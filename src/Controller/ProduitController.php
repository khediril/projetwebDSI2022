<?php

namespace App\Controller;

use App\Entity\Produit;
use App\Repository\ProduitRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
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
     * @Route("/produit/list", name="app_produit_list")
     */
    public function list(ProduitRepository $repos): Response
    {
        $produits = $repos->findAll();
        return $this->render('produit/list.html.twig', ["produits"=>$produits]);

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
     * @Route("/api/detail/{id}", name="app_produit_detail")
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
    
}
