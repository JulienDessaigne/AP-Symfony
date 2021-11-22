<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class VisiteurController extends AbstractController {

    #[Route('/visiteur', name: 'visiteur')]
    public function index(): Response
    {
        return $this->render('visiteur/index.html.twig', [
            'controller_name' => 'VisiteurController',
        ]);
    }
    #[Route('/creervisiteur', name: 'creer_visiteur')]
    public function createVisiteur(): Response {
        $entityManager = $this->getDoctrine()->getManager();
        $visiteur = new GSBFraisVisiteur();
        $visiteur->setNom('Amu');
        $visiteur->setPrenom('Sandrine');
        $visiteur->setLogin('samu');
        $visiteur->setMdp('pinpon');
        $visiteur->setDateEmbauche(date_create('2015-11-12'));
        $visiteur->setAncienId('p55');
// on signale à Doctrine que cet objet sera persistant
        $entityManager->persist($visiteur);
// On informe Doctrine de mettre à jour l'objet ouvert en ce moment (insert into)
        $entityManager->flush();
        return new Response('nouveau visiteur créé n°' . $visiteur->getId());
    }

}
