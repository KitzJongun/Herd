<!DOCTYPE html>
<html>

<head>
    <title>Jawaban Tiket Anda - HELPDESK AI</title>
</head>

<body
    style="font-family: Arial, sans-serif; line-height: 1.6; color: #333; padding: 20px; max-width: 600px; margin: 0 auto;">

    <div style="background-color: #2563eb; padding: 20px; border-radius: 8px 8px 0 0; text-align: center;">
        <h1 style="color: white; margin: 0; font-size: 1.4rem;">HELPDESK AI</h1>
        <p style="color: #bfdbfe; margin: 5px 0 0;">Tiket Anda Telah Dijawab</p>
    </div>

    <div style="border: 1px solid #e2e8f0; border-top: none; padding: 25px; border-radius: 0 0 8px 8px;">
        <p style="margin-top: 0;">Halo, <strong>{{ $ticket->client_name }}</strong>!</p>
        <p>Admin kami telah memberikan jawaban atas laporan tiket Anda. Berikut detailnya:</p>

        <table style="width: 100%; border-collapse: collapse; margin: 15px 0; font-size: 0.9rem;">
            <tr>
                <td
                    style="padding: 8px 10px; font-weight: bold; color: #64748b; width: 35%; background: #f8fafc; border: 1px solid #e2e8f0;">
                    Nomor Tiket</td>
                <td style="padding: 8px 10px; border: 1px solid #e2e8f0;">{{ $ticket->ticket_number }}</td>
            </tr>
            <tr>
                <td
                    style="padding: 8px 10px; font-weight: bold; color: #64748b; background: #f8fafc; border: 1px solid #e2e8f0;">
                    Judul Laporan</td>
                <td style="padding: 8px 10px; border: 1px solid #e2e8f0;">{{ $ticket->title }}</td>
            </tr>
            <tr>
                <td
                    style="padding: 8px 10px; font-weight: bold; color: #64748b; background: #f8fafc; border: 1px solid #e2e8f0;">
                    Status</td>
                <td style="padding: 8px 10px; border: 1px solid #e2e8f0;">
                    <span
                        style="background-color: #dcfce7; color: #166534; padding: 2px 10px; border-radius: 20px; font-size: 0.85rem; font-weight: bold;">
                        ✅ Closed
                    </span>
                </td>
            </tr>
        </table>

        <div
            style="background-color: #f0f9ff; padding: 18px; border-radius: 8px; margin: 20px 0; border-left: 4px solid #2563eb;">
            <p style="margin: 0 0 8px 0; font-weight: bold; color: #1e40af;">💬 Jawaban dari Admin:</p>
            @php
                $adminReply = $ticket->replies->sortByDesc('created_at')->first(fn($r) => $r->user_id !== null);
            @endphp
            <p style="margin: 0; white-space: pre-line; color: #1e293b;">{{ $adminReply ? $adminReply->message : '-' }}
            </p>
        </div>

        <p style="color: #64748b; font-size: 0.9rem;">
            Untuk melihat seluruh riwayat percakapan, kunjungi halaman
            <a href="{{ url('/lacak') }}" style="color: #2563eb; text-decoration: none; font-weight: bold;">Lacak
                Tiket</a>
            dan masukkan nomor tiket Anda.
        </p>

        <hr style="border: none; border-top: 1px solid #e2e8f0; margin: 20px 0;">
        <p style="margin: 0; color: #94a3b8; font-size: 0.85rem; text-align: center;">
            Terima kasih telah menggunakan layanan kami.<br>
            <strong style="color: #475569;">Tim HELPDESK AI</strong>
        </p>
    </div>

</body>

</html>