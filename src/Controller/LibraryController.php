<?php

namespace App\Controller;

use App\Entity\Tag;
use App\Entity\Book;
use App\Entity\Media;
use App\Entity\Author;
use App\Form\BookType;
use App\Form\AuthorType;
use App\Repository\BookRepository;
use App\Repository\AuthorRepository;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class LibraryController extends AbstractController
{
    
    /**
    * @Route("/", name="home")
    * @Route("/books", name="books_show")
    */
    public function booksShow(BookRepository $bookRepository){

        $books = $bookRepository->findAll();
        $form = $this->createForm(BookType::class, null);
        return $this->render('library/books.html.twig', ['books'=>$books, 'formBook'=>$form->createView(), 'editMode'=>false]);
    }

    /**
    * @Route("/tag/{id<\d+>}", name="books_show_by_tag")
    */
    public function booksShowByTag(BookRepository $bookRepository, Tag $tag){
        $books = $bookRepository->findByTag($tag)->getResult();
        $form = $this->createForm(BookType::class, null);
        return $this->render('library/books.html.twig', ['books'=>$books, 'formBook'=>$form->createView(), 'editMode'=>false]);
    }

    /**
    * @Route("/book/{id<\d+>}", name="book_show")
    */
    public function bookShow(Book $book){
        return $this->render('library/book.html.twig', ['book'=>$book]);
    }

    /**
    * @Route("/book/new", name="book_new")
    * @Route("/book/{id}/edit", name="book_edit")
    */
    public function book(Book $book=null, Request $request, ObjectManager $manager){
        if($book){
            $coverPicture = $book->getCoverPicture();
            if (!($coverPicture->getName() instanceof UploadedFile)) {
                $fileName = $this->getParameter('covers_directory')."/".$coverPicture->getName();
                $fileSystem = new Filesystem();
                if($fileSystem->exists($fileName)){
                    $file  = new UploadedFile($fileName, $coverPicture->getName());
                }
                else{
                    $file  = null;
                }
                $coverPicture->setName($file);
                $book->setCoverPicture($coverPicture);
            }
        }
        else{
            $book = new Book();
        }

       

        $form = $this->createForm(BookType::class, $book);
        $form->handleRequest($request);
        dump($form);
        if($form->isSubmitted() && $form->isValid()){
            if(!$book->getId()){
                $book->setCreatedAt(new \DateTime());
            }
            $coverPicture = $book->getCoverPicture();
            if ($coverPicture->getName() instanceof UploadedFile) {
                if($coverPicture->getName()!=null){
                    $file = $coverPicture->getName();
                    $fileName = md5(uniqid()).'.'.$file->guessExtension();
                    $file->move($this->getParameter('covers_directory'), $fileName);
                    $coverPicture->setName($fileName);
                }
            }

            $manager->persist($book);
            $manager->flush();

            return $this->redirectToRoute('books_show', ['id'=>$book->getId()]);
        }
     
        return $this->render('library/book_form.html.twig', ['formBook'=>$form->createView(), 'editMode'=>$book->getId()!==null]);
    }

    /**
    * @Route("/book/{id}/delete", name="book_delete")
    */
    public function bookDelete(Book $book, Request $request, ObjectManager $manager){
        $manager->remove($book);
        $manager->flush();
        return $this->redirectToRoute('books_show');
    }

    /**
    * @Route("/authors", name="authors_show")
    */
    public function authorsShow(AuthorRepository $authorRepository){
        $authors = $authorRepository->findAll();
        return $this->render('library/authors.html.twig', ['authors'=>$authors]);
    }

    /**
    * @Route("/author/new", name="author_new")
    * @Route("/author/{id}/edit", name="author_edit")
    */
    public function author(Author $author=null, Request $request, ObjectManager $manager){
        if(!$author){
            $author = new Author();
        }

        $form = $this->createForm(AuthorType::class, $author);

        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            if(!$author->getId()){
                $author->setCreatedAt(new \DateTime());
            }

            $manager->persist($author);
            $manager->flush();

            return $this->redirectToRoute('books_show');
        }

        return $this->render('library/author.html.twig', ['formAuthor'=>$form->createView(), 'editMode'=>$author->getId()!==null]);
    }

    /**
    * @Route("/author/{id}/delete", name="author_delete")
    */
    public function authorDelete(Author $author, Request $request, ObjectManager $manager){
        $authorBooks = $author->getBooks();
        foreach($authorBooks as $book){
            $manager->remove($book);
        }
        $manager->remove($author);
        $manager->flush();
        return $this->redirectToRoute('books_show');
    }
}
