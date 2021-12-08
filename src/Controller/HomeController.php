<?php

// src/Controller/HomeController.php

namespace App\Controller;

require_once("include/fct.inc.php");

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class HomeController extends AbstractController {

    /**
     * Si l'utilisateur est encore connecté on le dirige vers l'accueil, sinon vers la page de connexio
     * @param Session $session
     * @return type
     */
    public function index(Session $session) {

        if ($session->has("id")) {

            return $this->render('accueil.html.twig');
        } else {
            return $this->render('Home/connexion.html.twig');
        }
    }

    /**
     * Fonction qui récupère le logien et mdp renseigné par le visiteur et qui le connecte si le visiteur existe
     * @param Request $request
     * @return type
     */
    public function validerConnexion(Request $request) {
        $session = $request->getSession();
        $login = $request->request->get('login');
        $mdp = $request->request->get('mdp');
        $repository = $this->getDoctrine()->getRepository(\App\Entity\Visiteur::class);
        $id = $repository->isConnected($login, $mdp);
        dump($id);
        if ($id === 0) {
            return $this->render('Home/connexion.html.twig',
                            array('message' => 'Erreur de login ou de mot de passe ')
            );
        } else {
            $visiteur = $repository->getVisiteur($id);
            $session->set('id', $visiteur['id']);
            $session->set('nom', $visiteur['nom']);
            $session->set('prenom', $visiteur['prenom']);

            //Si il n'y a pas de fiche de frais pour le mois en cours, on en crée une
            $mois = getMois(date("d/m/Y"));
            $id = $session->get("id");
            if ($this->getDoctrine()->getRepository(\App\Entity\FicheFrais::class)->estPremierFraisMois($id, $mois)) {
                $this->getDoctrine()->getRepository(\App\Entity\FicheFrais::class)->creeNouvellesLignesFrais($id,$mois);
            }
            return $this->render('accueil.html.twig');
        }
    }

    /**
     * Deconnecte le visiteur
     * @param Request $request
     * @return type
     */
    public function deconnexion(Request $request) {

        $request->getSession()->clear();

        return $this->render('Home/connexion.html.twig');
    }

}

?>