<?php

namespace App\Controller;

use App\Repository\CategoryRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class CategoryController extends AbstractController
{


    #[Route('/categories', 'categories')]

    //Fonction qui permet de trouver tous les articles de la BDD
public function category(CategoryRepository $categoryRepository): Response{
        $categories = $categoryRepository->findAll();


        return $this->render('categories.html.twig', [
            'categories' => $categories]);
    }

#[Route('/category/{id}', 'category_show', ['id' => '\d+'])]

//Fonction qui permet de trouver l'article que l'on met en id dans l'url.
public function showCategory(CategoryRepository $categoryRepository, int $id): Response{

        //Va cercher l'id de l'article indiqué dans la BDD
        $categoryFound = $categoryRepository->find($id);

        //Si l'id ne correspond à aucun article, me redirige dans ma page d'erreur
    if (!$categoryFound) {
        return $this->redirectToRoute('not_found');
    }
    return $this->render('category.html.twig', [
        'category' => $categoryFound
    ]);
}
}