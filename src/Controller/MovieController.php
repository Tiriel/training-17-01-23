<?php

namespace App\Controller;

use App\Repository\MovieRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/movie")
 */
class MovieController extends AbstractController
{
    /**
     * @Route("", name="app_movie_index")
     */
    public function index(MovieRepository $repository): Response
    {
        $movies = $repository->findAll();

        return $this->render('movie/index.html.twig', [
            'controller_name' => 'MovieController',
            'movies' => $movies,
        ]);
    }

    /**
     * @Route("/{id}", name="app_movie_details", requirements={"id": "\d+"})
     */
    public function details(int $id, MovieRepository $repository): Response
    {
        $movie = $repository->find($id);

        return $this->render('movie/details.html.twig', [
            'movie' => $movie,
        ]);
    }

    /**
     * @Route("/title/{title}", name="app_movie_title")
     */
    public function showByTitle(string $title, MovieRepository $repository): Response
    {
        $movie = $repository->findOneBy(['title' => $title]);

        return $this->render('movie/details.html.twig', [
            'movie' => $movie,
        ]);
    }
}
