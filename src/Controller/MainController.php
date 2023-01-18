<?php

namespace App\Controller;

use App\Repository\MovieRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MainController extends AbstractController
{
    /**
     * @Route("", name="app_main_index")
     */
    public function index(MovieRepository $repository): Response
    {
        $lastMovies = $repository->findBy([], ['id' => 'DESC'], 6);

        return $this->render('main/index.html.twig', [
            'controller_name' => 'Index',
            'movies' => $lastMovies,
        ]);
    }

    /**
     * @Route("/contact", name="app_main_contact")
     */
    public function contact(): Response
    {
        return $this->render('main/contact.html.twig', [
            'controller_name' => 'Contact',
        ]);
    }

    public function decades(): Response
    {
        $decades = [
            '1970',
            '1980',
        ];

        return $this->render('_decades.html.twig', [
            'decades' => $decades,
        ])->setMaxAge('86400');
    }
}
