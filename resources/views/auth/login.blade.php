<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | Lp_Beauty</title>

    <style>
        * { box-sizing: border-box; }

        body {
            margin: 0;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
            font-family: Arial, sans-serif;
            background: linear-gradient(135deg, #fff8f5, #f8dce7);
            color: #593c48;
        }

        .login-card {
            width: 100%;
            max-width: 420px;
            padding: 38px 32px;
            background: #fffdfb;
            border: 1px solid #f2d5df;
            border-radius: 22px;
            box-shadow: 0 12px 35px rgba(130, 75, 95, 0.12);
        }

        .brand {
            text-align: center;
            margin-bottom: 28px;
        }

        .brand h1 {
            margin: 0 0 8px;
            color: #c45d85;
            font-size: 32px;
        }

        .brand p {
            margin: 0 0 8px;
            color: #987b88;
            font-size: 14px;
        }

        label {
            display: block;
            margin: 16px 0 8px;
            font-size: 14px;
            font-weight: bold;
        }

        input[type="email"],
        input[type="password"] {
            width: 100%;
            padding: 13px 14px;
            border: 1px solid #e8cbd6;
            border-radius: 10px;
            background: #fff;
            color: #593c48;
            font-size: 15px;
            outline: none;
        }

        input:focus {
            border-color: #cf7396;
            box-shadow: 0 0 0 3px rgba(207, 115, 150, 0.12);
        }

        .remember {
            display: flex;
            align-items: center;
            gap: 8px;
            margin: 17px 0;
            font-size: 14px;
        }

        .remember label {
            margin: 0;
            font-weight: normal;
        }

        button {
            width: 100%;
            padding: 13px;
            border: none;
            border-radius: 10px;
            background: #c45d85;
            color: white;
            font-size: 16px;
            font-weight: bold;
            cursor: pointer;
        }

        button:hover { background: #ad496f; }

        .alert {
            padding: 12px;
            margin-bottom: 16px;
            border-radius: 9px;
            font-size: 14px;
        }

        .success { background: #edf8ee; color: #28683a; }
        .error { background: #fff0f1; color: #a13248; }

        .footer {
            margin-top: 22px;
            text-align: center;
            color: #a18b95;
            font-size: 12px;
        }
    </style>
</head>

<body>
    <main class="login-card">
        <div class="brand">
            <h1>Lp_Beauty ♡</h1>
            <p>Your Makeup & Skincare Corner</p>
            <p>Silakan login untuk melanjutkan</p>
        </div>

        @if (session('success'))
            <div class="alert success">
                {{ session('success') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="alert error">
                {{ $errors->first() }}
            </div>
        @endif

        <form action="{{ route('login.store') }}" method="POST">
            @csrf

            <label for="email">Email</label>
            <input
                type="email"
                id="email"
                name="email"
                value="{{ old('email') }}"
                placeholder="Masukkan email"
                autocomplete="username"
                required
                autofocus
            >

            <label for="password">Password</label>
            <input
                type="password"
                id="password"
                name="password"
                placeholder="Masukkan password"
                autocomplete="current-password"
                required
            >

            <div class="remember">
                <input type="checkbox" id="remember" name="remember" value="1">
                <label for="remember">Ingat saya</label>
            </div>

            <button type="submit">Login</button>
        </form>

        <div class="footer">
            &copy; {{ date('Y') }} Lp_Beauty. Made with love ♡
        </div>
    </main>
</body>
</html>
