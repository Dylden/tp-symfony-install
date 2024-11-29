<?php

namespace App\Controller;


use App\Entity\Article;
use App\Form\ArticleType;
use App\Repository\ArticleRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ArticleController extends AbstractController
{
    #[Route('/articles', 'articles_list')]
    public function articles(ArticleRepository $articleRepository): Response
    {

        //Récupérer articles en BDD
        $articles = $articleRepository->findAll();


        return $this->render('articles_list.html.twig', [
            'articles' => $articles
        ]);
    }

    //Quand je met le numéro de l'id dans l'url, ça m'affiche l'article correspondant.
    #[Route('/article/{id}', 'article_show', ['id' => '\d+'])]
    //ArticleRepository est générée automatiquement
        //lors de la génération de l'entité Article
        //Elle contient plusieurs méthodes pour faire des requêtes de type SELECT
        //sur la table article.
        //J'utilise l'autowire pour instancier cette classe
    public function showArticle(int $id, ArticleRepository $articleRepository): Response
    {
        //J'utilise la méthode findAll du repository
        //pour récupérer tous les articles
        $articleFound = $articleRepository->find($id);

        //Je créé une variable me permettant de stocker la valeur de l'id de l'article
        //null par défaut
        $articleFound = null;

        //Permet de récupérer la valeur stockée dans articleFound


        if (!$articleFound) {
            return $this->redirectToRoute('not_found');
        }

        return $this->render('article_show.html.twig', [
            'article' => $articleFound
        ]);
    }

    #[Route('/articles/search-results', 'article_search_results')]
    public function articleSearchResults(Request $request): Response
    {
        $search = $request->query->get('search');

        return $this->render('article_search_results.html.twig', [
            'search' => $search
        ]);
    }

    //Fonction pour créer un article
    #[Route('/article/create', 'create_article')]
    public function createArticle(Request $request, EntityManagerInterface $entityManager): Response
    {
        //Je créé une instance de l'entité Article
        $article = new Article();

        //La méthode form permet de créer un formulaire pour le nouvel article
        $form = $this->createForm(ArticleType::class, $article);

        //Je demande au form de Symfony de récupérer données de la requête
        //+ de remplir automatiquement l'entité avec
        //donc de récupérer données de chaque input
        //+ les stocker dans les propriétés de l'entité (setTitle() etc)
        $form->handleRequest($request);

        if($form->isSubmitted()) {
            //Je met automatiquement la date de création de l'article
            $article->setCreatedAt(new \DateTime());
            $entityManager->persist($article);
            $entityManager->flush();
        }

        //Je créer une view pour ce formulaire
        $formView = $form->createView();

        return $this->render('articles_create.html.twig', [
            'formView' => $formView]);

    }

    #[Route('/article/delete/{id}', 'delete_article', ['id' => '\d+'])]
    public function removeArticle(int $id, EntityManagerInterface $entityManager, ArticleRepository $articleRepository): Response
    {

        //Permet de trouver l'article par son id
        $article = $articleRepository->find($id);

        //Si l'id n'est pas valide : renvoie à la page d'erreur
        if (!$articleRepository->find($id)) {
            return $this->redirectToRoute('not_found');
        }

        //Prépare à la suppression de l'article
        $entityManager->remove($article);
        //exécute la requête SQL de suppression de l'article
        $entityManager->flush();

        return $this->render('articles_delete.html.twig', [
            'article' => $article]);
    }

    #[Route('article/update/{id}', 'update_article', ['id' => '\d+'])]
    public function updateArticle(int $id, Request $request, ArticleRepository $articleRepository, EntityManagerInterface $entityManager)
    {
        //Je récupère mon article par son id dans la BDD

        $article = $articleRepository->find($id);

        //La méthode form permet de créer un formulaire pour le nouvel article
        $form = $this->createForm(ArticleType::class, $article);

        //Je demande au form de Symfony de récupérer données de la requête
        //+ de remplir automatiquement l'entité avec
        //donc de récupérer données de chaque input
        //+ les stocker dans les propriétés de l'entité (setTitle() etc)
        $form->handleRequest($request);

        if($form->isSubmitted()) {
            //Je met automatiquement la date de création de l'article
            $article->setCreatedAt(new \DateTime());
            $entityManager->persist($article);
            $entityManager->flush();
        }

        //Je créer une view pour ce formulaire
        $formView = $form->createView();

        return $this->render('article_update.html.twig', [
            'formView' => $formView,
            'article'=> $article]);


    }
}