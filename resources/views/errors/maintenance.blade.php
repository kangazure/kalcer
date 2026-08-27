<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="robots" content="noindex, nofollow">
    <title>Sedang Maintenance — PT Jaringan Teknologi Sejahtera</title>
    <link rel="icon" type="image/png" href="{{ asset('images/logo/jts-favicon.png') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        * { margin:0; padding:0; box-sizing:border-box; }
        body {
            font-family: 'Plus Jakarta Sans', system-ui, sans-serif;
            background: linear-gradient(135deg, #0a0a0a 0%, #1a1208 45%, #2b1503 100%);
            min-height: 100vh; display:flex; align-items:center; justify-content:center;
            padding: 24px; position: relative; overflow: hidden;
        }
        body::before {
            content:''; position:absolute; inset:0;
            background-image: linear-gradient(rgba(250,134,0,0.06) 1px, transparent 1px), linear-gradient(90deg, rgba(250,134,0,0.06) 1px, transparent 1px);
            background-size: 40px 40px; opacity:0.6;
        }
        .glow { position:absolute; top:30%; left:50%; transform:translateX(-50%); width:500px; height:500px; background:radial-gradient(circle, rgba(250,134,0,0.25), transparent 70%); pointer-events:none; }
        .card { position:relative; text-align:center; max-width:480px; }
        .logo { height:64px; width:64px; object-fit:contain; margin:0 auto 32px; opacity:0.9; }
        .spinner { width:56px; height:56px; border:3px solid rgba(250,134,0,0.2); border-top-color:#fa8600; border-radius:50%; margin:0 auto 28px; animation: spin 1s linear infinite; }
        @keyframes spin { to { transform: rotate(360deg); } }
        h1 { color:#fff; font-size:28px; font-weight:800; margin-bottom:12px; }
        p { color:#a4a4a4; font-size:15px; line-height:1.7; margin-bottom:28px; }
        .badge { display:inline-flex; align-items:center; gap:8px; background:rgba(250,134,0,0.1); border:1px solid rgba(250,134,0,0.25); color:#fea034; font-size:13px; font-weight:600; padding:8px 18px; border-radius:99px; }
        .dot { width:7px; height:7px; border-radius:50%; background:#fa8600; animation: pulse 1.5s ease-in-out infinite; }
        @keyframes pulse { 0%,100% { opacity:1; } 50% { opacity:0.4; } }
        .contact { margin-top: 32px; padding-top: 24px; border-top: 1px solid rgba(255,255,255,0.08); font-size: 13px; color: #666; }
        .contact a { color: #fea034; text-decoration: none; }
    </style>
</head>
<body>
    <div class="glow"></div>
    <div class="card">
        <img src="{{ asset('images/logo/jts-logo-mark-square.png') }}" alt="Logo JTS" class="logo">
        <div class="spinner"></div>
        <h1>Sedang Dalam Perbaikan</h1>
        <p>
            Kami sedang melakukan pemeliharaan sistem untuk meningkatkan kualitas layanan.
            Website akan kembali normal dalam waktu singkat. Mohon maaf atas ketidaknyamanannya.
        </p>
        <div class="badge">
            <span class="dot"></span>
            Estimasi selesai dalam beberapa saat
        </div>
        <div class="contact">
            Butuh bantuan segera? Hubungi <a href="https://wa.me/6282183999981">WhatsApp +62 821-8399-9981</a>
        </div>
    </div>
</body>
</html>
