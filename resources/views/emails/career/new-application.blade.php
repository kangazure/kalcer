<x-emails.layouts.base :subject="'Lamaran Baru — '.($application->career?->title ?? 'Posisi')">
    <h2 style="margin:0 0 16px; font-size:18px; color:#0a0a0a;">Lamaran Kerja Baru</h2>

    <p style="font-size:14px; color:#434343;">
        Ada pelamar baru untuk posisi <strong>{{ $application->career?->title ?? 'Tidak diketahui' }}</strong>.
    </p>

    <table role="presentation" width="100%" cellpadding="8" cellspacing="0" style="font-size:14px;">
        <tr>
            <td style="color:#666666; width:140px;">Nama Lengkap</td>
            <td style="font-weight:600;">{{ $application->full_name }}</td>
        </tr>
        <tr>
            <td style="color:#666666;">Email</td>
            <td>{{ $application->email }}</td>
        </tr>
        <tr>
            <td style="color:#666666;">Telepon</td>
            <td>{{ $application->phone }}</td>
        </tr>
        <tr>
            <td style="color:#666666;">Ekspektasi Gaji</td>
            <td>{{ $application->expected_salary ? 'Rp '.number_format((float) $application->expected_salary, 0, ',', '.') : 'Negosiasi' }}</td>
        </tr>
    </table>

    <div style="margin-top:24px;">
        <a href="{{ route('admin.career.applications', $application->career_id) }}"
           style="display:inline-block; background-color:#fa8600; color:#ffffff; text-decoration:none; padding:10px 20px; border-radius:8px; font-size:14px; font-weight:600;">
            Lihat Lamaran di Dashboard
        </a>
    </div>
</x-emails.layouts.base>
