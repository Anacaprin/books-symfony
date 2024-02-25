<?php
// src/Service/BookService.php

namespace App\Service;

use App\Entity\Book;
use Doctrine\ORM\EntityManagerInterface;

class BookService
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function getAllBooks()
    {
        return $this->entityManager->getRepository(Book::class)->findAll();
    }

    public function createBook(Book $book)
    {
        $this->entityManager->persist($book);
        $this->entityManager->flush();
    }

    public function updateBook(Book $book)
    {
        $this->entityManager->flush();
    }

    public function deleteBook(Book $book)
    {
        $this->entityManager->remove($book);
        $this->entityManager->flush();
    }
    public function searchBooks($searchTerm)
    {
        $queryBuilder = $this->entityManager->getRepository(Book::class)->createQueryBuilder('b');
        
        $query = $queryBuilder
            ->where('b.title LIKE :searchTerm OR b.author LIKE :searchTerm')
            ->setParameter('searchTerm', '%'.$searchTerm.'%')
            ->getQuery();
        
        $result = $query->getResult();
        
        return $result;
    }
}