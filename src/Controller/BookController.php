<?php

namespace App\Controller;

use App\Entity\Book;
use App\Form\BookType;
use App\Repository\BookRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/book")
 */
class BookController extends AbstractController
{
    /**
     * @Route("", name="app_book_index", methods={"GET"})
     * @Route("/list", name="app_book_list", methods={"GET"})
     */
    public function index(Request $request): Response
    {
        $controllerName = 'BookController';
        if ('app_book_list' === $request->attributes->get('_route')) {
            $controllerName .= '::list';
        }

        return $this->render('book/index.html.twig', [
            'controller_name' => $controllerName,
        ]);
    }

    /**
     * @Route("/{id}", name="app_book_details", requirements={"id": "\d+"}, defaults={"id": 1}, methods={"GET", "POST"})
     */
    public function details(int $id): Response
    {
        return $this->render('book/index.html.twig', [
            'controller_name' => 'BookController - id :'.$id,
        ]);
    }

    /**
     * @Route("/new", name="app_book_new", methods={"GET", "POST"})
     */
    public function new(Request $request, BookRepository $repository): Response
    {
        $book = new Book();
        $form = $this->createForm(BookType::class, $book);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $repository->add($book, true);

            dump($book);
            return $this->redirectToRoute('app_book_list');
        }

        return $this->renderForm('book/new.html.twig', [
            'form' => $form,
        ]);
    }
}
