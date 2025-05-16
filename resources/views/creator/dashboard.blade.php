<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <title>{{ config('app.name', 'Laravel') }} - Tableau de bord Créateur</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Figtree:ital,wght@0,300..900;1,300..900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('auth_theme/css/tailwind/tailwind.min.css') }}">
    <link rel="icon" type="image/png" sizes="32x32" href="shuffle-for-tailwind.png">
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.13.3/dist/cdn.min.js" defer></script>
</head>
<body class="antialiased bg-body text-body font-body">
    <div class="">
        <section class="p-4 bg-purple-500">
            <div class="px-4 sm:px-8 rounded-3xl bg-black md:py-16 py-8">
                <div class="container mx-auto pb-4 justify-between items-center md:px-4 md:flex">
                    <div class="flex">
                        <svg id="svg_some_id" width="8" height="8" viewBox="0 0 8 8" fill="none" xmlns="http://www.w3.org/2000/svg"></svg>
                        <span class="inline-block text-sm font-medium text-white">{{ __('Tableau de bord') }}</span>
                    </div>
                    <div class="flex space-x-3">
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="px-4 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700 transition-colors">
                                {{ __('Déconnexion') }}
                            </button>
                        </form>
                    </div>
                </div>
                <div class="border-t border-white border-opacity-25 md:pt-16 pt-4">
                    <div class="max-w-md mx-auto md:max-w-none">
                        <div class="flex flex-wrap items-center -mx-4">
                            <div class="w-full md:w-1/2 px-4 mb-12 md:mb-0">
                                <div class="max-w-lg mx-auto md:mr-0 overflow-hidden">
                                    <div>
                                        <h4 class="font-medium text-white mb-10 lg:text-5xl text-3xl">{{ __('Bienvenue sur votre tableau de bord !') }}</h4>
                                        <p class="block mb-12 lg:mb-32 text-lg text-gray-500">{{ __('Consultez vos informations et gérez votre activité.') }}</p>

                                        @if (session('success'))
                                            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-6" role="alert">
                                                <span class="block sm:inline">{{ session('success') }}</span>
                                            </div>
                                        @endif

                                        <h5 class="font-medium text-white mb-4">{{ __('Informations importantes') }}</h5>
                                        <ul class="list-disc pl-5 text-gray-300 mb-6">
                                            <li>{{ __('Ici pourrait s\'afficher le nombre de réservations récentes.') }}</li>
                                            <li>{{ __('Ici pourrait s\'afficher le solde de votre compte.') }}</li>
                                            <li>{{ __('Ici pourraient s\'afficher des notifications importantes.') }}</li>
                                        </ul>

                                        <h5 class="font-medium text-white mb-4">{{ __('Actions rapides') }}</h5>
                                        <ul class="list-disc pl-5 text-gray-300">
                                            <li><a href="{{ route('creator.profile.edit') }}" class="text-purple-400 hover:text-purple-300">{{ __('Modifier votre profil') }}</a></li>
                                            <li><a href="{{ route('schedule.index') }}" class="text-purple-400 hover:text-purple-300">{{ __('Gérer vos horaires') }}</a></li>
                                            <li><a href="{{ route('availability.index') }}" class="text-purple-400 hover:text-purple-300">{{ __('Gérer vos disponibilités') }}</a></li>
                                            <li><a href="#" class="text-purple-400 hover:text-purple-300">{{ __('Consulter vos réservations') }}</a></li>
                                            <li><a href="{{ route('event_type.create') }}" class="text-purple-400 hover:text-purple-300">{{ __('Créer un type d\'événement') }}</a></li>

                                        </ul>
                                    </div>
                                </div>
                            </div>
                            <div class="w-full md:w-1/2 px-4">
                                <div class="max-w-lg mx-auto md:mr-0 overflow-hidden">
                                    <div>
                                        <img class="block w-full rounded-lg shadow-xl" src="{{ asset('auth_theme/images/pexels.jpg') }}" alt="Tableau de bord">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
</body>
</html>