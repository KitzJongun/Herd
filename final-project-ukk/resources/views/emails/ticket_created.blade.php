<!DOCTYPE html>
<html>
<head>
    <title>Tiket Laporan HELPDESK AI</title>
</head>
<body style="font-family: Arial, sans-serif; line-height: 1.6; color: #333; padding: 20px;">
    <h2>Halo, {{ $ticket->client_name }}</h2>
    <p>Kami telah menerima laporan Anda. Berikut adalah detail tiket Anda:</p>
    
    <div style="background-color: #f8fafc; padding: 15px; border-radius: 5px; margin: 20px 0; border-left: 4px solid #2563eb;">
        <p><strong>Nomor Tiket:</strong> {{ $ticket->ticket_number }}</p>
        <p><strong>Judul Laporan:</strong> {{ $ticket->title }}</p>
        <p><strong>Status:</strong> {{ $ticket->status }}</p>
    </div>

    <p>Anda dapat melacak status dan melihat riwayat tanggapan dengan memasukkan <strong>Nomor Tiket</strong> di halaman pelacakan kami.</p>
    
    <p>Terima kasih,<br>Tim HELPDESK AI</p>
</body>
</html>
