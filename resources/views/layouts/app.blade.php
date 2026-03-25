<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('app.name') }}</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=playfair-display:700|dm-sans:400,500" rel="stylesheet">
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-background text-foreground">

<nav class="border-b border-border px-6 py-4 flex items-center justify-between">
    <a href="{{ route('home') }}" class="text-lg font-bold">
        Event<span class="text-primary"> Pass</span>
    </a>

    <div class="flex items-center gap-4">
        @auth
            <div class="relative" x-data="{ open: false }">
                <button 
                    @click="open = !open" 
                    class="w-9 h-9 rounded-full bg-primary flex items-center justify-center text-primary-foreground font-bold text-sm shadow-sm hover:scale-105 transition-all focus:outline-none"
                >
                    {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                </button>

                <div 
                    x-show="open" 
                    @click.away="open = false"
                    x-transition:enter="transition ease-out duration-100"
                    x-transition:enter-start="transform opacity-0 scale-95"
                    x-transition:enter-end="transform opacity-100 scale-100"
                    class="absolute right-0 mt-2 w-56 bg-card border border-border rounded-xl shadow-xl py-1 z-50 focus:outline-none"
                    style="display: none;"
                >
                    <div class="px-4 py-3 border-b border-border mb-1">
                        <p class="text-[10px] text-muted-foreground uppercase font-black tracking-widest leading-none mb-1">Utilisateur</p>
                        <p class="text-sm font-bold truncate text-foreground">{{ auth()->user()->name }}</p>
                        <p class="text-[10px] text-muted-foreground italic">{{ auth()->user()->email }}</p>
                    </div>
                    
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="w-full text-left px-4 py-2.5 text-sm text-destructive hover:bg-destructive/10 font-semibold transition flex items-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                            Déconnexion
                        </button>
                    </form>
                </div>
            </div>
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
