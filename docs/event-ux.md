# UX et comportements — Affichage des places & flux d'inscription

Ce document décrit le comportement attendu et l'implémentation UX/backend pour l'affichage des places restantes, le signalement d'urgence (≤ 2 places), la récupération du pass après inscription, la gestion des conditions de concurrence (surbooking) et la différenciation visuelle des événements passés/à venir.

## Affichage du nombre de places restantes
- Afficher un badge clair sur la carte d'événement et dans la page de détail.
- Sur la page de détail (bloc réservation) : afficher `Places réservées` et `X/Y` et une jauge visuelle.

## Visuel quand il reste 2 places
- Seuils visuels :
  - >= 10 : vert (normal)
  - ≤ 5 : orange (« Peu de places »)
  - ≤ 2 : rouge + label **"Dernières places !"** + animation subtile (`animate-pulse`) pour attirer l'attention.

## Comportement du CTA selon le stock
- >0 : bouton actif `S'inscrire`.
- =0 : bouton désactivé et badge `Complet`, avec suggestion : « Rejoindre la liste d'attente » ou « Recevoir une notification ». 

## Récupération du pass après inscription
- Page de confirmation : affichage immédiat du pass (code / QR) sur la page de l'événement.
- Envoi automatique d'un email de confirmation contenant le pass et les liens (`page événement`, `mon tableau de bord`).
- Espace utilisateur : page `Mes inscriptions / Mes passes` listant et téléchargeant chaque pass.

## Gestion des courses (race conditions / surbooking)
- Côté serveur : utiliser une transaction atomique et verrouillage de la ligne de l'événement (`SELECT ... FOR UPDATE` via Eloquent `lockForUpdate()`), vérifier les places encore disponibles puis insérer l'enregistrement.
- Côté UX : si la réservation échoue parce que l'événement est devenu complet, afficher une modal/alerte claire : « Désolé, cet événement est devenu complet pendant votre inscription. » et proposer la liste d'attente ou événements alternatifs.

## Différenciation visuelle événements passés vs à venir
- Événements à venir : carte normale, date en évidence, CTA primaire `S'inscrire`.
- Événements passés : badge `Terminé`, opacité réduite (par ex. `opacity-60 grayscale`), CTA remplacé par `Voir le récapitulatif` ou non cliquable.
- Ordonnancement : lister d'abord les événements à venir, puis les passés.

## Actions réalisées dans le code
- Ajout du présent fichier `docs/event-ux.md`.
- Patch du contrôleur d'inscription participant pour :
  - utiliser une transaction verrouillée (éviter le surbooking),
  - envoyer un email de confirmation contenant le pass (Mailable).
- Ajustement de la partial `event-card` pour afficher un badge rouge quand il reste ≤ 2 places.

Si vous voulez que je génère aussi un PDF du pass ou une intégration Wallet (Apple/Google), je peux ajouter cela ensuite.
