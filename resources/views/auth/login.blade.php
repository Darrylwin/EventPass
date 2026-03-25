<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion — {{ config('app.name') }}</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=playfair-display:700|dm-sans:400,500" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        body {
            font-family: 'DM Sans', sans-serif;
            background-color: #0c0c0f;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            overflow: hidden;
        }

        /* Cercles décoratifs en arrière-plan */
        body::before {
            content: '';
            position: absolute;
            width: 600px;
            height: 600px;
            border-radius: 50%;
            background: radial-gradient(circle, rgba(99, 57, 234, 0.15) 0%, transparent 70%);
            top: -150px;
            right: -150px;
            pointer-events: none;
        }

        body::after {
            content: '';
            position: absolute;
            width: 400px;
            height: 400px;
            border-radius: 50%;
            background: radial-gradient(circle, rgba(234, 57, 120, 0.1) 0%, transparent 70%);
            bottom: -100px;
            left: -100px;
            pointer-events: none;
        }

        .card {
            background: rgba(255, 255, 255, 0.03);
            border: 1px solid rgba(255, 255, 255, 0.08);
            backdrop-filter: blur(20px);
            border-radius: 24px;
            padding: 48px 40px;
            width: 100%;
            max-width: 420px;
            position: relative;
            z-index: 10;
            animation: fadeUp 0.5s ease forwards;
        }

        @keyframes fadeUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .brand {
            font-family: 'Playfair Display', serif;
            font-size: 28px;
            color: #ffffff;
            margin-bottom: 4px;
            letter-spacing: -0.5px;
        }

        .brand span {
            color: #8b5cf6;
        }

        .subtitle {
            font-size: 14px;
            color: rgba(255, 255, 255, 0.4);
            margin-bottom: 36px;
        }

        label {
            display: block;
            font-size: 12px;
            font-weight: 500;
            text-transform: uppercase;
            letter-spacing: 0.08em;
            color: rgba(255, 255, 255, 0.5);
            margin-bottom: 8px;
        }

        input[type="email"],
        input[type="password"],
        input[type="text"] {
            width: 100%;
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 12px;
            padding: 14px 16px;
            font-size: 15px;
            color: #ffffff;
            font-family: 'DM Sans', sans-serif;
            transition: border-color 0.2s, background 0.2s;
            outline: none;
            box-sizing: border-box;
        }

        input[type="email"]:focus,
        input[type="password"]:focus,
        input[type="text"]:focus {
            border-color: rgba(139, 92, 246, 0.6);
            background: rgba(139, 92, 246, 0.05);
        }

        input::placeholder {
            color: rgba(255, 255, 255, 0.2);
        }

        .field {
            margin-bottom: 20px;
        }

        .error-msg {
            font-size: 12px;
            color: #f87171;
            margin-top: 6px;
        }

        .remember-row {
            display: flex;
            align-items: center;
            gap: 8px;
            margin-bottom: 28px;
        }

        .remember-row input[type="checkbox"] {
            width: 16px;
            height: 16px;
            accent-color: #8b5cf6;
            cursor: pointer;
        }

        .remember-row span {
            font-size: 13px;
            color: rgba(255, 255, 255, 0.4);
        }

        .btn-primary {
            width: 100%;
            padding: 15px;
            background: linear-gradient(135deg, #8b5cf6, #6d28d9);
            border: none;
            border-radius: 12px;
            font-size: 15px;
            font-weight: 500;
            color: #ffffff;
            font-family: 'DM Sans', sans-serif;
            cursor: pointer;
            transition: opacity 0.2s, transform 0.15s;
            letter-spacing: 0.02em;
        }

        .btn-primary:hover {
            opacity: 0.9;
            transform: translateY(-1px);
        }

        .btn-primary:active {
            transform: translateY(0);
        }

        .footer-link {
            text-align: center;
            margin-top: 24px;
            font-size: 13px;
            color: rgba(255, 255, 255, 0.35);
        }

        .footer-link a {
            color: #8b5cf6;
            text-decoration: none;
            font-weight: 500;
        }

        .footer-link a:hover {
            text-decoration: underline;
        }

        .divider {
            height: 1px;
            background: rgba(255, 255, 255, 0.06);
            margin: 28px 0;
        }
    </style>
</head>
<body>
<div class="card">
    <div class="brand">Event<span>Pass</span></div>
    <p class="subtitle">Connectez-vous à votre espace</p>

    @if ($errors->any())
        <div
            style="background: rgba(248,113,113,0.1); border: 1px solid rgba(248,113,113,0.3); border-radius: 10px; padding: 12px 16px; margin-bottom: 20px; font-size: 13px; color: #f87171;">
            {{ $errors->first() }}
        </div>
    @endif

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <div class="field">
            <label for="email">Adresse email</label>
            <input
                type="email"
                id="email"
                name="email"
                value="{{ old('email') }}"
                placeholder="vous@exemple.com"
                required
                autofocus
            >
            @error('email')
            <p class="error-msg">{{ $message }}</p>
            @enderror
        </div>

        <div class="field">
            <label for="password">Mot de passe</label>
            <input
                type="password"
                id="password"
                name="password"
                placeholder="••••••••"
                required
            >
            @error('password')
            <p class="error-msg">{{ $message }}</p>
            @enderror
        </div>

        <div class="remember-row">
            <input type="checkbox" id="remember" name="remember">
            <span>Se souvenir de moi</span>
        </div>

        <button type="submit" class="btn-primary">Se connecter</button>
    </form>

    <div class="divider"></div>

    <p class="footer-link">
        Pas encore de compte ? <a href="{{ route('register') }}">S'inscrire</a>
    </p>
</div>
</body>
</html>
