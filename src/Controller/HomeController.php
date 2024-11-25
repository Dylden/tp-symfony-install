<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

//Classe HomeController
class HomeController extends AbstractController
{
//Création de la route pour méthode home
    #[Route('/home', 'home')]
//Création méthode home
    public function home()
    {
        return $this->render('home.html.twig');
    }
}