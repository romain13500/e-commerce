<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HelloController{

    /**
     * @Route("/hello/{prenom?World}", name="hello")
     */
    public function hello($prenom){

        return new Response("Salam aleykoum $prenom");

    }
}