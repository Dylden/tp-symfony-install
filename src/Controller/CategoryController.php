<?php

namespace App\Controller;

use App\Entity\Article;
use App\Entity\Category;
use App\Repository\CategoryRepository;
use Doctrine\ORM\EntityManagerInterface;
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

        //Va chercher l'article par rapport à l'ID indiqué dans la BDD
        $categoryFound = $categoryRepository->find($id);

        //Si l'id ne correspond à aucun article, me redirige dans ma page d'erreur
    if (!$categoryFound) {
        return $this->redirectToRoute('not_found');
    }
    return $this->render('category.html.twig', [
        'category' => $categoryFound
    ]);
}

    #[Route('/category/create', 'create_category')]
    public function createCategory(EntityManagerInterface $entityManager): Response
    {
        //Je créé une instance de l'entité Category
        $category = new Category();

        //Méthode set permet de remplir les propriétés de ma nouvelle catégorie
        $category->setTitle('Numérique');
        $category->setColor('orange');

        //EntityManager permet de sauvegarder ou supprimer une catégorie en BDD

        //persist pré-sauvegarde mes entités
        $entityManager->persist($category);

        //flush permet d'exécuter la requête SQL dans la BDD
        $entityManager->flush();

        return $this->render('category_create.html.twig', [
            'category' => $category]);

    }
}