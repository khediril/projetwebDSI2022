<?php

namespace App\DataFixtures;

use App\Entity\User;
use App\Entity\User1;
use App\Entity\Produit;
use App\Entity\Categorie;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class ProduitFixtures extends Fixture
{
    private $passwordHasher;
    public function __construct(UserPasswordHasherInterface $passwordHasher)
    {
      $this->passwordHasher = $passwordHasher;
    }
    public function load(ObjectManager $manager): void
    {
        for ($j=1; $j < 6; $j++) {
            $categorie = new Categorie();
            $categorie->setNom("Categorie".$j);
            $manager->persist($categorie);
            $user = new User();
            $user->setNom('user'.$j)
             ->setEmail('user'.$j."@gmail.com");
            $manager->persist($user);
            for ($i=1;$i<10 ;$i++) {
                $produit = new Produit();
                $produit->setNom("produit".$j.$i);
                $produit->setPrix(5*$i);
                $produit->setDescription("Le produit ".$i."est made in Tunisia");
                $produit->setStock(2*$i);
                $produit->setCreatedAt(new \DateTime());
                $produit->setCategorie($categorie);
                $produit->setOwner($user);
                // $product = new Product();
                $manager->persist($produit);
            }
        }
        for ($i=1; $i < 3; $i++) {
            $user = new User1();
        
            $user->setNom("user".$i)
             ->setEmail("user".$i."@gmail.com");
        
            $hashedPassword = $this->passwordHasher->hashPassword(
                $user,
                "user".$i
            );
            $user->setPassword($hashedPassword)
                 ->setRoles(['ROLE_USER']);
                 
            $manager->persist($user);
            $manager->flush();
        }
        for ($i=3; $i < 5; $i++) {
            $user = new User1();
        
            $user->setNom("user".$i)
             ->setEmail("user".$i."@gmail.com");
        
            $hashedPassword = $this->passwordHasher->hashPassword(
                $user,
                "user".$i
            );
            $user->setPassword($hashedPassword)
                 ->setRoles(['ROLE_ADMIN']);
                 
            $manager->persist($user);
            $manager->flush();
        }
    }
}
