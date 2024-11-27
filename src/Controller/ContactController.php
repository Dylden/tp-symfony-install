<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

class ContactController extends AbstractController
{
    #[Route('/contact', name: 'contact')]
    public function contact(Request $request)
    {
        $name = $request->request->get('name');

        $message = $request->request->get('message');

        return $this->render('contact.html.twig', [
            'name' => $name,
            'message'=> $message]);

    }
}