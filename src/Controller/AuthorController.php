<?php

namespace App\Controller;

use App\Entity\Author;
use App\Form\AuthorType;
use App\Repository\AuthorRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AuthorController extends AbstractController
{
    
    public function index(): Response
    {
        return $this->render('author/index.html.twig', [
            'controller_name' => 'AuthorController',
        ]);
    }

    
    public function showAuthor ( $name ){
        return $this->render('author/show.html.twig',[
            'n'=>$name
        ]);
    }

    public function list() 
    {
        $authors = array(
            array('id' => 1, 'picture' => '/images/Victor_Hugo.jpg','username' => ' Victor
            Hugo', 'email' => 'victor.hugo@gmail.com ', 'nb_authors' => 100),
            array('id' => 2, 'picture' => '/images/william_shakespeare.jpg','username' => '
            William Shakespeare', 'email' => ' william.shakespeare@gmail.com', 'nb_authors' =>
            200 ),
            array('id' => 3, 'picture' => '/images/Taha_Hussein.jpg','username' => ' Taha
            Hussein', 'email' => 'taha.hussein@gmail.com', 'nb_authors' => 300),);
            
            return $this->render('author/list.html.twig', ['authors' => $authors]);
    }
    
    public function authorDetails ($id){
        $authors = array(
            array('id' => 1, 'picture' => '/images/Victor_Hugo.jpg','username' => ' Victor
            Hugo', 'email' => 'victor.hugo@gmail.com ', 'nb_authors' => 100),
            array('id' => 2, 'picture' => '/images/william_shakespeare.jpg','username' => '
            William Shakespeare', 'email' => ' william.shakespeare@gmail.com', 'nb_authors' =>
            200 ),
            array('id' => 3, 'picture' => '/images/Taha_Hussein.jpg','username' => ' Taha
            Hussein', 'email' => 'taha.hussein@gmail.com', 'nb_authors' => 300),);

        $author=NULL;
        foreach($authors as $a)
        {
            if($a['id'] == $id)
            {
                $author =$a;
                
                break;
            }
        }

        return $this->render('author/showAuthor.html.twig',['author' => $author]);
    }
    
    #[Route('/author/get/all',name:'app_get_all')]
    public function getAll(AuthorRepository $repo){
        $authors = $repo ->findAll();
        return $this->render('author/list.html.twig',['authors'=>$authors]);
    }

    #[Route('/author/add',name:'app_add_author')]
    public function add(MangerRegistry $manager){
        $author = new Author();
        $author->setUsername("yassine1");
        $author->setEmail("yassine.essid@esprit.tn");
        $manager->getManager()->presist($author);
        $manager->getManager()->flush();
        return $this->redirectToRoute('app_get_all_author');
    }

    #[Route('/author/delete/id',name:'app_delete_author')]
    public function detete($id,MangerRegistry $manager, AuthorRepository $repo){
        $author = $repo->find($id);
        $manager->getManager()->remove($author);
        $manager->getManager()->flush();
        return $this->redirectToRoute('app_get_all_author');
    }

    #[Route('/author/update/{id}',name:'app_update_by_id')]
    public function updateAuthor($id,ManagerRegistry $manager,AuthorRepository $repo,Request $req){
        $author = $repo->find($id);
        $form = $this->createForm(AuthorType::class,$author);
        $form->handleRequest($req);
        if($form->isSubmitted())
        {
            $manager->getManager()->persist($author);
            $manager->getManager()->flush();
            return $this->redirectToRoute('app_getall');
        }
        return $this->renderForm('author/add.html.twig',['f'=>$form]);
    }
    
    #[Route('/addAuthor', name: 'app_author_add')]
    public function addAuthor(Request $req,ManagerRegistry $manager) {
        $author = new author();
        $form = $this->createForm(authorType::class,$author);
        $form->handleRequest($req);
        //$author->setRef($form->getData()->getRef());
        if($form->isSubmitted()){
        $manager->getManager()->persist($author);
        $manager->getManager()->flush();
        return $this->redirectToRoute('app_author');
        }
        return $this->render('author/add.html.twig',[
            'f'=>$form->createView()
        ]);
    }

    

    


   
}