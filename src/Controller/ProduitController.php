<?php

namespace App\Controller;

use App\Entity\Produit;
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
    public function add($nom,$prix,$stock): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $produit = new Produit();
        $produit->setNom($nom);
        $produit->setPrix($prix);
        $produit->setDescription("ce produit est made in Tunisia");
        $produit->setStock($stock);
        $produit->setCreatedAt(new \DateTime());
        $entityManager->persist($produit);
        $entityManager->flush();

        return $this->render('produit/add.html.twig', ["nom" => $nom  ]);
    }
}
