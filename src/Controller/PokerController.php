<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class PokerController{

    #[Route('/poker' , 'poker')]
    public function poker(){

        //permet d'appeler la méthode createFromGlobals
        $request = Request::createFromGlobals();
        $age = $request->query->get('age');

        var_dump($age); die;

        return new Response('Bienvenue sur le site de Poker');
    }
}