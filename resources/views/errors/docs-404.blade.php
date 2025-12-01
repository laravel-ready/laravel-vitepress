<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Page Not Found - Documentation</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        :root {
            --bg-primary: #f8fafc;
            --bg-secondary: #ffffff;
            --text-primary: #1e293b;
            --text-secondary: #64748b;
            --accent: #f59e0b;
            --accent-hover: #d97706;
            --border: #e2e8f0;
            --shadow: rgba(0, 0, 0, 0.1);
        }

        @media (prefers-color-scheme: dark) {
            :root {
                --bg-primary: #0f172a;
                --bg-secondary: #1e293b;
                --text-primary: #f1f5f9;
                --text-secondary: #94a3b8;
                --accent: #f59e0b;
                --accent-hover: #fbbf24;
                --border: #334155;
                --shadow: rgba(0, 0, 0, 0.3);
            }
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, sans-serif;
            background-color: var(--bg-primary);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
            color: var(--text-primary);
        }

        .container {
            text-align: center;
            background: var(--bg-secondary);
            padding: 60px 40px;
            border-radius: 16px;
            border: 1px solid var(--border);
            box-shadow: 0 25px 50px -12px var(--shadow);
            max-width: 500px;
            width: 100%;
        }

        .error-code {
            font-size: 120px;
            font-weight: 700;
            color: var(--accent);
            line-height: 1;
            margin-bottom: 10px;
        }

        .error-title {
            font-size: 24px;
            font-weight: 600;
            color: var(--text-primary);
            margin-bottom: 16px;
        }

        .error-message {
            font-size: 16px;
            color: var(--text-secondary);
            margin-bottom: 32px;
            line-height: 1.6;
        }

        .back-link {
            display: inline-block;
            background-color: var(--accent);
            color: #ffffff;
            text-decoration: none;
            padding: 14px 32px;
            border-radius: 8px;
            font-weight: 500;
            font-size: 16px;
            transition: background-color 0.2s, transform 0.2s;
        }

        .back-link:hover {
            background-color: var(--accent-hover);
            transform: translateY(-2px);
        }

        .info-bar {
            display: flex;
            flex-direction: column;
            gap: 10px;
            margin-top: 32px;
            padding: 16px 20px;
            background-color: var(--bg-primary);
            border: 1px solid var(--border);
            border-radius: 8px;
            font-size: 14px;
            color: var(--text-secondary);
        }

        .info-bar code {
            background-color: var(--border);
            padding: 10px 6px;
            border-radius: 4px;
            font-family: ui-monospace, SFMono-Regular, Menlo, Monaco, Consolas, monospace;
            font-size: 13px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="error-code">404</div>
        <h1 class="error-title">Page Not Found</h1>
        <p class="error-message">
            The documentation page you're looking for doesn't exist or has been moved.
        </p>
        <a href="{{ url(config('vitepress.route.prefix', 'docs')) }}" class="back-link">
            Back to Documentation
        </a>
        <div class="info-bar">
            <p>
                Did you build and publish assets? Run:
            </p>

            <code>php artisan vitepress:build</code>
        </div>
    </div>
</body>
</html>
