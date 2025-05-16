<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <title>{{ config('app.name', 'Laravel') }} - Accueil</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <style>
        body {
            font-family: 'Arial', sans-serif;
            line-height: 1.6;
            margin: 0;
            padding: 0;
            color: #333;
        }
        .container {
            width: 80%;
            margin: 0 auto;
            padding: 20px;
        }
        header {
            background-color: #6b46c1;
            color: white;
            padding: 1rem;
            text-align: center;
        }
        .hero {
            background-color: #f3f4f6;
            padding: 3rem 1rem;
            text-align: center;
            margin-bottom: 2rem;
        }
        .hero h1 {
            font-size: 2.5rem;
            margin-bottom: 1rem;
        }
        .hero p {
            font-size: 1.2rem;
            margin-bottom: 2rem;
            color: #4b5563;
        }
        .btn {
            display: inline-block;
            padding: 0.75rem 1.5rem;
            text-decoration: none;
            border-radius: 0.375rem;
            font-weight: bold;
            margin: 0 0.5rem;
        }
        .btn-primary {
            background-color: #6b46c1;
            color: white;
        }
        .btn-outline {
            border: 2px solid #6b46c1;
            color: #6b46c1;
        }
        .features {
            display: flex;
            flex-wrap: wrap;
            justify-content: space-between;
            margin-bottom: 2rem;
        }
        .feature {
            flex: 1;
            min-width: 300px;
            padding: 1.5rem;
            margin: 1rem;
            background-color: white;
            border-radius: 0.5rem;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        .feature h3 {
            color: #6b46c1;
        }
        footer {
            background-color: #6b46c1;
            color: white;
            text-align: center;
            padding: 1rem;
            margin-top: 2rem;
        }
    </style>
</head>
<body>
    <header>
        <div class="container">
            <h2>{{ config('app.name', 'Laravel') }}</h2>
        </div>
    </header>

    <section class="hero">
        <div class="container">
            <h1>Bienvenue sur notre plateforme de réservation</h1>
            <p>Connectez-vous avec vos créateurs de contenu préférés et réservez des sessions en direct.</p>
            <div>
                <a href="{{ route('login') }}" class="btn btn-primary">Se connecter</a>
                <a href="{{ route('register') }}" class="btn btn-outline">S'inscrire</a>
                <a href="{{ route('register.creator.form') }}" class="btn btn-primary">Devenir Créateur</a>
            </div>
        </div>
    </section>

    <section class="container">
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
    </section>

    <footer>
        <p>&copy; {{ date('Y') }} {{ config('app.name', 'Laravel') }}. Tous droits réservés.</p>
    </footer>
</body>
</html>