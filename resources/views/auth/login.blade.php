<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar Sesión - Taquilla CoMotor</title>
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body {
            font-family: sans-serif;
            background-color: #f0f2f5;
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
        }
        .login-card {
            background: #fff;
            padding: 40px;
            border-radius: 10px;
            box-shadow: 0 2px 12px rgba(0,0,0,0.12);
            width: 100%;
            max-width: 380px;
        }
        .login-card h1 {
            text-align: center;
            font-size: 1.4rem;
            color: #333;
            margin-bottom: 6px;
        }
        .login-card .subtitle {
            text-align: center;
            color: #888;
            font-size: 0.9rem;
            margin-bottom: 28px;
        }
        label {
            display: block;
            font-size: 0.85rem;
            color: #555;
            margin-bottom: 5px;
            font-weight: 600;
        }
        input[type="email"],
        input[type="password"] {
            width: 100%;
            padding: 10px 14px;
            border: 1px solid #ccc;
            border-radius: 6px;
            font-size: 0.95rem;
            margin-bottom: 18px;
            transition: border-color 0.2s;
        }
        input:focus {
            outline: none;
            border-color: #007bff;
        }
        input.is-invalid {
            border-color: #dc3545;
        }
        .error-msg {
            color: #dc3545;
            font-size: 0.82rem;
            margin-top: -14px;
            margin-bottom: 14px;
        }
        .remember-row {
            display: flex;
            align-items: center;
            gap: 8px;
            margin-bottom: 22px;
            font-size: 0.88rem;
            color: #555;
        }
        .btn-login {
            width: 100%;
            padding: 11px;
            background-color: #007bff;
            color: #fff;
            border: none;
            border-radius: 6px;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: background-color 0.2s;
        }
        .btn-login:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <div class="login-card">
        <h1>Taquilla CoMotor</h1>
        <p class="subtitle">Ingresa tus credenciales para continuar</p>

        <form method="POST" action="/login">
            @csrf

            <label for="email">Correo electrónico</label>
            <input
                type="email"
                id="email"
                name="email"
                value="{{ old('email') }}"
                autocomplete="email"
                autofocus
                class="{{ $errors->has('email') ? 'is-invalid' : '' }}"
            >
            @error('email')
                <p class="error-msg">{{ $message }}</p>
            @enderror

            <label for="password">Contraseña</label>
            <input
                type="password"
                id="password"
                name="password"
                autocomplete="current-password"
                class="{{ $errors->has('password') ? 'is-invalid' : '' }}"
            >
            @error('password')
                <p class="error-msg">{{ $message }}</p>
            @enderror

            <div class="remember-row">
                <input type="checkbox" name="remember" id="remember">
                <label for="remember" style="margin:0; font-weight:normal;">Recordarme</label>
            </div>

            <button type="submit" class="btn-login">Entrar</button>
        </form>
    </div>
</body>
</html>
