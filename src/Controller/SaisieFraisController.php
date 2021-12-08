<?php

namespace App\Controller;

require_once("include/fct.inc.php");

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

class SaisieFraisController extends AbstractController {

    /**
     * Fonction permettant d'initialiser dans un tableau toutes les variables qui seront utile au traitement des données dans les fonctions suivantes et pour le render
     * @param Request $request
     * @return type
     */
    private function initialisationVar(Request $request) {
        $session = $request->getSession();
        $mois = getMois(date("d/m/Y"));
        $variables = array(
            "repositoryLigneFraisForfait" => $this->getDoctrine()->getRepository(\App\Entity\LigneFraisForfait::class),
            "repositoryLigneFraisHorsForfait" => $this->getDoctrine()->getRepository(\App\Entity\LigneFraisHorsForfait::class),
            "repositoryFicheFrais" => $this->getDoctrine()->getRepository(\App\Entity\FicheFrais::class),
            "erreur" => "",
            "idVisiteur" => $session->get("id"),
            "mois" => $mois,
            "numAnnee" => substr($mois, 0, 4),
            "numMois" => substr($mois, 4, 2)
        );

        return $variables;
    }

    /**
     * Fonction permettant d'initialiser un tableau avec les résultats des différentes requêtes interrogeant la base de données afin de lister tous les frais et l'état de la fiche
     * @param Request $request
     * @return type
     */
    private function varForRender(Request $request) {
        $variables = $this->initialisationVar($request);
        $return = array(
            "lesfraishorsforfait" => $variables["repositoryLigneFraisHorsForfait"]->getLesFraisHorsForfait($variables["idVisiteur"], $variables["mois"]),
            "lesfraisforfait" => $variables["repositoryLigneFraisForfait"]->getLesFraisForfait($variables["idVisiteur"], $variables["mois"]),
            "etat" => $variables["repositoryFicheFrais"]->getEtatFicheFrais($variables["idVisiteur"], $variables["mois"])
        );
        return $return;
    }

    /**
     * fonction initialisant la page de saisie de fiche de frais
     * @param Request $request
     * @return type
     */
    public function saisir_frais(Request $request) {
        
        //appel de toutes les variables permettant le traitement et le rendu
        $variables = $this->initialisationVar($request);

        //Si il n'y a pas de fiche pour le mois en cours, on en crée une (au cas où l'utilisateur arrive à ne pas passer par l'accueil)
        if ($variables["repositoryFicheFrais"]->estPremierFraisMois($variables["idVisiteur"], $variables["mois"])) {
            $variables["repositoryFicheFrais"]->creeNouvellesLignesFrais($variables["idVisiteur"], $variables["mois"]);
        }
        
        //on ajoute dans notre variable tableau d'autres variables permettant le rendu de la vue
        $variables += $this->varForRender($request);

        //rendu
        return $this->render('SaisieFrais/saisietouslesfrais.html.twig', $variables);
    }

    /**
     * fonction permettant de mettre à jour les frais forfait de l'utilisateur
     * @param Request $request
     * @return type
     */
    public function valider_maj_frais_forfait(Request $request) {
        
        //appel de toutes les variables permettant le traitement et le rendu
        $variables = $this->initialisationVar($request);

        //on récupère les frais forfait changés
        $lesFrais = $request->request->get('lesFrais');
        
        //si la quantité est valide on met à jour les forfaits, sinon on ajoute l'erreur dans notre variable
        if (lesQteFraisValides($lesFrais)) {

            $variables["repositoryLigneFraisForfait"]->majFraisForfait($variables["idVisiteur"], $variables["mois"], $lesFrais);
        } else {

            $variables["erreur"] = "Les valeurs des frais doivent être numériques";
        }
        
        //on ajoute dans notre variable tableau d'autres variables permettant le rendu de la vue
        $variables += $this->varForRender($request);
        
        //rendu
        return $this->render('SaisieFrais/saisietouslesfrais.html.twig', $variables);
    }

    /**
     * Fonction permettant de valider la création de nouveaux frais
     * @param Request $request
     * @return type
     */
    public function valider_creation_frais(Request $request) {

        //appel de toutes les variables permettant le traitement et le rendu
        $variables = $this->initialisationVar($request);

        //on récupère les données saisies par l'utilisateur concernant un frais hors forfait
        $dateFrais = $request->request->get('dateFrais');
        $libelle = $request->request->get('libelle');
        $montant = $request->request->get('montant');

        //on test les données retournées
        $variables["erreur"] = valideInfosFrais($dateFrais, $libelle, $montant);
        
        //Si il n'y a pas d'erreurs on crée un nouveau frais hors forfait
        if ($variables["erreur"] === "") {

            $variables["repositoryLigneFraisHorsForfait"]->creeNouveauFraisHorsForfait($variables["idVisiteur"], $variables["mois"], $libelle, $dateFrais, $montant);
        }

        //on ajoute dans notre variable tableau d'autres variables permettant le rendu de la vue
        $variables += $this->varForRender($request);

        //rendu
        return $this->render('SaisieFrais/saisietouslesfrais.html.twig', $variables);
    }

    public function supprimer_frais(Request $request) {

        //appel de toutes les variables permettant le traitement et le rendu
        $variables = $this->initialisationVar($request);

        //on récupère l'id du frais hors forfait selectionné et on supprime le frais
        $id = $request->query->get('id');
        $variables["repositoryLigneFraisHorsForfait"]->supprimerFraisHorsForfait($id);

        //on ajoute dans notre variable tableau d'autres variables permettant le rendu de la vue
        $variables += $this->varForRender($request);

        //rendu
        return $this->render('SaisieFrais/saisietouslesfrais.html.twig', $variables);
    }

    public function changer_etat_fiche_frais(Request $request) {

        //appel de toutes les variables permettant le traitement et le rendu
        $variables = $this->initialisationVar($request);

        //on récupère l'état de la fiche et on change sa valeur en fonction de sa valeur
        $etatAChanger = $variables["repositoryFicheFrais"]->getEtatFicheFrais($variables["idVisiteur"], $variables["mois"]);
        switch ($etatAChanger) {
            case "CR":
                $variables["repositoryFicheFrais"]->cloturerFicheFrais($variables["idVisiteur"], $variables["mois"]);
                break;

            case "CL":
                $variables["repositoryFicheFrais"]->ouvrirFicheFrais($variables["idVisiteur"], $variables["mois"]);
                break;
        }

        //on ajoute dans notre variable tableau d'autres variables permettant le rendu de la vue
        $variables += $this->varForRender($request);

        //rendu
        return $this->render('SaisieFrais/saisietouslesfrais.html.twig', $variables);
    }

}
