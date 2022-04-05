<?php

namespace App\DataFixtures;

use App\Entity\Produit;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;

class ProduitFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        for ($i=1;$i<20 ;$i++) {
            $produit = new Produit();
            $produit->setNom("produit".$i);
            $produit->setPrix(5*$i);
            $produit->setDescription("Le produit ".$i."est made in Tunisia");
            $produit->setStock(2*$i);
            $produit->setCreatedAt(new \DateTime());
            // $product = new Product();
            $manager->persist($produit);
        }

        $manager->flush();
    }
}
