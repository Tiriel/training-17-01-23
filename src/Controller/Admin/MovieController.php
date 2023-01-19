<?php

namespace App\Controller\Admin;

use App\Entity\Movie;
use App\Form\MovieType;
use App\Repository\MovieRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/movie")
 */
class MovieController extends AbstractController
{
    /**
     * @Route("/", name="app_admin_movie_index", methods={"GET"})
     */
    public function index(MovieRepository $movieRepository): Response
    {
        return $this->render('admin/movie/index.html.twig', [
            'movies' => $movieRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="app_admin_movie_new", methods={"GET", "POST"})
     */
    public function new(Request $request, MovieRepository $movieRepository): Response
    {
        $movie = new Movie();
        $form = $this->createForm(MovieType::class, $movie);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $movieRepository->add($movie, true);

            return $this->redirectToRoute('app_admin_movie_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('admin/movie/new.html.twig', [
            'movie' => $movie,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="app_admin_movie_show", methods={"GET"})
     */
    public function show(Movie $movie): Response
    {
        return $this->render('admin/movie/show.html.twig', [
            'movie' => $movie,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="app_admin_movie_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Movie $movie, MovieRepository $movieRepository): Response
    {
        $form = $this->createForm(MovieType::class, $movie);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $movieRepository->add($movie, true);

            return $this->redirectToRoute('app_admin_movie_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('admin/movie/edit.html.twig', [
            'movie' => $movie,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="app_admin_movie_delete", methods={"POST"})
     */
    public function delete(Request $request, Movie $movie, MovieRepository $movieRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$movie->getId(), $request->request->get('_token'))) {
            $movieRepository->remove($movie, true);
        }

        return $this->redirectToRoute('app_admin_movie_index', [], Response::HTTP_SEE_OTHER);
    }
}

/**
1. Create a command "app:movie:import" that will import movies from the API
2. Add an argument to this command to search for a specific movie
3. Inject the OmdbGateway in the command
4. Use the OmdbGateway to fetch the movie by the title
5. Persist the new Movie entity
 * Bonus :
6. If the argument is missing, ask the user to enter a title
 */

/**
1. Create a command "app:movie:poster-retrieve" that will retrieve the poster of a movie
2. Inject the OmdbGateway and MovieRepository in the command
3. For each movie that is missing a poster, retrieve the poster from the API
4. Persist the movie
 * Bonus :
5. Show the progress bar
 */