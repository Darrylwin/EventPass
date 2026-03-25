<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion — {{ config('app.name') }}</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=playfair-display:700|dm-sans:400,500" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-background flex items-center justify-center px-4 py-12">

<div class="w-full max-w-md">

    {{-- Brand --}}
    <div class="text-center mb-8">
        <h1 class="text-3xl font-bold text-foreground tracking-tight">
            Event<span class="text-primary"> Pass</span>
        </h1>
        <p class="text-muted-foreground text-sm mt-2">Connectez-vous à votre espace</p>
    </div>

    {{-- Card --}}
    <div class="bg-card border border-border rounded-xl p-8 shadow-sm">

        @if ($errors->any())
            <div
                class="bg-destructive/10 border border-destructive/30 text-destructive text-sm rounded-lg px-4 py-3 mb-6">
                {{ $errors->first() }}
            </div>
        @endif

        <form method="POST" action="{{ route('login') }}">
            @csrf

            <div class="mb-5">
                <label for="email"
                       class="block text-xs font-medium text-muted-foreground uppercase tracking-widest mb-2">
                    Adresse email
                </label>
                <input
                    type="email"
                    id="email"
                    name="email"
                    value="{{ old('email') }}"
                    placeholder="vous@exemple.com"
                    required
                    autofocus
                    class="w-full bg-input/30 border border-border rounded-lg px-4 py-3 text-sm text-foreground placeholder:text-muted-foreground focus:outline-none focus:ring-2 focus:ring-ring focus:border-transparent transition"
                >
                @error('email')
                <p class="text-destructive text-xs mt-1.5">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-5">
                <label for="password"
                       class="block text-xs font-medium text-muted-foreground uppercase tracking-widest mb-2">
                    Mot de passe
                </label>
                <input
                    type="password"
                    id="password"
                    name="password"
                    placeholder="••••••••"
                    required
                    class="w-full bg-input/30 border border-border rounded-lg px-4 py-3 text-sm text-foreground placeholder:text-muted-foreground focus:outline-none focus:ring-2 focus:ring-ring focus:border-transparent transition"
                >
                @error('password')
                <p class="text-destructive text-xs mt-1.5">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex items-center gap-2 mb-6">
                <input
                    type="checkbox"
                    id="remember"
                    name="remember"
                    class="w-4 h-4 accent-primary cursor-pointer"
                >
                <label for="remember" class="text-sm text-muted-foreground cursor-pointer">
                    Se souvenir de moi
                </label>
            </div>

            <button
                type="submit"
                class="w-full bg-primary text-primary-foreground font-medium text-sm py-3 rounded-lg hover:opacity-90 active:scale-[0.99] transition-all"
            >
                Se connecter
            </button>
        </form>

        <div class="border-t border-border mt-6 pt-6 text-center">
            <p class="text-sm text-muted-foreground">
                Pas encore de compte ?
                <a href="{{ route('register') }}" class="text-primary font-medium hover:underline">
                    S'inscrire
                </a>
            </p>
        </div>

    </div>
</div>

</body>
</html>
