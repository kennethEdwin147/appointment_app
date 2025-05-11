<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <title>{{ config('app.name', 'Laravel') }} - Devenir Créateur</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=DM+Sans:opsz,wght@9..40,400;9..40,500;9..40,600;9..40,700&display=swap">
    <link rel="stylesheet" href="{{ asset('register_theme/css/bootstrap.min.css') }}">
    <link rel="icon" type="image/png" sizes="32x32" href="">
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
                                    <p class="fw-medium text-muted mb-6">Démarre ton aventure de Créateur !</p>
                                    <img class="d-block mx-auto img-fluid" src="{{ asset('register_theme/images/pexels-107014568-9725465.jpg') }}" alt="">
                                </div>
                            </div>
                        </div>
                        <div class="col-12 col-lg-6 pt-16 px-8 px-sm-16 pb-20">
                            <div>
                                <span class="d-block fs-6 fw-semibold mb-4">Créer un compte Créateur</span>
                                <h3 class="fs-1 mb-6">Lance ton aventure en tant que Créateur !</h3>
                                <p class="lead fs-8 mb-16">
                                    Votre plateforme pour des événements réussis.
                                    <br>
                                </p>
                                <form method="POST" action="{{ route('register.creator') }}">
                                    @csrf
                                    <div class="mb-6">
                                        <label for="firstname" class="form-label fw-medium text-muted">{{ __('Prénom') }}</label>
                                        <input id="firstname" type="text" class="form-control @error('first_name') is-invalid @enderror" name="first_name" value="{{ old('first_name') }}" placeholder="ex. Daniel" required autocomplete="first_name" autofocus>
                                        @error('first_name')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                    <div class="mb-6">
                                        <label for="lastname" class="form-label fw-medium text-muted">{{ __('Nom') }}</label>
                                        <input id="lastname" type="text" class="form-control @error('last_name') is-invalid @enderror" name="last_name" value="{{ old('last_name') }}" placeholder="ex. Duncan" required autocomplete="last_name">
                                        @error('last_name')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                    <div class="mb-6">
                                        <label for="email" class="form-label fw-medium text-muted">{{ __('Adresse e-mail') }}</label>
                                        <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" placeholder="ex. d.duncan@email.com" required autocomplete="email">
                                        @error('email')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                    <div class="mb-6">
                                        <label for="password" class="form-label fw-medium text-muted">{{ __('Mot de passe') }}</label>
                                        <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" placeholder="••••••••" required autocomplete="new-password">
                                        @error('password')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                    <div class="mb-6">
                                        <label for="confirmpassword" class="form-label fw-medium text-muted">{{ __('Confirmer le mot de passe') }}</label>
                                        <input id="confirmpassword" type="password" class="form-control" name="password_confirmation" placeholder="••••••••" required autocomplete="new-password">
                                    </div>
                                    <button class="btn btn-lg py-4 mb-6 btn-primary w-100" type="submit">{{ __('C\'est parti !') }}</button>
                                    <p class="d-flex align-items-center text-muted fs-9 fw-medium mb-0">
                                        <span class="me-1">Tu as dejà un compte ?</span>
                                        <a class="btn text-muted border-0 p-0" href="{{ route("login") }}">{{ __('Connectes toi') }}</a>
                                    </p>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
    <script src="{{ asset('js/bootstrap/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('js/main.js') }}"></script>
</body>
</html>