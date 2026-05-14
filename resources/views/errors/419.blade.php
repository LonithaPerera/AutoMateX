<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Session Expired — AutoMateX</title>
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
            max-width: 400px;
            width: 100%;
            text-align: center;
        }
        .icon {
            width: 60px;
            height: 60px;
            border-radius: 1rem;
            background: rgba(251,191,36,0.1);
            border: 1px solid rgba(251,191,36,0.25);
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1.5rem;
        }
        .code {
            font-size: 0.7rem;
            letter-spacing: 0.15em;
            color: #fbbf24;
            text-transform: uppercase;
            margin-bottom: 0.5rem;
        }
        h1 {
            font-size: 1.5rem;
            font-weight: 700;
            margin-bottom: 0.75rem;
        }
        p {
            font-size: 0.875rem;
            color: #64748b;
            line-height: 1.6;
            margin-bottom: 1.75rem;
        }
        .btn {
            display: inline-block;
            padding: 0.75rem 2rem;
            border-radius: 0.75rem;
            font-size: 0.875rem;
            font-weight: 600;
            letter-spacing: 0.05em;
            text-decoration: none;
            background: linear-gradient(135deg, #0066ff, #00f5ff);
            color: #080c14;
            transition: opacity 0.2s;
        }
        .btn:hover { opacity: 0.9; }
    </style>
</head>
<body>
    <div class="card">
        <div class="icon">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="#fbbf24" aria-hidden="true" width="32" height="32">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/>
            </svg>
        </div>
        <p class="code">419 · Session Expired</p>
        <h1>Your session timed out</h1>
        <p>The page token has expired. This usually happens if the page was left open for too long. Go back and try submitting the form again.</p>
        <a href="{{ url('/') }}" class="btn">← Go Back</a>
    </div>
</body>
</html>
