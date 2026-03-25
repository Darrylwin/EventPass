<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inscription — {{ config('app.name') }}</title>
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
        <p class="text-muted-foreground text-sm mt-2">Créez votre compte participant</p>
    </div>

    {{-- Card --}}
    <div class="bg-card border border-border rounded-xl p-8 shadow-sm">

        @if ($errors->any())
            <div
                class="bg-destructive/10 border border-destructive/30 text-destructive text-sm rounded-lg px-4 py-3 mb-6">
                @foreach ($errors->all() as $error)
                    <div>{{ $error }}</div>
                @endforeach
            </div>
        @endif

        <form method="POST" action="{{ route('register') }}">
            @csrf

            <div class="mb-5">
                <label for="name"
                       class="block text-xs font-medium text-muted-foreground uppercase tracking-widest mb-2">
                    Nom complet
                </label>
                <input
                    type="text"
                    id="name"
                    name="name"
                    value="{{ old('name') }}"
                    placeholder="Marie Louise"
                    required
                    autofocus
                    class="w-full bg-input/30 border border-border rounded-lg px-4 py-3 text-sm text-foreground placeholder:text-muted-foreground focus:outline-none focus:ring-2 focus:ring-ring focus:border-transparent transition"
                >
                @error('name')
                <p class="text-destructive text-xs mt-1.5">{{ $message }}</p>
                @enderror
            </div>

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
                <p class="text-muted-foreground text-xs mt-1.5">Minimum 8 caractères</p>
                @error('password')
                <p class="text-destructive text-xs mt-1.5">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-6">
                <label for="password_confirmation"
                       class="block text-xs font-medium text-muted-foreground uppercase tracking-widest mb-2">
                    Confirmer le mot de passe
                </label>
                <input
                    type="password"
                    id="password_confirmation"
                    name="password_confirmation"
                    placeholder="••••••••"
                    required
                    class="w-full bg-input/30 border border-border rounded-lg px-4 py-3 text-sm text-foreground placeholder:text-muted-foreground focus:outline-none focus:ring-2 focus:ring-ring focus:border-transparent transition"
                >
            </div>

            <button
                type="submit"
                class="w-full bg-primary text-primary-foreground font-medium text-sm py-3 rounded-lg hover:opacity-90 active:scale-[0.99] transition-all"
            >
                Créer mon compte
            </button>
        </form>

        <div class="border-t border-border mt-6 pt-6 text-center">
            <p class="text-sm text-muted-foreground">
                Déjà un compte ?
                <a href="{{ route('login') }}" class="text-primary font-medium hover:underline">
                    Se connecter
                </a>
            </p>
        </div>

    </div>
</div>

</body>
</html>
