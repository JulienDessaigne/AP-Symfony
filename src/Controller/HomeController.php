<?php

// src/Controller/HomeController.php

namespace App\Controller;

require_once("include/fct.inc.php");
require_once("include/class.pdogsb.inc.php");

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use PdoGsb;

class HomeController extends AbstractController {

    public function index() {

        return $this->render('Home/connexion.html.twig');
    }

    public function validerConnexion(Request $request) {
        $session = $request->getSession();
        $login = $request->request->get('login');
        $mdp = $request->request->get('mdp');
        $pdo = PdoGsb::getPdoGsb();
        $visiteur = $pdo->getInfosVisiteur($login, $mdp);
        if (!is_array($visiteur)) {
            return $this->render('Home/connexion.html.twig',
                            array('message' => 'Erreur de login ou de mot de passe ')
            );
        } else {
            $session->set('id', $visiteur['id']);
            $session->set('nom', $visiteur['nom']);
            $session->set('prenom', $visiteur['prenom']); // $_SESSION[‘prenom’] = $visiteur['prenom']
            return $this->render('accueil.html.twig');
        } 
    }
    public function deconnexion(Request $request) {
    
        $request->getSession()->clear();
        
        return $this->render('Home/connexion.html.twig');
    }

}

?>