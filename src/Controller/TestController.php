<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class TestController
{
    public function index()
    {
        dd("c'est good");
        
    }

    public function test(Request $request)
    {
        $age = $request->query->get('age',0);
        return new Response(" Vous avez $age ans !");    
    }
}