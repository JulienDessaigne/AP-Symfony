<?php

// src/Controller/LuckyController.php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class HelloController extends AbstractController {

    public function word(string $name) {
        
        return $this->render('hello/hello.html.twig', ['name' => $name]);
    }

}
