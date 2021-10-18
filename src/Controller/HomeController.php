<?php

// src/Controller/HomeController.php

namespace App\Controller;

require_once("include/fct.inc.php");
require_once("include/class.pdogsb.inc.php");

use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use PdoGsb;

class HomeController extends AbstractController {

    public function index() {
        return $this->render('Home/connexion.html.twig');
    }

}

?>