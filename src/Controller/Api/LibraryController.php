<?php

namespace App\Controller\Api;

use App\Entity\Tag;
use App\Entity\Book;
use App\Entity\Author;
use App\Repository\BookRepository;
use App\Repository\AuthorRepository;
use Doctrine\Common\Collections\Criteria;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class LibraryController extends AbstractController
{

    /**
    * @Route("/api", name="api_endpoint") 
    * @Route("/api/books", name="api_books")
    */
    public function books(SerializerInterface $serializer, BookRepository $bookRepository){
        $books = $bookRepository->findAll();
        $json = $serializer->serialize(
            $books,
            'json', 
            array('groups' => array('api'))
            ); 
            header('Content-Type: application/json');

        return JsonResponse::fromJsonString($json, 200);

    }

    // TODO: return json custom errors
    /**
    * @Route("/api/books_show", name="api_books_show")
    */
    public function booksShow(Request $request, SerializerInterface $serializer, BookRepository $bookRepository){

        $before =  $request->query->get('before'); 
        $after =  $request->query->get('after'); 
        $nationality =  $request->query->get('nationality'); 
        
        $books = $bookRepository->findBooks($before, $after, $nationality);
  
        $json = $serializer->serialize(
            $books,
            'json', 
            array('groups' => array('api'))
            ); 
            header('Content-Type: application/json');
        return JsonResponse::fromJsonString($json, 200);
    }
}