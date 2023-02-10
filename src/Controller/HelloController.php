<?php

namespace App\Controller;


use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Twig\Environment;

class HelloController{

    protected $twig;
    public function __construct(Environment $twig)
    {
        $this->twig = $twig;
    }


  protected function render(string $path, array $variables = []){

        $html = $this->twig->render($path, $variables);
        return new Response($html);
    }
   

    /**
     * @Route("/hello/{prenom?World}", name="hello")
     */
    public function hello($prenom){

        // $html = $this->twig->render('hello.html.twig', [
        //     'prenom'=>$prenom, 
        // ]);
        
        // return new Response($html);

        return $this->render('hello.html.twig', [
            'prenom' => $prenom
        ]);

    }

    /**
     * @Route("/example", name="example")
     */
    public function example(){

        
        return $this->render('example.html.twig', [

            'age' => 34
        ]);

    }

    
}