<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('app.name') }}</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=playfair-display:700|dm-sans:400,500" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-background text-foreground">

<nav class="border-b border-border px-6 py-4 flex items-center justify-between">
    <a href="{{ route('home') }}" class="text-lg font-bold">
        Event<span class="text-primary"> Pass</span>
    </a>

    <div class="flex items-center gap-4">
        @auth
            <span class="text-sm text-muted-foreground">{{ auth()->user()->name }}</span>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button
                    type="submit"
                    class="text-sm text-muted-foreground hover:text-foreground transition"
                >
                    Déconnexion
                </button>
            </form>
        @else
            <a href="{{ route('login') }}" class="text-sm text-muted-foreground hover:text-foreground transition">
                Connexion
            </a>
            <a href="{{ route('register') }}"
               class="text-sm bg-primary text-primary-foreground px-4 py-1.5 rounded-lg hover:opacity-90 transition">
                Inscription
            </a>
        @endauth
    </div>
</nav>

<main class="px-6 py-8">
    @if (session('success'))
        <div class="bg-primary/10 border border-primary/30 text-primary text-sm rounded-lg px-4 py-3 mb-6">
            {{ session('success') }}
        </div>
    @endif

    @if (session('error'))
        <div class="bg-destructive/10 border border-destructive/30 text-destructive text-sm rounded-lg px-4 py-3 mb-6">
            {{ session('error') }}
        </div>
    @endif

    @yield('content')
</main>

</body>
</html>
