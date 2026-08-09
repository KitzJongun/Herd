<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Ticket;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\StreamedResponse;

class DashboardController extends Controller
{
    public function index()
    {
        // 1. Jumlah tiket masuk tiap bulan
        $ticketsPerMonth = Ticket::selectRaw('strftime("%m", created_at) as month, count(*) as count')
            ->whereRaw('strftime("%Y", created_at) = strftime("%Y", "now")')
            ->groupBy('month')
            ->pluck('count', 'month')->toArray();

        // 2. Jumlah tiket closed tiap bulan
        $closedPerMonth = Ticket::where('status', 'Closed')
            ->selectRaw('strftime("%m", created_at) as month, count(*) as count')
            ->whereRaw('strftime("%Y", created_at) = strftime("%Y", "now")')
            ->groupBy('month')
            ->pluck('count', 'month')->toArray();

        $chartData = [
            'labels' => ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
            'tickets' => [],
            'closed' => [],
        ];

        for ($i = 1; $i <= 12; $i++) {
            $m = str_pad($i, 2, '0', STR_PAD_LEFT);
            $chartData['tickets'][] = $ticketsPerMonth[$m] ?? 0;
            $chartData['closed'][] = $closedPerMonth[$m] ?? 0;
        }

        // 3. Sepuluh kategori dengan tiket terbanyak
        $topCategories = Ticket::select('category_id', DB::raw('count(*) as total'))
            ->with('category')
            ->groupBy('category_id')
            ->orderByDesc('total')
            ->limit(10)
            ->get();

        return view('admin.dashboard', compact('chartData', 'topCategories'));
    }

    public function export()
    {
        $fileName = 'tickets_export.csv';
        $tickets = Ticket::with('category')->get();

        $headers = array(
            "Content-type"        => "text/csv",
            "Content-Disposition" => "attachment; filename=$fileName",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        );

        $columns = ['ID', 'No Tiket', 'Nama Klien', 'Email', 'Judul', 'Kategori', 'Status', 'Prioritas', 'Dibuat'];

        $callback = function() use($tickets, $columns) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);
            foreach ($tickets as $ticket) {
                $row['ID'] = $ticket->id;
                $row['No Tiket'] = $ticket->ticket_number;
                $row['Nama Klien'] = $ticket->client_name;
                $row['Email'] = $ticket->client_email;
                $row['Judul'] = $ticket->title;
                $row['Kategori'] = $ticket->category ? $ticket->category->name : '-';
                $row['Status'] = $ticket->status;
                $row['Prioritas'] = $ticket->priority;
                $row['Dibuat'] = $ticket->created_at;

                fputcsv($file, array($row['ID'], $row['No Tiket'], $row['Nama Klien'], $row['Email'], $row['Judul'], $row['Kategori'], $row['Status'], $row['Prioritas'], $row['Dibuat']));
            }
            fclose($file);
        };

        return new StreamedResponse($callback, 200, $headers);
    }
}
