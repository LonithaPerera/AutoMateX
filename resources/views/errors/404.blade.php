<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>404 — Page Not Found · AutoMateX</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            background: #080c14;
            color: #fff;
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 1.5rem;
        }
        .card {
            background: rgba(255,255,255,0.04);
            border: 1px solid rgba(0,245,255,0.15);
            border-radius: 1.25rem;
            padding: 2.5rem 2rem;
            max-width: 420px;
            width: 100%;
            text-align: center;
        }
        .icon {
            width: 64px;
            height: 64px;
            border-radius: 1rem;
            background: rgba(0,245,255,0.08);
            border: 1px solid rgba(0,245,255,0.2);
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1.5rem;
        }
        .code {
            font-size: 0.7rem;
            letter-spacing: 0.15em;
            color: #00f5ff;
            text-transform: uppercase;
            margin-bottom: 0.5rem;
            font-family: 'Courier New', monospace;
        }
        h1 {
            font-size: 1.5rem;
            font-weight: 700;
            margin-bottom: 0.75rem;
            letter-spacing: -0.02em;
        }
        p {
            font-size: 0.875rem;
            color: #64748b;
            line-height: 1.6;
            margin-bottom: 1.75rem;
        }
        .actions { display: flex; flex-direction: column; gap: 0.75rem; }
        .btn {
            display: inline-block;
            padding: 0.75rem 2rem;
            border-radius: 0.75rem;
            font-size: 0.875rem;
            font-weight: 600;
            letter-spacing: 0.05em;
            text-decoration: none;
            transition: opacity 0.2s;
        }
        .btn-primary {
            background: linear-gradient(135deg, #0066ff, #00f5ff);
            color: #080c14;
        }
        .btn-secondary {
            background: rgba(255,255,255,0.04);
            border: 1px solid rgba(255,255,255,0.1);
            color: #64748b;
        }
        .btn:hover { opacity: 0.85; }
        .brand {
            font-size: 0.75rem;
            color: #334155;
            margin-top: 2rem;
            letter-spacing: 0.1em;
            text-transform: uppercase;
            font-family: 'Courier New', monospace;
        }
    </style>
</head>
<body>
    <div class="card">
        <div class="icon">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="#00f5ff" aria-hidden="true" width="32" height="32">
                <path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z"/>
            </svg>
        </div>
        <p class="code">404 · Not Found</p>
        <h1>Page not found</h1>
        <p>The page you're looking for doesn't exist or may have been moved. Check the URL and try again.</p>
        <div class="actions">
            <a href="{{ url('/') }}" class="btn btn-primary">← Go Back</a>
            <a href="/" class="btn btn-secondary">Go to Home</a>
        </div>
        <p class="brand">AutoMateX</p>
    </div>
</body>
</html>
