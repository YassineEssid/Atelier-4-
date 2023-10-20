<?php

namespace App\Controller;

use App\Entity\Book;
use App\Form\BookType;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\BookRepository;

class BookController extends AbstractController
{
    #[Route('/book', name: 'app_book')]
    public function index(): Response
    {
        return $this->render('book/index.html.twig', [
            'controller_name' => 'BookController',
        ]);
    }

    #[Route('/book/all', name:'app_book')]
    public function getAllBook(BookRepository $repo){ 
       $books=$repo->findAll();
       return $this->render('book/index.html.twig',['books'=>$books]);
    }

    #[Route('/addBook', name: 'app_book_add')]
    public function addBook(Request $req,ManagerRegistry $manager) {
        $book = new Book();
        $form = $this->createForm(BookType::class,$book);
        $form->handleRequest($req);
        //$book->setRef($form->getData()->getRef());
        if($form->isSubmitted()){
            $book->setPublished(true);
        $manager->getManager()->persist($book);
        $manager->getManager()->flush();
        return $this->redirectToRoute('app_book');
        }
        return $this->render('author/add.html.twig',[
            'f'=>$form->createView()
        ]);
    }

    #[Route('book/update/{ref}',name:'app_book_update')]
    public function updateBook(ManagerRegistry $manager,$ref,BookRepository $rep,Request $req){
        $book = $rep->find($ref);
        $form = $this->createForm(BookType::class,$book);
        $form->handleRequest($req);
        if($form->isSubmitted()){
            $manager->getManager()->flush();
            return $this->redirectToRoute('app_book_all');
        }
        return $this->render('book/add.html.twig',['f'=>$form->createView()]);
    }

    #[Route('book/delete/{ref}',name:'app_book_delete')]
    public function deleteBook(ManagerRegistry $manager,$ref,BookRepository $rep){
        $book = $rep->find($ref);
        $manager->getManager()->remove($book);
        $manager->getManager()->flush();
        return $this->redirectToRoute('app_book_all');
    }
}