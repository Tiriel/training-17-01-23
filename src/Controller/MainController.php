<?php

namespace App\Controller;

use App\Dto\Contact;
use App\Form\ContactType;
use App\Repository\MovieRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
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
    public function contact(Request $request): Response
    {
        $dto = new Contact();
        $form = $this->createForm(ContactType::class, $dto);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            dump($dto);

            return $this->redirectToRoute('app_main_contact');
        }

        return $this->renderForm('main/contact.html.twig', [
            'form' => $form
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
