<?php

namespace App\Controller;

use App\Entity\Movie;
use App\Omdb\OmdbGateway;
use App\Repository\MovieRepository;
use DateTime;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/movie")
 */
class MovieController extends AbstractController
{
    private OmdbGateway $omdbGateway;

    public function __construct(
        OmdbGateway $omdbGateway,
    ){
        $this->omdbGateway = $omdbGateway;
    }

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
    public function details(Movie $movie): Response
    {
        $movieFromApi = $this->omdbGateway->fetchMovie($movie->getTitle());

        return $this->render('movie/details.html.twig', [
            'movie' => $movieFromApi,
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
