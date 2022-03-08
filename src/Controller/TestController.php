<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TestController extends AbstractController
{
    /**
     * @Route("/test1", name="app_test1")
     */
    public function index(): Response
    {
        $rep = $this->render('test/test1.html.twig',[]);
        return $rep;
    }

    /**
     * @Route("/test2", name="app_test2")
     */
    public function test(): Response
    {
        $names = ['lina','Mehdi','Chaima','Malek','Rami'];
       
        return  $this->render('test/test2.html.twig', ['noms'=> $names ]);
       
    }
    /**
     * @Route("/test3", name="app_test3")
     */
    public function test3(): Response
    {
        $names = ['lina','Mehdi','Chaima','Malek','Rami'];
        $code =<<<MARQUEUR
        <!DOCTYPE html><html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    Je suis dans la page test3 333333333
</body>
</html>
MARQUEUR; 
        $rep = new Response($code);
        return  $rep;
        //$this->render('test/test2.html.twig', ['noms'=> $names ]);
       
    }
    /**
     * @Route("/somme/{a}/{b}", name="app_test4")
     */
    public function somme($a,$b): Response
    {
        $somme = $a + $b;
       
        return  $this->render('test/somme.html.twig', ["x" => $a,"y"=>$b ,"som" => $somme ]);
       
    }
}
