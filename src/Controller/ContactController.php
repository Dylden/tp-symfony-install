<?php

namespace App\Controller;

use App\Entity\Category;
use App\Repository\ArticleRepository;
use App\Repository\CategoryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;


//Ajout de la classe ContactController
class ContactController extends AbstractController
{
    //La route pour accéder à cette page est /contact
    #[Route('/contact', name: 'contact')]

    //Je fais une fonction contact
    //qui me sert à récupérer les données POST de mon formulaire
    public function contact(Request $request)
    {
        $name = $request->request->get('name');

        $message = $request->request->get('message');

        return $this->render('contact.html.twig', [
            'name' => $name,
            'message'=> $message]);

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
}