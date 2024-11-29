<?php

namespace App\Controller;

use App\Entity\Article;
use App\Entity\Category;
use App\Form\ArticleType;
use App\Form\CategoryType;
use App\Repository\ArticleRepository;
use App\Repository\CategoryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
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
    public function createCategory(Request $request, EntityManagerInterface $entityManager): Response
    {
            //Je créé une instance de l'entité Category
            $category = new Category();

            //La méthode form permet de créer un formulaire pour la nouvelle catégorie
            $form = $this->createForm(CategoryType::class, $category);

            //Je demande au form de Symfony de récupérer données de la requête
            //+ de remplir automatiquement l'entité avec
            //donc de récupérer données de chaque input
            //+ les stocker dans les propriétés de l'entité (setTitle() etc)
            $form->handleRequest($request);

            if ($form->isSubmitted()) {
                //Je met automatiquement la date de création de la catégorie
                $entityManager->persist($category);
                $entityManager->flush();
            }

            //Je créer une view pour ce formulaire
            $formView = $form->createView();

            return $this->render('category_create.html.twig', [
                'formView' => $formView]);
    }

    #[Route('/category/delete/{id}', 'delete_category', ['id' => '\d+'])]
    public function removeCategory(int $id, EntityManagerInterface $entityManager, CategoryRepository $categoryRepository): Response{

        //Permet de trouver la catégorie par son id
        $category = $categoryRepository->find($id);

        //Si l'id n'est pas valide : renvoie à la page d'erreur
        if(!$categoryRepository->find($id)){
            return $this->redirectToRoute('not_found');
        }

        //Prépare à la suppression de la catégorie
        $entityManager->remove($category);
        //exécute la requête SQL de suppression de la catégorie
        $entityManager->flush();

        return $this->render('category_delete.html.twig', [
            'category' => $category]);
    }

    #[Route('category/update/{id}', 'update_category', ['id' => '\d+'])]
    public function updateCategory(int $id, Request $request, CategoryRepository $categoryRepository, EntityManagerInterface $entityManager){

        
        //Je récupère ma catégorie par son id dans la BDD
        $category = $categoryRepository->find($id);
        //La méthode form permet de créer un formulaire pour la nouvelle catégorie
        $form = $this->createForm(CategoryType::class, $category);

        //Je demande au form de Symfony de récupérer données de la requête
        //+ de remplir automatiquement l'entité avec
        //donc de récupérer données de chaque input
        //+ les stocker dans les propriétés de l'entité (setTitle() etc)
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            //Je met automatiquement la date de création de la catégorie
            $entityManager->persist($category);
            $entityManager->flush();
        }

        //Je créer une view pour ce formulaire
        $formView = $form->createView();

        return $this->render('category_create.html.twig', [
            'formView' => $formView]);
    }
}