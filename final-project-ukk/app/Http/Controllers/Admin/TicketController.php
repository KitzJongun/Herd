<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Ticket;
use App\Models\Category;
use App\Models\TicketReply;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;
use App\Mail\TicketRepliedMail;

class TicketController extends Controller
{
    public function index(Request $request)
    {
        $query = Ticket::with('category', 'admin')->latest();

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('priority')) {
            $query->where('priority', $request->priority);
        }
        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }
        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('ticket_number', 'like', '%'.$request->search.'%')
                  ->orWhere('client_name', 'like', '%'.$request->search.'%')
                  ->orWhere('client_email', 'like', '%'.$request->search.'%')
                  ->orWhere('title', 'like', '%'.$request->search.'%');
            });
        }

        $tickets    = $query->paginate(10)->withQueryString();
        $categories = \App\Models\Category::all();

        return view('admin.tickets.index', compact('tickets', 'categories'));
    }

    public function export(Request $request)
    {
        $query = Ticket::with('category', 'admin')->latest();

        if ($request->filled('status'))      $query->where('status', $request->status);
        if ($request->filled('priority'))    $query->where('priority', $request->priority);
        if ($request->filled('category_id')) $query->where('category_id', $request->category_id);
        if ($request->filled('date_from'))   $query->whereDate('created_at', '>=', $request->date_from);
        if ($request->filled('date_to'))     $query->whereDate('created_at', '<=', $request->date_to);
        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('ticket_number', 'like', '%'.$request->search.'%')
                  ->orWhere('client_name', 'like', '%'.$request->search.'%')
                  ->orWhere('title', 'like', '%'.$request->search.'%');
            });
        }

        $tickets  = $query->get();
        $fileName = 'tickets_export_' . now()->format('Ymd_His') . '.csv';

        $headers = [
            'Content-Type'        => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"$fileName\"",
            'Pragma'              => 'no-cache',
            'Cache-Control'       => 'must-revalidate, post-check=0, pre-check=0',
            'Expires'             => '0',
        ];

        $callback = function () use ($tickets) {
            $file = fopen('php://output', 'w');
            // BOM agar Excel bisa baca UTF-8
            fwrite($file, "\xEF\xBB\xBF");
            fputcsv($file, ['ID', 'No Tiket', 'Nama Klien', 'Email', 'Judul', 'Kategori', 'Status', 'Prioritas', 'Ringkasan AI', 'Dibuat']);

            foreach ($tickets as $t) {
                fputcsv($file, [
                    $t->id,
                    $t->ticket_number,
                    $t->client_name,
                    $t->client_email,
                    $t->title,
                    $t->category ? $t->category->name : '-',
                    $t->status,
                    $t->priority ?? '-',
                    $t->ai_summary ?? '-',
                    $t->created_at->format('d/m/Y H:i'),
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function show(Ticket $ticket)
    {
        $ticket->load('replies.user', 'category', 'aiSuggestedCategory');
        $categories = Category::all();

        // AI Logic
        if ($ticket->status === 'Open' && empty($ticket->ai_summary)) {
            $this->callAiForTicket($ticket, $categories);
        }

        return view('admin.tickets.show', compact('ticket', 'categories'));
    }

    private function callAiForTicket(Ticket $ticket, $categories)
    {
        $apiKey = env('GROQ_API_KEY');
        if (!$apiKey) return;

        $catNames = $categories->pluck('name')->implode(', ');

        $prompt = "Tolong analisis tiket keluhan ini secara singkat. \n"
                . "Judul: " . $ticket->title . "\n"
                . "Deskripsi: " . $ticket->description . "\n"
                . "Harap kembalikan HANYA dalam format JSON seperti ini (TANPA MARKDOWN, TANPA EXTRA TEXT):\n"
                . "{\n"
                . '  "summary": "Ringkasan 1 kalimat",'."\n"
                . '  "category": "Pilih salah satu yang paling cocok dari daftar ini: ' . $catNames . '",'."\n"
                . '  "priority": "Pilih antara Rendah, Sedang, atau Tinggi"'."\n"
                . "}";

        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $apiKey,
                'Content-Type' => 'application/json',
            ])->post('https://api.groq.com/openai/v1/chat/completions', [
                'model' => 'llama-3.1-8b-instant',
                'messages' => [
                    ['role' => 'user', 'content' => $prompt]
                ],
                'response_format' => ['type' => 'json_object']
            ]);

            if ($response->successful()) {
                $text = $response->json('choices.0.message.content');
                
                // bersihkan JSON dari markdown blocks (misal: ```json ... ```)
                $cleanText = str_replace(['```json', '```'], '', $text);
                $cleanText = trim($cleanText);

                $data = json_decode($cleanText, true);

                if ($data) {
                    $ticket->ai_summary = $data['summary'] ?? null;
                    
                    if (isset($data['category'])) {
                        $c = Category::where('name', 'like', '%' . $data['category'] . '%')->first();
                        $ticket->ai_suggested_category_id = $c ? $c->id : null;
                    }

                    $priorities = ['Rendah', 'Sedang', 'Tinggi'];
                    if (isset($data['priority']) && in_array($data['priority'], $priorities)) {
                        $ticket->ai_suggested_priority = $data['priority'];
                    }

                    $ticket->save();
                }
            }
        } catch (\Exception $e) {
            // Abaikan error AI jika limit atau gagal
        }
    }

    public function reply(Request $request, Ticket $ticket)
    {
        $request->validate(['message' => 'required|string']);

        TicketReply::create([
            'ticket_id' => $ticket->id,
            'user_id' => auth()->id(),
            'message' => $request->message
        ]);

        // Otomatis tutup tiket saat admin membalas
        $ticket->status = 'Closed';
        $ticket->admin_id = auth()->id();
        $ticket->save();

        try {
            Mail::to($ticket->client_email)->send(new TicketRepliedMail($ticket->fresh(['replies'])));
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Mail Error (TicketRepliedMail): ' . $e->getMessage());
        }

        return back()->with('success', 'Balasan berhasil dikirim.');
    }

    public function updateStatus(Request $request, Ticket $ticket)
    {
        $request->validate(['status' => 'required|in:Open,In Progress,Closed', 'priority' => 'nullable|in:Rendah,Sedang,Tinggi']);
        
        $ticket->status = $request->status;
        if($request->priority) $ticket->priority = $request->priority;
        $ticket->admin_id = auth()->id();
        $ticket->save();

        return back()->with('success', 'Status tiket diperbarui.');
    }

    public function acceptAiSuggest(Request $request, Ticket $ticket)
    {
        if ($ticket->ai_suggested_category_id) {
            $ticket->category_id = $ticket->ai_suggested_category_id;
        }
        if ($ticket->ai_suggested_priority) {
            $ticket->priority = $ticket->ai_suggested_priority;
        }
        $ticket->admin_id = auth()->id();
        $ticket->save();

        return back()->with('success', 'Sugesti AI telah diaplikasikan.');
    }
}
