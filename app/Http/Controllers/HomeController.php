<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Affiche la page d'accueil de l'application.
     *
     * @return \Illuminate\Contracts\View\View|\Illuminate\Contracts\View\Factory
     */
    public function index()
    {
        // Ici, nous pourrions récupérer des données à afficher sur la page d'accueil,
        // comme une liste des créateurs populaires, des événements à venir, etc.
        return view('home.index'); // Nous créerons cette vue Blade plus tard
    }
}