<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;
use App\Mail\TicketCreatedMail;
use App\Models\TicketReply;
use App\Services\GroqService;

class PublicController extends Controller
{
    public function index()
    {
        $categories = Category::all();
        return view('public.index', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'client_name' => 'required|string|max:255',
            'client_email' => 'required|email|max:255',
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'category_id' => 'required|exists:categories,id',
            'attachment' => 'nullable|file|mimes:jpg,jpeg,png,pdf,doc,docx|max:2048',
            'g-recaptcha-response' => 'required',
        ], [
            'g-recaptcha-response.required' => 'CAPTCHA wajib diisi untuk keamanan.'
        ]);

        // Verify Captcha
        $response = Http::asForm()->post('https://www.google.com/recaptcha/api/siteverify', [
            'secret' => env('RECAPTCHA_SECRET_KEY', '6LeIxAcTAAAAAGG-vFI1TnRWxMZNFuojJ4WifJWe'),
            'response' => $request->input('g-recaptcha-response'),
        ]);

        if (!$response->json('success') || $response->json('score') < 0.5) {
            return back()->withInput()->withErrors(['g-recaptcha-response' => 'Sistem mendeteksi aktivitas mencurigakan (Skor bot rendah). Silakan coba lagi.']);
        }

        $data = $request->except(['g-recaptcha-response', 'attachment']);
        
        $data['ticket_number'] = 'TIX-' . strtoupper(Str::random(6));
        $data['status'] = 'Open';

        if ($request->hasFile('attachment')) {
            $data['attachment'] = $request->file('attachment')->store('attachments', 'public');
        }

        $ticket = Ticket::create($data);

        // Kirim email notifikasi tiket dibuat
        try {
            Mail::to($ticket->client_email)->send(new TicketCreatedMail($ticket));
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Mail Error (TicketCreatedMail): ' . $e->getMessage());
        }

        // AI buat sugesti balasan → simpan ke DB saja, tidak kirim email
        // Email jawaban baru dikirim setelah admin submit balasan
        try {
            $groq = new GroqService();
            $aiReplyContent = $groq->generateReply("Judul: {$ticket->title}\nKeluhan: {$ticket->description}");

            if ($aiReplyContent) {
                TicketReply::create([
                    'ticket_id' => $ticket->id,
                    'message'   => $aiReplyContent,
                    'sender'    => 'ai',
                    'user_id'   => null
                ]);
            }
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('AI Reply Flow Error: ' . $e->getMessage());
        }

        return redirect()->route('lacak')->with('success', 'Tiket berhasil dikirim. Nomor Tiket Anda adalah: ' . $ticket->ticket_number . '. Kami juga telah mengirimkan email notifikasi ke alamat Anda.');
    }

    public function track()
    {
        return view('public.track');
    }

    public function check(Request $request)
    {
        $request->validate([
            'ticket_number' => 'required|string'
        ]);

        $ticket = Ticket::with(['replies' => function($query) {
            $query->orderBy('created_at', 'asc');
        }, 'replies.user', 'category'])->where('ticket_number', $request->ticket_number)->first();

        if (!$ticket) {
            return back()->withErrors(['ticket_number' => 'Nomor Tiket tidak ditemukan.']);
        }

        return view('public.track_result', compact('ticket'));
    }
}
