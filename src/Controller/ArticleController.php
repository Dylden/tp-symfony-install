<?php

namespace App\Controller;

use App\Entity\Article;
use Doctrine\ORM\EntityManagerInterface;

use App\Repository\ArticleRepository;
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
    public function createArticle(EntityManagerInterface $entityManager): Response
    {
        //Je créé une instance de l'entité Article
        $article = new Article();

        //Méthode set permet de remplir les propriétés de mon nouvel article
        $article->setTitle('Article 5');
        $article->setContent('Ceci est un article nouveau');
        $article->setImage('https://gratisography.com/wp-content/uploads/2024/10/gratisography-cool-cat-800x525.jpg');
        $article->setCreatedAt(new \DateTime());


        //EntityManager permet de sauvegarder ou supprimer un article en BDD

        //persist pré-sauvegarde mes entités
        $entityManager->persist($article);

        //flush permet d'exécuter la requête SQL dans la BDD
        $entityManager->flush();

        return $this->render('articles_create.html.twig', [
            'article' => $article]);

    }
}