<!DOCTYPE html>
<html>
<head>
    <title>Balasan Tiket Laporan HELPDESK AI</title>
</head>
<body style="font-family: Arial, sans-serif; line-height: 1.6; color: #333; padding: 20px;">
    <h2>Halo, {{ $name }}</h2>
    <p>Tiket laporan Anda dengan nomor <strong>{{ $ticket_code }}</strong> telah mendapatkan balasan.</p>
    
    <div style="background-color: #f8fafc; padding: 15px; border-radius: 5px; margin: 20px 0; border-left: 4px solid #10b981;">
        <p style="white-space: pre-line;">{{ $reply }}</p>
    </div>

    <p style="font-style: italic; color: #64748b; font-size: 0.9em;">
        *Balasan ini dibuat otomatis oleh sistem AI kami.
    </p>

    <p>Terima kasih,<br>Tim HELPDESK AI</p>
</body>
</html>
