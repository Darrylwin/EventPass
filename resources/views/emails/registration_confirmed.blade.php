<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Confirmation d'inscription</title>
</head>
<body style="font-family: Arial, Helvetica, sans-serif; color: #111;">
<p>Bonjour {{ $user->name }},</p>

<p>Votre inscription à l'événement <strong>{{ $event->title }}</strong> est confirmée.</p>

<p>Votre pass : <strong style="font-family: monospace; font-size: 1.25rem;">{{ $registration->pass_code }}</strong></p>

<p>
    Vous pouvez retrouver votre pass et les détails de l'événement en vous rendant sur :
    <a href="{{ route('events.show', $event) }}">la page de l'événement</a>
    ou
    <a href="{{ route('participant.dashboard') }}">votre tableau de bord</a>.
</p>

<p>Merci,<br>{{ config('app.name') }}</p>

</body>
</html>
