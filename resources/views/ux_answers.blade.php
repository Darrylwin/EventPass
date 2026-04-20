@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto space-y-12 py-8">
    <div class="text-center space-y-4">
        <h1 class="text-5xl font-bold font-display tracking-tight">Justifications UX</h1>
        <p class="text-xl text-muted-foreground">Réponses aux questions posées pour la soutenance</p>
    </div>

    <div class="space-y-8">
        {{-- Question 1 --}}
        <div class="bg-card border border-border rounded-2xl p-8 shadow-sm">
            <h2 class="text-xl font-bold mb-4 text-primary flex items-start gap-3">
                <span class="flex-shrink-0 flex items-center justify-center w-8 h-8 rounded-full bg-primary/20 text-primary text-sm font-black">Q1</span>
                Comment affichez-vous le nombre de places restantes - et que se passe-t-il visuellement quand il n'en reste que 2 ?
            </h2>
            <div class="prose prose-lg dark:prose-invert text-foreground/80">
                <p>Le nombre de places restantes est affiché clairement sur la page de détail de l'événement dans le bloc de réservation, avec une mise en évidence de la capacité totale et des places disponibles.</p>
                <p><strong>Comportement visuel (≤ 2 places) :</strong> La zone du compteur subit une altération visuelle urgente : la bordure et le texte deviennent rouges <code class="text-destructive bg-destructive/10 px-1 rounded">text-destructive</code>, et une animation de pulsation <code class="text-foreground bg-muted px-1 rounded">animate-pulse</code> est appliquée pour attirer immédiatement l'attention de l'utilisateur sur la rareté, l'incitant à finaliser son inscription rapidement.</p>
            </div>
        </div>

        {{-- Question 2 --}}
        <div class="bg-card border border-border rounded-2xl p-8 shadow-sm">
            <h2 class="text-xl font-bold mb-4 text-primary flex items-start gap-3">
                <span class="flex-shrink-0 flex items-center justify-center w-8 h-8 rounded-full bg-primary/20 text-primary text-sm font-black">Q2</span>
                Où et comment le participant retrouve-t-il son pass facilement après inscription ?
            </h2>
            <div class="prose prose-lg dark:prose-invert text-foreground/80">
                <p>Après l'inscription, le participant est redirigé vers son <strong>Tableau de Bord (Dashboard)</strong> personnel. Un message de succès dynamique apparaît pour confirmer l'action.</p>
                <p>Le pass est affiché de manière proéminente dans les "Événements à venir" sous forme d'une étiquette bien distincte, avec une police "monospace" grasse et colorée pour le code, rendant la recherche visuelle instantanée lors de son arrivée à l'événement.</p>
            </div>
        </div>

        {{-- Question 3 --}}
        <div class="bg-card border border-border rounded-2xl p-8 shadow-sm">
            <h2 class="text-xl font-bold mb-4 text-primary flex items-start gap-3">
                <span class="flex-shrink-0 flex items-center justify-center w-8 h-8 rounded-full bg-primary/20 text-primary text-sm font-black">Q3</span>
                Que se passe-t-il si l'événement affiche « complet » alors que l'utilisateur était en train de s'inscrire ?
            </h2>
            <div class="prose prose-lg dark:prose-invert text-foreground/80">
                <p>Le système gère la concurrence au niveau du backend. Si un utilisateur finalise son inscription alors que la dernière place vient d'être prise, le contrôleur vérifie de nouveau la capacité <code class="text-foreground bg-muted px-1 rounded">if ($event->isFull())</code> avant de valider la transaction.</p>
                <p>L'utilisateur est redirigé vers la page précédente de l'événement avec une alerte rouge claire en haut de l'écran affichant temporairement l'erreur : <em>"Désolé, cet événement est complet."</em> Le bouton d'inscription devient ensuite formellement indisponible.</p>
            </div>
        </div>

        {{-- Question 4 --}}
        <div class="bg-card border border-border rounded-2xl p-8 shadow-sm">
            <h2 class="text-xl font-bold mb-4 text-primary flex items-start gap-3">
                <span class="flex-shrink-0 flex items-center justify-center w-8 h-8 rounded-full bg-primary/20 text-primary text-sm font-black">Q4</span>
                Comment différenciez-vous visuellement un événement passé d'un événement à venir sur la page d'accueil ?
            </h2>
            <div class="prose prose-lg dark:prose-invert text-foreground/80">
                <p>Sur la page d'accueil (qui affiche l'intégralité du catalogue public), les événements passés sont ostensiblement affichés mais visuellement séparés :</p>
                <ul>
                    <li>L'image et la carte ont un filtre <strong>niveau de gris (grayscale)</strong> et une <strong>opacité réduite</strong> pour signaler qu'ils ne sont plus actifs.</li>
                    <li>Un badge <strong>"Terminé"</strong> apparaît en haut de la carte de l'événement.</li>
                    <li>Les interactions de survol (hover) qui suggèrent la « cliquabilité » sont désactivées sur les boutons d'inscription depuis cette liste (le bouton affiche "Événement passé").</li>
                </ul>
                <p>Cela permet de conserver une archive valorisante du catalogue de l'organisateur tout en évitant la confusion d'utilisation.</p>
            </div>
        </div>
    </div>
</div>
@endsection
