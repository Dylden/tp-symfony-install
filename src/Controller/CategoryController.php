<?php

namespace App\Controller;

use App\Entity\Article;
use App\Entity\Category;
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
        if($request->isMethod('POST')) {
            //Je créé une instance de l'entité Category
            $category = new Category();

            //Je récupère les données de mon formulaire
            $title = $request->request->get('title');
            $color = $request->request->get('color');
            //Méthode set permet de remplir les propriétés de ma nouvelle catégorie
            $category->setTitle($title);
            $category->setColor($color);

            //EntityManager permet de sauvegarder ou supprimer une catégorie en BDD

            //persist pré-sauvegarde mes entités
            $entityManager->persist($category);

            //flush permet d'exécuter la requête SQL dans la BDD
            $entityManager->flush();

            return $this->render('category_create.html.twig', [
                'category' => $category]);
        }
        return $this->render('category_create.html.twig');

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
    public function updateCategory(int $id, CategoryRepository $categoryRepository, EntityManagerInterface $entityManager){

        
        //Je récupère ma catégorie par son id dans la BDD
        $category = $categoryRepository->find($id);

        //Je modifie les propriétés de l'instance de la catégorie avec la méthode set
        $category->setTitle('Catégorie 4 MAJ');
        $category->setColor('Couleur catégorie MAJ');

        //ré-enregistrement de la catégorie en BDD
        $entityManager->persist($category);

        //Exécution de la requête SQL
        $entityManager->flush();

        return $this->render('category_update.html.twig', [
            'category'=> $category]);


    }
}