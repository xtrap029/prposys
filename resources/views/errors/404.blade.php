<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>404 — Page Not Found</title>
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background: #0a0a0a;
            color: #ededed;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
        }

        .container {
            text-align: center;
            position: relative;
            z-index: 1;
            padding: 2rem;
        }

        .glow {
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -60%);
            width: 600px;
            height: 600px;
            background: radial-gradient(circle, rgba(99,102,241,0.15) 0%, transparent 70%);
            pointer-events: none;
        }

        .number {
            font-size: clamp(8rem, 20vw, 16rem);
            font-weight: 800;
            line-height: 1;
            letter-spacing: -0.05em;
            background: linear-gradient(135deg, #6366f1 0%, #a855f7 50%, #ec4899 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            user-select: none;
            animation: pulse 4s ease-in-out infinite;
        }

        @keyframes pulse {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.75; }
        }

        .divider {
            width: 48px;
            height: 2px;
            background: linear-gradient(90deg, #6366f1, #ec4899);
            margin: 1.5rem auto;
            border-radius: 2px;
        }

        .title {
            font-size: 1.25rem;
            font-weight: 600;
            color: #ededed;
            margin-bottom: 0.75rem;
            letter-spacing: -0.01em;
        }

        .subtitle {
            font-size: 0.9rem;
            color: #737373;
            max-width: 320px;
            margin: 0 auto 2rem;
            line-height: 1.6;
        }

        .btn {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.6rem 1.4rem;
            border-radius: 8px;
            font-size: 0.875rem;
            font-weight: 500;
            text-decoration: none;
            cursor: pointer;
            transition: all 0.2s ease;
            border: none;
        }

        .btn-primary {
            background: #6366f1;
            color: #fff;
        }

        .btn-primary:hover {
            background: #5558e8;
            transform: translateY(-1px);
            box-shadow: 0 4px 20px rgba(99,102,241,0.4);
        }

        .grid-bg {
            position: fixed;
            inset: 0;
            background-image:
                linear-gradient(rgba(255,255,255,0.03) 1px, transparent 1px),
                linear-gradient(90deg, rgba(255,255,255,0.03) 1px, transparent 1px);
            background-size: 48px 48px;
            pointer-events: none;
        }
    </style>
</head>
<body>
    <div class="grid-bg"></div>
    <div class="glow"></div>
    <div class="container">
        <div class="number">404</div>
        <div class="divider"></div>
        <div class="title">Page not found</div>
        <p class="subtitle">The page you're looking for doesn't exist or you don't have access to it.</p>
        <a href="javascript:history.back()" class="btn btn-primary">
            <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M19 12H5M12 5l-7 7 7 7"/></svg>
            Go back
        </a>
    </div>
</body>
</html>
