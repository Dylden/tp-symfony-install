<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class PokerController extends AbstractController {

    #[Route('/poker' , 'poker')]
    public function poker(){

        //permet d'appeler la méthode createFromGlobals
        $request = Request::createFromGlobals();
        $age = $request->query->get('age');

        if($age>=18){
            return $this->render('poker-major.html.twig');
        } else {
            return $this->render('poker-error.html.twig');
        }

    }
}