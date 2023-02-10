<?php

namespace App\Controller;

use App\Taxes\Calculator;
use App\Taxes\Detector;
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
            'Joueur1'=>[
                'prenom'=> 'Zinedine',
                'nom'=> 'Zidane',
                'age'=> 43     
            ],
            'Joueur2'=>[
                'prenom'=> 'Didier',
                'nom'=> 'Drogba',
                'age'=> 40
            ]
        ]);
        
        return new Response($html);

    }
}