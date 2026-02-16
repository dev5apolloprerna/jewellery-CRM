<!DOCTYPE html>
<html lang="{{ str_replace('_','-', app()->getLocale()) }}">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <!-- Avoid indexing this temporary page -->
  <meta name="robots" content="noindex, nofollow">
  <title>{{ config('app.name', 'Our site') }} — Under Construction</title>
  <style>
    :root { --bg:#0f172a; --fg:#e2e8f0; --muted:#94a3b8; --accent:#22c55e; }
    * { box-sizing: border-box; }
    html,body { height: 100%; }
    body {
      margin: 0;
      min-height: 100%;
      display: grid;
      place-items: center;
      background: radial-gradient(1000px 600px at 10% 10%, #1e293b 0, #0f172a 60%);
      color: var(--fg);
      font-family: system-ui, -apple-system, Segoe UI, Roboto, Helvetica, Arial, sans-serif;
    }
    .card {
      width: min(640px, 92vw);
      background: rgba(255,255,255,0.06);
      border: 1px solid rgba(255,255,255,0.08);
      border-radius: 18px;
      padding: 32px;
      backdrop-filter: blur(6px);
      box-shadow: 0 10px 30px rgba(0,0,0,.35);
    }
    h1 { margin: 8px 0 6px; font-size: clamp(28px, 4vw, 36px); letter-spacing: .3px; }
    p { margin: 6px 0 0; color: var(--muted); line-height: 1.6; }
    .row { display:flex; align-items:center; gap:10px; margin-top:18px; flex-wrap: wrap; }
    .badge {
      display:inline-block; padding:6px 10px; border-radius:999px;
      background: rgba(255,255,255,0.08); border:1px solid rgba(255,255,255,0.12);
      font-size:13px; color: var(--fg);
    }
    .progress { margin-top: 20px; height: 10px; background: rgba(255,255,255,.1); border-radius: 999px; overflow: hidden; }
    .bar { width: 65%; height: 100%; background: linear-gradient(90deg, var(--accent), #38bdf8); }
    .footer { margin-top: 24px; display:flex; justify-content:space-between; flex-wrap:wrap; gap:10px; font-size:14px; color:var(--muted) }
    a { color:#a5b4fc; text-decoration:none; border-bottom:1px dotted #a5b4fc33; }
    a:hover { border-color:#a5b4fcaa; }
  </style>
</head>
<body>
  <main class="card" role="main" aria-label="Under construction">
    <div class="badge">🚧 Under Construction</div>
    <h1>{{ config('app.name', 'Our site') }} is getting a makeover</h1>
    <p>
      We’re working behind the scenes to launch something great.
      Thanks for your patience. For urgent queries, email
      <a href="mailto:{{ config('mail.from.address') }}">{{ config('mail.from.address') }}</a>.
    </p>

    <div class="progress" aria-label="Progress" aria-valuenow="65" aria-valuemin="0" aria-valuemax="100">
      <div class="bar"></div>
    </div>

    <div class="row">
      <span class="badge">✅ Secure & Fast</span>
      <span class="badge">🛠️ Maintenance</span>
      <span class="badge">💬 Support Available</span>
    </div>

    <div class="footer">
      <span>&copy; {{ now()->year }} {{ config('app.name') }}. All rights reserved.</span>
      <span>Launching soon.</span>
    </div>
  </main>
</body>
</html>
