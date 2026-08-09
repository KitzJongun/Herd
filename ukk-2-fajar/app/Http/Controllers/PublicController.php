<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Ticket;
use App\Models\Reply;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

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
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'category_id' => 'required|exists:categories,id',
            'attachment' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
            'g-recaptcha-response' => 'required'
        ]);

        // Verify reCAPTCHA
        /** @var \Illuminate\Http\Client\Response $response */
        $response = \Illuminate\Support\Facades\Http::asForm()->post('https://www.google.com/recaptcha/api/siteverify', [
            'secret' => env('RECAPTCHA_SECRET_KEY'),
            'response' => $request->input('g-recaptcha-response'),
            'remoteip' => $request->ip(),
        ]);

        $captchaData = $response->json();

        if (!$captchaData['success'] || $captchaData['score'] < 0.5) {
            return redirect()->back()->withErrors(['g-recaptcha-response' => 'Verifikasi keamanan gagal. Silakan coba lagi.'])->withInput();
        }

        $ticket_number = 'TIX-' . strtoupper(Str::random(6));

        $path = null;
        if ($request->hasFile('attachment')) {
            $path = $request->file('attachment')->store('attachments', 'public');
        }

        $ticket = Ticket::create([
            'ticket_number' => $ticket_number,
            'name' => $request->name,
            'email' => $request->email,
            'title' => $request->title,
            'description' => $request->description,
            'category_id' => $request->category_id,
            'attachment' => $path,
            'status' => 'Open',
        ]);

        // NEXUS AI ANALYSIS (Gemini)
        try {
            $prompt = "Kamu adalah teknisi komputer profesional NEXUS AI.
                       Jawab dengan bahasa Indonesia santai tapi jelas.
                       Berikan solusi step-by-step untuk masalah berikut:
                       
                       Judul: {$ticket->title}
                       Deskripsi: {$ticket->description}
                       
                       Aturan:
                       - Solusi harus praktis.
                       - Jangan terlalu panjang.
                       - Akhiri dengan 'Semoga membantu! - Tim NEXUS AI'";

            /** @var \Illuminate\Http\Client\Response $aiResponse */
            $aiResponse = \Illuminate\Support\Facades\Http::withHeaders([
                'Content-Type' => 'application/json',
            ])->post("https://generativelanguage.googleapis.com/v1/models/gemini-1.5-flash:generateContent?key=" . env('GEMINI_API_KEY'), [
                'contents' => [
                    [
                        'parts' => [
                            ['text' => $prompt]
                        ]
                    ]
                ]
            ]);

            if ($aiResponse->successful()) {
                $aiText = $aiResponse->json()['candidates'][0]['content']['parts'][0]['text'] ?? "Laporan Anda telah diterima dan sedang dianalisis oleh sistem.";
                
                $ticket->update([
                    'ai_summary' => Str::limit($aiText, 255),
                    'ai_processed' => true,
                    'priority' => 'Medium', // Default if AI processing simple text
                ]);

                // Create AI Auto-Reply
                Reply::create([
                    'ticket_id' => $ticket->id,
                    'user_id' => 1, // Default Admin ID
                    'message' => "🤖 **NEXUS AI AUTO-RESPONSE** \n\n" . $aiText,
                ]);
            } else {
                \Illuminate\Support\Facades\Log::error("Gemini API Fail: " . $aiResponse->body());
            }
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error("Gemini AI Exception: " . $e->getMessage());
        }

        return redirect()->back()->with('success', $ticket_number);
    }

    public function track(Request $request)
    {
        $request->validate([
            'ticket_number' => 'required|string'
        ]);

        $ticket_number = $request->input('ticket_number');

        $mockTicket = Ticket::with(['category', 'replies.user', 'suggestedCategory'])
            ->where('ticket_number', $ticket_number)
            ->first();

        if (!$mockTicket) {
            return redirect()->back()->withErrors(['ticket_number' => 'Nomor tiket tidak ditemukan.'])->withInput();
        }

        return view('public.track-result', compact('mockTicket'));
    }
}
