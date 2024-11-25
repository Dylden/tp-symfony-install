<?php

namespace App\Controller;

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
        return $this->render('home.html.twig');
    }
}