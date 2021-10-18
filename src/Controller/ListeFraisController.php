<?php

// src/Controller/ListeFraisController.php

namespace App\Controller;

require_once("include/fct.inc.php");
require_once("include/class.pdogsb.inc.php");

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use PdoGsb;

class ListeFraisController extends AbstractController {

    public function index(Request $request) {
        $session = $request->getSession();
        $idVisiteur = $session->get('id');
        $pdo = PdoGsb::getPdoGsb();
        $lesMois = $pdo->getLesMoisDisponibles($idVisiteur);
        if ($lesMois != null) {// s'il y a au moins une fiche de frais pour ce visiteur
            if ($request->getMethod() == 'GET') {
                $lesCles = array_keys($lesMois);
                $moisASelectionner = $lesCles[0];
                return $this->render('ListeFrais/listemois.html.twig',
                                array('lesmois' => $lesMois, 'lemois' => $moisASelectionner));
            } else {
                // suite du traitement non recopié ici on le rajoutera en fin de TP
                
            }
        } else { // il n’y a pas de mois
            return $this->render('ListeFrais/listemois.html.twig');
        }
    }

// fin de la function
}

// fin de la classe