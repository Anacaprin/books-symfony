<?php
// src/Controller/Controller.php

namespace App\Controller;

use App\Service\BookService;
use App\Form\BookType;
use App\Entity\Book;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;

class Controller extends AbstractController
{
    private $bookService;

    public function __construct(BookService $bookService)
    {
        $this->bookService = $bookService;
    }

    #[Route('/', name: 'home')]
    public function home(): Response
    {
        return $this->render('base.html.twig');
    }

    #[Route('/list/book', name: 'book_list')]
    public function list(): Response
    {
        $book = $this->bookService->getAllBooks();

        return $this->render('list/index.html.twig', [
            'book' => $book,
        ]);
    }

    #[Route('/list/create', name: 'book_create')]
    public function create(Request $request): Response
    {
        $book = new Book();
        $form = $this->createForm(BookType::class, $book);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->bookService->createBook($book);

            return $this->redirectToRoute('book_list');
        }

        return $this->render('list/create.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/list/edit/{id}', name: 'book_edit')]
    public function edit(Request $request, Book $book): Response
    {
        $form = $this->createForm(BookType::class, $book);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->bookService->updateBook($book);

            return $this->redirectToRoute('book_list');
        }

        return $this->render('list/edit.html.twig', [
            'form' => $form->createView(),
            'book' => $book,
        ]);
    }

    #[Route('/list/delete/{id}', name: 'book_delete', methods: ['POST'])]
    public function delete(Book $book): RedirectResponse
    {
        $this->bookService->deleteBook($book);

        return $this->redirectToRoute('book_list');
    }
}

