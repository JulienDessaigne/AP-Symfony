<?php

// src/Controller/ListeFraisController.php

namespace App\Controller;

require_once("include/fct.inc.php");

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ListeFraisController extends AbstractController {

    /**
     * Fonction permettant au visiteur de selectionner une fiche de frais. A la selection, affiche dans la même vue tous les détails de cette fiche de frais
     * @param Request $request
     * @return type
     */
    public function index(Request $request) {
        $session = $request->getSession();
        $idVisiteur = $session->get('id');
        $repositoryFicheFrais = $this->getDoctrine()->getRepository(\App\Entity\FicheFrais::class);
        $repositoryLigneFraisForfait = $this->getDoctrine()->getRepository(\App\Entity\LigneFraisForfait::class);
        $repositoryligneFraisHorsForfait = $this->getDoctrine()->getRepository(\App\Entity\LigneFraisHorsForfait::class);
        dump($repositoryLigneFraisForfait->getLesFraisForfait($idVisiteur,202111));
        $lesMois = $repositoryFicheFrais->getLesMoisDispo($idVisiteur);
        if ($lesMois != null) {// s'il y a au moins une fiche de frais pour ce visiteur
            if ($request->getMethod() == 'GET') {
                $lesCles = array_keys($lesMois);
                $moisASelectionner = $lesCles[0];
                return $this->render('ListeFrais/listemois.html.twig',
                                array('lesmois' => $lesMois, 'lemois' => $moisASelectionner));
            } else {
                $leMois = $request->request->get('lstMois');

                $lesMois = $repositoryFicheFrais->getLesMoisDispo($idVisiteur);
                $moisASelectionner = $leMois;
                $lesFraisHorsForfait = $repositoryligneFraisHorsForfait->getLesFraisHorsForfait($idVisiteur, $leMois);
                $lesFraisForfait = $repositoryLigneFraisForfait->getLesFraisForfait($idVisiteur, $leMois);
                $lesInfosFicheFrais = $repositoryFicheFrais->getLesInfosFicheFrais($idVisiteur, $leMois);
                $numAnnee = substr($leMois, 0, 4);
                $numMois = substr($leMois, 4, 2);
                $libEtat = $lesInfosFicheFrais['libEtat'];
                $montantValide = $lesInfosFicheFrais['montantValide'];
                $nbJustificatifs = $lesInfosFicheFrais['nbJustificatifs'];
                $dateModif = dateAnglaisVersFrancais($lesInfosFicheFrais['dateModif']);

                return $this->render('ListeFrais/listetouslesfrais.html.twig',
                                array('numannee' => $numAnnee,
                                    'nummois' => $numMois,
                                    'lesfraishorsforfait' => $lesFraisHorsForfait,
                                    'lesfraisforfait' => $lesFraisForfait,
                                    'libetat' => $libEtat,
                                    'montantvalide' => $montantValide,
                                    'nbjustificatifs' => $nbJustificatifs,
                                    'datemodif' => $dateModif,
                                    'lesmois' => $lesMois,
                                    'lemois' => $moisASelectionner));
            }
        } else {
            return $this->render('ListeFrais/listemois.html.twig',
                            array('lesmois' => $lesMois));
        }
    }

// fin de la function
}

// fin de la classe