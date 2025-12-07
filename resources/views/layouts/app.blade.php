<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Senbak URL Shortener</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <style>
        body {
            background: #f7f9fc;
            font-family: system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif;
            color: #222;
            margin: 0;
        }
        header {
            background: #ffffff;
            border-bottom: 1px solid #d4d8dd;
            padding: 10px 18px;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        header .left {
            display: flex;
            gap: 18px;
            align-items: center;
            font-weight: 600;
        }
        header a {
            text-decoration: none;
            color: #333;
            font-size: 14px;
        }
        header a:hover {
            text-decoration: underline;
        }
        .container {
            max-width: 900px;
            margin: 24px auto;
            padding: 0 14px;
        }
        .card {
            background: #ffffff;
            border-radius: 6px;
            border: 1px solid #d4d8dd;
            padding: 18px 20px;
            margin-bottom: 18px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.07);
        }
        h1, h2, h3 {
            margin: 0 0 12px;
            font-weight: 600;
        }
        label {
            display: block;
            margin-bottom: 4px;
            font-size: 13px;
        }
        input[type="text"], input[type="email"], input[type="password"], input[type="url"], select {
            width: 100%;
            padding: 8px 10px;
            border-radius: 4px;
            border: 1px solid #d4d8dd;
            margin-bottom: 12px;
            font-size: 14px;
        }
        button, .btn {
            display: inline-block;
            padding: 8px 15px;
            border-radius: 4px;
            border: none;
            background: #2d6cdf;
            color: #fff;
            font-size: 14px;
            cursor: pointer;
        }
        button:hover, .btn:hover {
            background: #2458b8;
        }
        .btn-secondary {
            background: #e4e6eb;
            color: #222;
        }
        .btn-secondary:hover {
            background: #d4d6da;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 6px;
            font-size: 14px;
        }
        th, td {
            text-align: left;
            padding: 8px 6px;
            border-bottom: 1px solid #e2e5e9;
        }
        th {
            background: #f1f3f7;
            font-weight: 600;
        }
        tr:hover td {
            background: #fafbff;
        }
        .flash {
            margin-bottom: 12px;
            padding: 8px 10px;
            border-radius: 4px;
            font-size: 13px;
            background: #e1f3d8;
            border: 1px solid #b6d99a;
        }
        .error {
            color: #b3261e;
            font-size: 13px;
            margin-top: -8px;
            margin-bottom: 10px;
        }
        .small-text {
            font-size: 13px;
            color: #666;
        }
    </style>
</head>
<body>
@if(Auth::check())
    <header>
        <div class="left">
            <span>Senbak</span>
            <a href="{{ route('dashboard') }}">Dashboard</a>
            <a href="{{ route('short-urls.index') }}">Short URLs</a>
            @can('invite-user')
                <a href="{{ route('invitations.create') }}">Invitations</a>
            @endcan
        </div>
        <div>
            <span class="small-text">{{ Auth::user()->name }} ({{ Auth::user()->role }})</span>
            <form action="{{ route('logout') }}" method="POST" style="display:inline;">
                @csrf
                <button type="submit" class="btn-secondary" style="margin-left:10px;">Logout</button>
            </form>
        </div>
    </header>
@endif

<div class="container">
    @if(session('status'))
        <div class="flash">{{ session('status') }}</div>
    @endif

    @yield('content')
</div>
</body>
</html>
