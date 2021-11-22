<?php

namespace App\Controller;
require_once("include/fct.inc.php");

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
class SaisieFraisController extends AbstractController {

    #[Route('/saisie/frais', name: 'app_frais_saisie_frais')]
    public function saisir_frais(Request $request) {

        $session = $request->getSession();

        $repositoryFicheFrais = $this->getDoctrine()->getRepository(\App\Entity\FicheFrais::class);
        $repositoryLigneFraisForfait = $this->getDoctrine()->getRepository(\App\Entity\LigneFraisForfait::class);
        $repositoryligneFraisHorsForfait = $this->getDoctrine()->getRepository(\App\Entity\LigneFraisHorsForfait::class);

        $idVisiteur = $session->get("id");
        $mois = getMois(date("d/m/Y"));
        $numAnnee = substr($mois, 0, 4);
        $numMois = substr($mois, 4, 2);

        if ($repositoryFicheFrais->estPremierFraisMois($idVisiteur, $mois)) {
            $repositoryFicheFrais->creeNouvellesLignesFrais($idVisiteur, $mois);
        }


        $lesFraisHorsForfait = $repositoryligneFraisHorsForfait->getLesFraisHorsForfait($idVisiteur, $mois);
        $lesFraisForfait = $repositoryLigneFraisForfait->getLesFraisForfait($idVisiteur, $mois);

        dump($lesFraisForfait);
        return $this->render('SaisieFrais/saisietouslesfrais.html.twig', array('numannee' => $numAnnee,
                    'lesfraishorsforfait' => $lesFraisHorsForfait,
                    'lesfraisforfait' => $lesFraisForfait,
                    'numMois' => $numMois,
                    'numAnnee' => $numAnnee));
    }

    #[Route('/saisie/frais', name: 'app_frais_saisie_frais_validerMaj')]
    public function valider_maj_frais_forfait(): Response {
        return $this->render('SaisieFrais/saisietouslesfrais.html.twig', [
                    'controller_name' => 'SaisieFraisController',
        ]);
    }

    #[Route('/saisie/frais', name: 'app_frais_saisie_frais_validerCreation')]
    public function valider_creation_frais(): Response {
        return $this->render('SaisieFrais/saisietouslesfrais.html.twig', [
                    'controller_name' => 'SaisieFraisController',
        ]);
    }

    #[Route('/saisie/frais', name: 'app_frais_saisie_frais_supprimer')]
    public function supprimer_frais(): Response {
        return $this->render('SaisieFrais/saisietouslesfrais.html.twig', [
                    'controller_name' => 'SaisieFraisController',
        ]);
    }

}
