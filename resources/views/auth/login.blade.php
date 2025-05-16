<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <title>{{ config('app.name', 'Laravel') }} - Connexion</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=DM+Sans:opsz,wght@9..40,400;9..40,500;9..40,600;9..40,700&display=swap">
    <link rel="stylesheet" href="{{ asset('register_theme/css/bootstrap.min.css') }}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('favicon.png') }}">
</head>
<body>
    <div class="">
        <section class="py-12 py-lg-16 bg-light bg-opacity-25 overflow-hidden">
            <div class="container mw-2xl mw-lg-7xl">
                <div class="bg-white rounded-5 overflow-hidden">
                    <div class="row">
                        <div class="col-12 col-lg-6 p-0 h-75">
                            <div class="d-flex justify-content-center h-100 p-12 rounded-4 bg-dark-light">
                                <div class="mw-sm py-12 px-10 bg-white rounded-4">
                                    <img class="d-block mb-3 img-fluid" src="{{ asset('register_theme/images/mirga-dark-logo.svg') }}" alt="">
                                    <p class="fw-medium text-muted mb-6">Bienvenue sur notre plateforme !</p>
                                    <img class="d-block mx-auto img-fluid" src="{{ asset('register_theme/images/pexels-107014568-9725465.jpg') }}" alt="">
                                </div>
                            </div>
                        </div>
                        <div class="col-12 col-lg-6 pt-16 px-8 px-sm-16 pb-20">
                            <div>
                                <span class="d-block fs-6 fw-semibold mb-4">Connexion</span>
                                <h3 class="fs-1 mb-6">Heureux de vous revoir !</h3>
                                <p class="lead fs-8 mb-16">
                                    Connectez-vous pour accéder à votre compte.
                                    <br>
                                </p>
                                
                                @if (session('success'))
                                    <div class="alert alert-success mb-4">
                                        {{ session('success') }}
                                    </div>
                                @endif
                                
                                @if (session('error'))
                                    <div class="alert alert-danger mb-4">
                                        {{ session('error') }}
                                    </div>
                                @endif
                                
                                <form method="POST" action="{{ route('login') }}">
                                    @csrf
                                    <div class="mb-6">
                                        <label for="email" class="form-label fw-medium text-muted">{{ __('Adresse e-mail') }}</label>
                                        <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" placeholder="ex. d.duncan@email.com" required autocomplete="email" autofocus>
                                        @error('email')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                    <div class="mb-6">
                                        <div class="d-flex justify-content-between">
                                            <label for="password" class="form-label fw-medium text-muted">{{ __('Mot de passe') }}</label>
                                            @if (Route::has('password.request'))
                                                <a class="form-label fw-medium text-muted" href="{{ route('password.request') }}">
                                                    {{ __('Mot de passe oublié ?') }}
                                                </a>
                                            @endif
                                        </div>
                                        <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" placeholder="••••••••" required autocomplete="current-password">
                                        @error('password')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                    <div class="mb-6 form-check">
                                        <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                                        <label class="form-check-label" for="remember">
                                            {{ __('Se souvenir de moi') }}
                                        </label>
                                    </div>
                                    <button class="btn btn-lg py-4 mb-6 btn-primary w-100" type="submit">{{ __('Se connecter') }}</button>
                                    <p class="d-flex align-items-center text-muted fs-9 fw-medium mb-0">
                                        <span class="me-1">Vous n'avez pas de compte ?</span>
                                        <a class="btn text-muted border-0 p-0" href="{{ route('register') }}">{{ __('Inscrivez-vous') }}</a>
                                    </p>
                                    <div class="mt-4 text-center">
                                        <a href="{{ route('register.creator.form') }}" class="btn btn-outline-secondary">
                                            {{ __('Devenir créateur') }}
                                        </a>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
    <script src="{{ asset('register_theme/js/bootstrap/bootstrap.bundle.min.js') }}"></script>
</body>
</html>
