<?php

namespace App\Http\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

//Classe HomeController
class HomeController
{
//Création de la route pour méthode home
    #[Route('/', 'home')]

//Création méthode home
    public function home()
    {
        return new Response('<h1>Page accueil trop cool !</h1>');
    }
}