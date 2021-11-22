<?php

namespace App\Repository;

use App\Entity\FicheFrais;
use App\Repository\EtatRepository;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method FicheFrais|null find($id, $lockMode = null, $lockVersion = null)
 * @method FicheFrais|null findOneBy(array $criteria, array $orderBy = null)
 * @method FicheFrais[]    findAll()
 * @method FicheFrais[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class FicheFraisRepository extends ServiceEntityRepository {

    public function __construct(ManagerRegistry $registry) {
        parent::__construct($registry, FicheFrais::class);
    }

    public function getLesMoisDispo(int $id): ?array {
        $conn = $this->getEntityManager()->getConnection();
        $sql = ' select mois as mois from fiche_frais where id_visiteur_id = :id order by mois desc  ';
        $stmt = $conn->prepare($sql);
        $resultSet = $stmt->executeQuery(['id' => $id]);
        $lesMois = array();
        $laLigne = $resultSet->fetchAllAssociative();
        dump($laLigne);
        foreach ($laLigne as $uneLigne) {
            dump($uneLigne);
            $mois = $uneLigne;
            $numAnnee = substr($mois["mois"], 0, 4);
            $numMois = substr($mois["mois"], 4, 2);
            $lesMois[$mois["mois"]] = array(
                "mois" => $mois["mois"],
                "numAnnee" => $numAnnee,
                "numMois" => $numMois
            );
        }
        return $lesMois;
    }

    public function getNbjustificatifs(int $id, string $mois) {
        $conn = $this->getEntityManager()->getConnection();
        $sql = "select nbJustificatifs as nb from FicheFrais where id_visiteur_id =:id and mois = :mois";
        $stmt = $conn->prepare($sql);
        $resultSet = $stmt->executeQuery(['id' => $id, 'mois' => $mois]);

        return $resultSet->fetchAllAssociative();
    }

    public function majNbJustificatifs(int $id, string $mois, int $nbJustificatifs) {
        $conn = $this->getEntityManager()->getConnection();
        $sql = "update FicheFrais set nbJustificatifs = :nbJustificatifs 
		where idVisiteur = :id and mois = :mois";
        $stmt = $conn->prepare($sql);
        $stmt->executeQuery(['id' => $id, 'nbJustificatifs' => $nbJustificatifs, 'mois' => $mois]);
    }

    public function getLesInfosFicheFrais(int $id, string $mois) {
        $conn = $this->getEntityManager()->getConnection();
        $sql = "select FF.id_etat_id as idEtat, FF.date_motif as dateModif, FF.nb_justificatifs as nbJustificatifs, 
			FF.montant_valide as montantValide, E.libelle as libEtat from  fiche_frais FF inner join etat E on FF.id_etat_id = E.id 
			where FF.id_visiteur_id =:id and FF.mois = :mois";
        $stmt = $conn->prepare($sql);
        $resultSet = $stmt->executeQuery(['id' => $id, 'mois' => $mois]);

        return $resultSet->fetchAssociative();
    }

    public function estPremierFraisMois(int $id, string $mois) {
        $conn = $this->getEntityManager()->getConnection();
        $ok = false;
        $sql = "select count(*) as nblignesfrais from fiche_frais 
		where mois = :mois and id_visiteur_id = :id";
        $stmt = $conn->prepare($sql);
        $resultSet = $stmt->executeQuery(['id' => $id, 'mois' => $mois]);
        $laLigne = $resultSet->fetchAssociative();
        if ($laLigne['nblignesfrais'] == 0) {
            $ok = true;
        }
        return $ok;
    }

    public function dernierMoisSaisi(int $id) {
        $conn = $this->getEntityManager()->getConnection();
        $sql = "select max(mois) as dernierMois from fiche_frais where id_visiteur_id = :id";
        $stmt = $conn->prepare($sql);
        $resultSet = $stmt->executeQuery(['id' => $id]);
        $laLigne = $resultSet->fetchAssociative();
        $dernierMois = $laLigne['dernierMois'];
        return $dernierMois;
    }

    public function creeNouvellesLignesFrais(int $id, string $mois) {
        $conn = $this->getEntityManager()->getConnection();
        $dernierMois = $this->dernierMoisSaisi($id);
        $laDerniereFiche = $this->getLesInfosFicheFrais($id, $dernierMois);
        if ($laDerniereFiche['idEtat'] == 'CR') {
            $this->majEtatFicheFrais($id, $dernierMois, 'CL');
        }
        
        $sql = "insert into fiche_frais(id_visiteur_id,mois,nb_justificatifs,montant_valide,date_motif,id_etat_id) 
		select :id,:mois,0,0,now(), e.id
                from etat as e where ini_lib='CR'";
        $stmt = $conn->prepare($sql);
        $stmt->executeQuery(['id' => $id, 'mois' => $mois]);

        
        
        $repositoryFraisForfait = $this->getEntityManager->getRepository(\App\Entity\FraisForfait::class);

        $lesIdFrais = $repositoryFraisForfait->getLesIdFrais();
        foreach ($lesIdFrais as $uneLigneIdFrais) {
            $unIdFrais = $uneLigneIdFrais['idfrais'];
            $sql = "insert into ligne_frais_forfait(id_visiteur_id,mois,id_frais_forfait_id,quantite) 
			values(:id,:mois,:unIdFrais,0)";
            $stmt = $conn->prepare($sql);
            $stmt->executeQuery(['id' => $id, 'mois' => $mois, 'unIdFrais' => $unIdFrais]);
        }
    }

    public function majEtatFicheFrais(int $id, string $mois, string $etat) {
        $conn = $this->getEntityManager()->getConnection();

        $sql = "update fiche_frais set id_etat_id = (select id from etat where ini_lib=:etat), dateModif = now() 
		where id_visiteur_id =:id and mois = :mois";
        $stmt = $conn->prepare($sql);
        $stmt->executeQuery(['id' => $id, 'mois' => $mois, 'etat' => $etat]);
    }

    // /**
    //  * @return FicheFrais[] Returns an array of FicheFrais objects
    //  */
    /*
      public function findByExampleField($value)
      {
      return $this->createQueryBuilder('f')
      ->andWhere('f.exampleField = :val')
      ->setParameter('val', $value)
      ->orderBy('f.id', 'ASC')
      ->setMaxResults(10)
      ->getQuery()
      ->getResult()
      ;
      }
     */

    /*
      public function findOneBySomeField($value): ?FicheFrais
      {
      return $this->createQueryBuilder('f')
      ->andWhere('f.exampleField = :val')
      ->setParameter('val', $value)
      ->getQuery()
      ->getOneOrNullResult()
      ;
      }
     */
}
