<?php

namespace App\Controller;

class TestController
{
    public function index()
    {
        dump("c'est good");
        die();
    }

    public function test()
    {
        dump("page test");
        die();
    }
}