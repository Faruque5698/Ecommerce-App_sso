<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Dashboard</title>
    <style>
        body {
            margin: 0;
            font-family: Arial, sans-serif;
        }
        header {
            background-color: #1f2937;
            color: white;
            padding: 15px 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .logo {
            display: flex;
            align-items: center;
        }
        .logo img {
            height: 40px;
            margin-right: 10px;
        }
        .user-info {
            display: flex;
            align-items: center;
            gap: 15px;
        }
        main {
            padding: 20px;
        }
        .logout-btn {
            background-color: #ef4444;
            color: white;
            border: none;
            padding: 8px 14px;
            border-radius: 4px;
            cursor: pointer;
        }
        .logout-btn:hover {
            background-color: #dc2626;
        }
    </style>
</head>
<body>
    <header>
        <div class="logo">
            <h2>Ecommerce Client Dashboard</h2>
        </div>
        <div class="user-info">
            {{-- Logout Form --}}
            <form action="{{ route('logout') }}" method="POST" id="logoutForm">
                @csrf
                <button type="submit" class="logout-btn">Logout</button>
            </form>
        </div>
    </header>

    <main>
        <h3>Welcome, {{ $user['name'] ?? 'User' }}</h3>
        <p>This is your dashboard. You can add widgets and stats here later.</p>
    </main>
</body>
</html>
