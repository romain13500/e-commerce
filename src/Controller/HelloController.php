<?php

namespace App\Controller;

use App\Taxes\Calculator;
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
    public function hello($prenom, Environment $twig){

        $this->logger->info("mon message de log !!");
        $tva = $this->calculator->calcul(100);
        dump($tva);
        $html = $twig->render('hello.html.twig', ['prenom'=>$prenom]);
        return new Response($html);

    }
}