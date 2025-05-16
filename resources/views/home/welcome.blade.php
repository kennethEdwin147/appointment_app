<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <title>{{ config('app.name', 'Laravel') }} - Accueil</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <style>
        body { font-family: Arial, sans-serif; margin: 0; padding: 20px; }
        header { background: #6b46c1; color: white; padding: 10px; text-align: center; margin-bottom: 20px; }
        h1 { margin-top: 0; }
        .actions { margin: 20px 0; text-align: center; }
        .actions a { display: inline-block; margin: 5px; padding: 10px 15px; text-decoration: none; }
        .primary-btn { background: #6b46c1; color: white; }
        .outline-btn { border: 1px solid #6b46c1; color: #6b46c1; }
        .features { margin: 20px 0; }
        .feature { margin-bottom: 20px; padding: 10px; border: 1px solid #ddd; }
        footer { margin-top: 20px; padding: 10px; background: #6b46c1; color: white; text-align: center; }
    </style>
</head>
<body>
    <header>
        <h2>{{ config('app.name', 'Laravel') }}</h2>
    </header>

    <div style="text-align: center;">
        <h1>Bienvenue sur notre plateforme de réservation</h1>
        <p>Connectez-vous avec vos créateurs de contenu préférés et réservez des sessions en direct.</p>

        <div class="actions">
            <a href="{{ route('login') }}" class="primary-btn">Se connecter</a>
            <a href="{{ route('register') }}" class="outline-btn">S'inscrire</a>
            <a href="{{ route('register.creator.form') }}" class="primary-btn">Devenir Créateur</a>
        </div>
    </div>

    <div class="features">
        <div class="feature">
            <h3>Réservez facilement</h3>
            <p>Trouvez des créneaux disponibles et réservez en quelques clics.</p>
        </div>
        <div class="feature">
            <h3>Rencontrez vos créateurs préférés</h3>
            <p>Interagissez en direct avec vos créateurs de contenu favoris.</p>
        </div>
        <div class="feature">
            <h3>Devenez créateur</h3>
            <p>Partagez votre passion et connectez-vous avec votre communauté.</p>
        </div>
    </div>

    <footer>
        <p>&copy; {{ date('Y') }} {{ config('app.name', 'Laravel') }}. Tous droits réservés.</p>
    </footer>
</body>
</html>