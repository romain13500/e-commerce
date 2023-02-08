<?php

namespace App\Controller;

use App\Taxes\Calculator;
use App\Taxes\Detector;
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
    public function hello($prenom,  Environment $twig){

        $html = $twig->render('hello.html.twig', [
            'prenom'=>$prenom, 
            'age'=>34,
            'prenoms'=> ['Malika','Kassim','Lalimate']
        ]);
        return new Response($html);

    }
}