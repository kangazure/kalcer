<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $subject ?? config('app.name') }}</title>
</head>
<body style="margin:0; padding:0; background-color:#f5f5f7; font-family: 'Segoe UI', Roboto, Helvetica, Arial, sans-serif; color:#161616;">
    <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="background-color:#f5f5f7; padding:32px 0;">
        <tr>
            <td align="center">
                <table role="presentation" width="600" cellpadding="0" cellspacing="0" style="background-color:#ffffff; border-radius:12px; overflow:hidden; box-shadow:0 4px 20px rgba(0,0,0,0.06);">
                    <tr>
                        <td style="background-color:#0a0a0a; padding:24px 32px;">
                            <span style="color:#fa8600; font-size:20px; font-weight:700; letter-spacing:0.5px;">JTS</span>
                            <span style="color:#ffffff; font-size:14px; margin-left:8px;">Jaringan Teknologi Sejahtera</span>
                        </td>
                    </tr>
                    <tr>
                        <td style="padding:32px;">
                            {{ $slot }}
                        </td>
                    </tr>
                    <tr>
                        <td style="background-color:#f5f5f7; padding:20px 32px; font-size:12px; color:#666666;">
                            Email ini dikirim otomatis oleh sistem internal {{ config('app.name') }}. Jangan membalas email ini secara langsung.
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>
