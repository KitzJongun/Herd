<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use App\Models\Category;
use App\Services\AiAssistantService;
use Illuminate\Http\Request;

class TicketController extends Controller
{
    public function show(Ticket $ticket, AiAssistantService $aiService)
    {
        // Fitur Asisten AI: Saat membuka detail tiket yang baru masuk (status "Open" dan belum diproses)
        if ($ticket->status === 'Open' && !$ticket->ai_processed) {
            
            // Panggil API secara sinkron untuk memproses saat halaman diload
            $aiData = $aiService->analyzeTicket($ticket->title, $ticket->description);
            
            if ($aiData) {
                // Find suggested category by name matching
                $suggestedCat = Category::where('name', 'LIKE', '%' . $aiData['category'] . '%')->first();

                // Validation priority
                $prio = in_array(ucwords($aiData['priority'] ?? ''), ['Rendah', 'Sedang', 'Tinggi']) 
                        ? ucwords($aiData['priority']) : null;
                
                $ticket->update([
                    'ai_summary' => $aiData['summary'] ?? null,
                    'ai_suggested_category_id' => $suggestedCat ? $suggestedCat->id : null,
                    'ai_suggested_priority' => $prio,
                    'ai_processed' => true // so it doesn't trigger again
                ]);

                // reload from db to reflect changes
                $ticket->refresh();
            }
        }

        $ticket->load('category', 'suggestedCategory', 'replies.user');

        return view('admin.ticket-detail', compact('ticket'));
    }
}
