<x-emails.layouts.base :subject="'Pesan Kontak Baru — '.$contact->subject">
    <h2 style="margin:0 0 16px; font-size:18px; color:#0a0a0a;">Pesan Kontak Baru Masuk</h2>

    <table role="presentation" width="100%" cellpadding="8" cellspacing="0" style="font-size:14px;">
        <tr>
            <td style="color:#666666; width:140px;">Nama</td>
            <td style="font-weight:600;">{{ $contact->name }}</td>
        </tr>
        <tr>
            <td style="color:#666666;">Email</td>
            <td>{{ $contact->email }}</td>
        </tr>
        <tr>
            <td style="color:#666666;">Telepon</td>
            <td>{{ $contact->phone ?? '-' }}</td>
        </tr>
        <tr>
            <td style="color:#666666;">Sumber</td>
            <td>{{ $contact->source }}</td>
        </tr>
        <tr>
            <td style="color:#666666;">Subjek</td>
            <td>{{ $contact->subject ?? '-' }}</td>
        </tr>
    </table>

    <div style="margin-top:16px; padding:16px; background-color:#f5f5f7; border-radius:8px; font-size:14px; line-height:1.6;">
        {{ $contact->message }}
    </div>

    <div style="margin-top:24px;">
        <a href="{{ route('admin.contact.show', $contact->id) }}"
           style="display:inline-block; background-color:#fa8600; color:#ffffff; text-decoration:none; padding:10px 20px; border-radius:8px; font-size:14px; font-weight:600;">
            Lihat di Dashboard
        </a>
    </div>
</x-emails.layouts.base>
