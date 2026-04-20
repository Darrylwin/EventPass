<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', config('app.name'))</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=playfair-display:700|dm-sans:400,500,600" rel="stylesheet">
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="icon" href="{{ asset('favib.png') }}" type="image/png">
    <link rel="apple-touch-icon" href="{{ asset('favib.png') }}">
</head>
<body class="min-h-screen bg-background text-foreground">

<nav
    class="sticky top-0 z-40 border-b border-border bg-background/95 backdrop-blur-sm px-6 py-0 flex items-center justify-between h-14">

    {{-- Brand --}}
    <div class="flex items-center gap-8">
        <a href="{{ route('home') }}" class="flex items-center text-lg font-bold shrink-0 gap-2">
            <img src="{{ asset('logo.png') }}" alt="{{ config('app.name', 'EventPass') }}" class="h-8 w-auto" />
        </a>

        {{-- Navigation contextuelle selon le rôle --}}
        @auth
            @if(auth()->user()->isParticipant())
                <div class="hidden md:flex items-center gap-1">
                    <a href="{{ route('home') }}"
                       class="px-3 py-1.5 rounded-md text-sm font-medium transition-colors {{ request()->routeIs('home') || request()->routeIs('events.*') ? 'bg-primary/10 text-primary' : 'text-muted-foreground hover:text-foreground hover:bg-muted' }}">
                        Événements
                    </a>
                    <a href="{{ route('participant.dashboard') }}"
                       class="px-3 py-1.5 rounded-md text-sm font-medium transition-colors {{ request()->routeIs('participant.dashboard') ? 'bg-primary/10 text-primary' : 'text-muted-foreground hover:text-foreground hover:bg-muted' }}">
                        Mes inscriptions
                    </a>
                </div>
            @elseif(auth()->user()->isOrganisateur())
                <div class="hidden md:flex items-center gap-1">
                    <a href="{{ route('organisateur.dashboard') }}"
                       class="px-3 py-1.5 rounded-md text-sm font-medium transition-colors {{ request()->routeIs('organisateur.dashboard') ? 'bg-primary/10 text-primary' : 'text-muted-foreground hover:text-foreground hover:bg-muted' }}">
                        Dashboard
                    </a>
                    <a href="{{ route('organisateur.events.index') }}"
                       class="px-3 py-1.5 rounded-md text-sm font-medium transition-colors {{ request()->routeIs('organisateur.events.*') ? 'bg-primary/10 text-primary' : 'text-muted-foreground hover:text-foreground hover:bg-muted' }}">
                        Mes événements
                    </a>
                    <a href="{{ route('organisateur.events.create') }}"
                       class="px-3 py-1.5 rounded-md text-sm font-medium transition-colors text-muted-foreground hover:text-foreground hover:bg-muted">
                        + Créer
                    </a>
                </div>
            @elseif(auth()->user()->isAdmin())
                <div class="hidden md:flex items-center gap-1">
                    <a href="/admin"
                       class="px-3 py-1.5 rounded-md text-sm font-medium text-muted-foreground hover:text-foreground hover:bg-muted transition-colors">
                        Panel Admin
                    </a>
                    <a href="{{ route('home') }}"
                       class="px-3 py-1.5 rounded-md text-sm font-medium text-muted-foreground hover:text-foreground hover:bg-muted transition-colors">
                        Voir le site
                    </a>
                </div>
            @endif
        @endauth
    </div>

    {{-- Zone droite --}}
    <div class="flex items-center gap-3">
        @auth
            {{-- Badge rôle (visible uniquement sur desktop) --}}
            <span class="hidden lg:inline-flex items-center px-2 py-0.5 rounded text-[10px] font-semibold uppercase tracking-wider
                {{ auth()->user()->isAdmin() ? 'bg-destructive/10 text-destructive' : '' }}
                {{ auth()->user()->isOrganisateur() ? 'bg-amber-500/10 text-amber-600' : '' }}
                {{ auth()->user()->isParticipant() ? 'bg-primary/10 text-primary' : '' }}
            ">
                {{ auth()->user()->role }}
            </span>

            {{-- Menu utilisateur --}}
            <div class="relative" x-data="{ open: false }">
                <button
                    @click="open = !open"
                    class="w-8 h-8 rounded-full bg-primary flex items-center justify-center text-primary-foreground font-bold text-sm hover:scale-105 transition-all focus:outline-none focus:ring-2 focus:ring-ring focus:ring-offset-2"
                    aria-label="Menu utilisateur"
                >
                    {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                </button>

                <div
                    x-show="open"
                    @click.away="open = false"
                    x-transition:enter="transition ease-out duration-100"
                    x-transition:enter-start="transform opacity-0 scale-95"
                    x-transition:enter-end="transform opacity-100 scale-100"
                    x-transition:leave="transition ease-in duration-75"
                    x-transition:leave-start="transform opacity-100 scale-100"
                    x-transition:leave-end="transform opacity-0 scale-95"
                    class="absolute right-0 mt-2 w-60 bg-card border border-border rounded-xl shadow-lg py-1 z-50"
                    style="display: none;"
                >
                    {{-- Infos utilisateur --}}
                    <div class="px-4 py-3 border-b border-border">
                        <p class="text-sm font-semibold text-foreground truncate">{{ auth()->user()->name }}</p>
                        <p class="text-xs text-muted-foreground truncate">{{ auth()->user()->email }}</p>
                    </div>

                    {{-- Liens rapides selon le rôle --}}
                    @if(auth()->user()->isParticipant())
                        <div class="py-1 border-b border-border">
                            <a href="{{ route('home') }}"
                               class="flex items-center gap-3 px-4 py-2 text-sm text-foreground hover:bg-muted transition-colors">
                                <svg class="w-4 h-4 text-muted-foreground" fill="none" stroke="currentColor"
                                     viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                                Parcourir les événements
                            </a>
                            <a href="{{ route('participant.dashboard') }}"
                               class="flex items-center gap-3 px-4 py-2 text-sm text-foreground hover:bg-muted transition-colors">
                                <svg class="w-4 h-4 text-muted-foreground" fill="none" stroke="currentColor"
                                     viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"/>
                                </svg>
                                Mes pass
                            </a>
                        </div>
                    @elseif(auth()->user()->isOrganisateur())
                        <div class="py-1 border-b border-border">
                            <a href="{{ route('organisateur.dashboard') }}"
                               class="flex items-center gap-3 px-4 py-2 text-sm text-foreground hover:bg-muted transition-colors">
                                <svg class="w-4 h-4 text-muted-foreground" fill="none" stroke="currentColor"
                                     viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                                </svg>
                                Tableau de bord
                            </a>
                            <a href="{{ route('organisateur.events.index') }}"
                               class="flex items-center gap-3 px-4 py-2 text-sm text-foreground hover:bg-muted transition-colors">
                                <svg class="w-4 h-4 text-muted-foreground" fill="none" stroke="currentColor"
                                     viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                                </svg>
                                Mes événements
                            </a>
                            <a href="{{ route('organisateur.events.create') }}"
                               class="flex items-center gap-3 px-4 py-2 text-sm text-foreground hover:bg-muted transition-colors">
                                <svg class="w-4 h-4 text-muted-foreground" fill="none" stroke="currentColor"
                                     viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M12 4v16m8-8H4"/>
                                </svg>
                                Créer un événement
                            </a>
                        </div>
                    @elseif(auth()->user()->isAdmin())
                        <div class="py-1 border-b border-border">
                            <a href="/admin"
                               class="flex items-center gap-3 px-4 py-2 text-sm text-foreground hover:bg-muted transition-colors">
                                <svg class="w-4 h-4 text-muted-foreground" fill="none" stroke="currentColor"
                                     viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                </svg>
                                Panel d'administration
                            </a>
                        </div>
                    @endif

                    {{-- Déconnexion --}}
                    <div class="py-1">
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit"
                                    class="w-full flex items-center gap-3 px-4 py-2 text-sm text-destructive hover:bg-destructive/10 transition-colors">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                                </svg>
                                Déconnexion
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            {{-- Menu mobile hamburger --}}
            <div class="md:hidden" x-data="{ mobileOpen: false }">
                <button @click="mobileOpen = !mobileOpen" class="p-2 rounded-md hover:bg-muted transition-colors"
                        aria-label="Menu">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path x-show="!mobileOpen" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M4 6h16M4 12h16M4 18h16"/>
                        <path x-show="mobileOpen" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>

                <div x-show="mobileOpen" @click.away="mobileOpen = false"
                     class="absolute top-14 left-0 right-0 bg-card border-b border-border shadow-lg p-4 space-y-1"
                     style="display: none;">
                    @if(auth()->user()->isParticipant())
                        <a href="{{ route('home') }}"
                           class="block px-3 py-2 rounded-md text-sm hover:bg-muted transition-colors">Événements</a>
                        <a href="{{ route('participant.dashboard') }}"
                           class="block px-3 py-2 rounded-md text-sm hover:bg-muted transition-colors">Mes
                            inscriptions</a>
                    @elseif(auth()->user()->isOrganisateur())
                        <a href="{{ route('organisateur.dashboard') }}"
                           class="block px-3 py-2 rounded-md text-sm hover:bg-muted transition-colors">Dashboard</a>
                        <a href="{{ route('organisateur.events.index') }}"
                           class="block px-3 py-2 rounded-md text-sm hover:bg-muted transition-colors">Mes
                            événements</a>
                        <a href="{{ route('organisateur.events.create') }}"
                           class="block px-3 py-2 rounded-md text-sm hover:bg-muted transition-colors">Créer un
                            événement</a>
                    @elseif(auth()->user()->isAdmin())
                        <a href="/admin" class="block px-3 py-2 rounded-md text-sm hover:bg-muted transition-colors">Panel
                            Admin</a>
                    @endif
                </div>
            </div>

        @else
            <a href="{{ route('login') }}"
               class="text-sm text-muted-foreground hover:text-foreground transition-colors">
                Connexion
            </a>
            <a href="{{ route('register') }}"
               class="text-sm bg-primary text-primary-foreground px-3 py-1.5 rounded-lg hover:opacity-90 transition-opacity font-medium">
                S'inscrire
            </a>
        @endauth
    </div>
</nav>

<main class="px-4 md:px-6 py-6 md:py-8">

    {{-- Flash messages --}}
    @if (session('success'))
        <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)"
             x-transition:leave="transition ease-in duration-300"
             x-transition:leave-start="opacity-100 translate-y-0"
             x-transition:leave-end="opacity-0 -translate-y-2"
             class="max-w-6xl mx-auto mb-6 flex items-start gap-3 bg-primary/10 border border-primary/30 text-primary text-sm rounded-lg px-4 py-3">
            <svg class="w-4 h-4 mt-0.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
            </svg>
            <p>{{ session('success') }}</p>
            <button @click="show = false" class="ml-auto shrink-0 opacity-60 hover:opacity-100 transition-opacity">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>
    @endif

    @if (session('error'))
        <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 7000)"
             x-transition:leave="transition ease-in duration-300"
             x-transition:leave-start="opacity-100 translate-y-0"
             x-transition:leave-end="opacity-0 -translate-y-2"
             class="max-w-6xl mx-auto mb-6 flex items-start gap-3 bg-destructive/10 border border-destructive/30 text-destructive text-sm rounded-lg px-4 py-3">
            <svg class="w-4 h-4 mt-0.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16.5c-.77.833.192 2.5 1.732 2.5z"/>
            </svg>
            <p>{{ session('error') }}</p>
            <button @click="show = false" class="ml-auto shrink-0 opacity-60 hover:opacity-100 transition-opacity">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>
    @endif

    @yield('content')
</main>

</body>
</html>
