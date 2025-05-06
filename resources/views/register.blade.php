<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Inscription</title>
</head>
<body>
    <h1>Inscription</h1>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('register') }}">
        @csrf

        <div>
            <label for="name">Nom</label>
            <input type="text" id="name" name="name" value="{{ old('name') }}" required autofocus>
        </div>

        <div>
            <label for="email">Email</label>
            <input type="email" id="email" name="email" value="{{ old('email') }}" required>
        </div>

        <div>
            <label for="password">Mot de passe</label>
            <input type="password" id="password" name="password" required>
        </div>

        <div>
            <label for="password_confirmation">Confirmer le mot de passe</label>
            <input type="password" id="password_confirmation" name="password_confirmation" required>
        </div>

        <div>
            <button type="submit">S'inscrire</button>
        </div>
    </form>

    <p>Déjà un compte? <a href="{{ route('login') }}">Se connecter</a></p>
</body>
</html>