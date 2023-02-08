<?php

namespace App\Controller;

use App\Taxes\Calculator;
use Cocur\Slugify\Slugify;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Twig\Environment;

class HelloController{


    protected $logger;
    protected $calculator;
    
    public function __construct(LoggerInterface $logger, Calculator $calculator)
    {
        $this->logger = $logger;
        $this->calculator = $calculator;
    }

    /**
     * @Route("/hello/{prenom?World}", name="hello")
     */
    public function hello($prenom, LoggerInterface $logger, Calculator $calculator, Slugify $slugify, Environment $twig){

       
        dump($slugify->slugify("hey"));
        $logger->info("mon message de log !!");
        $tva = $calculator->calcul(100);
        dump($tva);
        $html = $twig->render('hello.html.twig', ['prenom'=>$prenom]);
        return new Response($html);

    }
}