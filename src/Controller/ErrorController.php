<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;

class ErrorController extends AbstractController
{
    #[Route('/not-found', 'not_found')]
    public function notFound(): Response
    {
        $view = $this->renderView("404.html.twig");

        return new Response($view, 404);
    }
}