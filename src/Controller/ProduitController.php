<?php

namespace App\Controller;

use App\Entity\Produit;
use App\Form\ProduitType;
use App\Service\MessageGenerator;
use App\Repository\UserRepository;
use App\Repository\ProduitRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


class ProduitController extends AbstractController
{
    /**
     * @Route("/produit", name="app_produit")
     */
    public function index(): Response
    {
        return $this->render('produit/index.html.twig', [
            'controller_name' => 'ProduitController',
        ]);
    }
    /**
     * @Route("/produit/add/{nom}/{prix}/{stock}", name="app_produit")
     */
    public function add($nom,$prix,$stock,ManagerRegistry $doctrine): Response
    {
        $entityManager = $doctrine->getManager();
        $produit = new Produit();
        $produit->setNom($nom);
        $produit->setPrix($prix);
        $produit->setDescription("ce produit est made in Tunisia");
        $produit->setStock($stock);
        $produit->setCreatedAt(new \DateTime());
        $entityManager->persist($produit);
        $entityManager->flush();

        return $this->render('produit/add.html.twig', ["produit" => $produit  ]);
    }
     /**
     * @Route("/{_locale}", name="app_produit_list")
     * @IsGranted("ROLE_ADMIN")
     */
    public function list(ProduitRepository $repos,MessageGenerator $mg): Response
    {
        $user = $this->getUser();
       // dd($user);
        $email = $user->getEmail();
        $message = $mg->getHappyMessage();
        $produits = $repos->findAll();
        return $this->render('produit/list.html.twig', ["produits"=>$produits,"ema"=>$email,"msg" =>$message]);

    }
    /**
     * @Route("/produit/del/{id}", name="app_produit_delete")
     */
    public function delete($id,ProduitRepository $repos,ManagerRegistry $doctrine): Response
    {
        $produit=$repos->find($id);
        
        $entityManager = $doctrine->getManager();
        
        $entityManager->remove($produit);
        $entityManager->flush();
        return $this->redirectToRoute('app_produit_list');
        //return $this->render('produit/add.html.twig', ["produit" => $produit  ]);
    }
     /**
     * @Route("/produit/detail/{id}", name="app_produit_detail")
     */
    public function detail($id,ProduitRepository $repos): Response
    {
        $produit=$repos->find($id);
        if(!$produit)
        {
            return $this->render('produit/erreur.html.twig', ["msg" => "Aucun produit avec id:$id"  ]);     
        }
       
        //return $this->redirectToRoute('app_produit_list');
        return $this->render('produit/detail.html.twig', ["produit" => $produit  ]);
    }
      /**
     * @Route("/produit/chercher/{pmin}/{pmax}", name="app_produit_chercher")
     */
    public function chercher($pmin,$pmax,ProduitRepository $repos): Response
    {
        $produits = $repos->chercherParIntervalPrix($pmin,$pmax);

        return $this->render('produit/list.html.twig', ["produits"=>$produits]);

    }
     /**
     * @Route("/api/detail/{id}", name="api_app_produit_detail")
     */
    public function detailJson($id,ProduitRepository $repos): Response
    {
        $produit=$repos->find($id);
        if(!$produit)
        {
            return $this->render('produit/erreur.html.twig', ["msg" => "Aucun produit avec id:$id"  ]);     
        }
       
        //return $this->redirectToRoute('app_produit_list');
        return $this->json($produit);
    }
    /**
     * @Route("/produit/ajout", name="app_produit_ajout")
     */
    public function ajout(Request $request,UserRepository $userrepo): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');
        $produit = new Produit();
       
       $form = $this->createForm(ProduitType::class,$produit,[
                'method' => 'GET',
    ]);
       
       //traitement du formulaire
       $form->handleRequest($request);
       if ($form->isSubmitted() && $form->isValid()) {
           // $form->getData() holds the submitted values
           // but, the original `$task` variable has also been updated
          // $task = $form->getData();
           $produit->setCreatedAt(new \DateTime());
           $user = $userrepo->find(1);
           $produit->setOwner($user);
           $em = $this->getDoctrine()->getManager();
           $em->persist($produit);
           $em->flush();
           // ... perform some action, such as saving the task to the database

           return $this->redirectToRoute('app_produit_list');
       }

       //return $this->render('/produit/ajout.html.twig',['form' => $form->createView()]);
       return $this->renderForm('/produit/ajout.html.twig',['form' => $form]);
    }
    
}
