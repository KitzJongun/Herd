<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function dashboard()
    {
        $stats = [
            'total_tickets' => Ticket::count(),
            'open_tickets' => Ticket::where('status', 'Open')->count(),
            'resolved_tickets' => Ticket::where('status', 'Closed')->count(),
            'ai_assisted' => Ticket::where('ai_processed', true)->count()
        ];

        $recentTickets = Ticket::with(['category', 'suggestedCategory'])
            ->orderBy('created_at', 'desc')
            ->take(10)
            ->get();

        return view('admin.dashboard', compact('stats', 'recentTickets'));
    }
}
