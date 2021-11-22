<?php

namespace App\Repository;

use App\Entity\LigneFraisHorsForfait;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method LigneFraisHorsForfait|null find($id, $lockMode = null, $lockVersion = null)
 * @method LigneFraisHorsForfait|null findOneBy(array $criteria, array $orderBy = null)
 * @method LigneFraisHorsForfait[]    findAll()
 * @method LigneFraisHorsForfait[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class LigneFraisHorsForfaitRepository extends ServiceEntityRepository {

    public function __construct(ManagerRegistry $registry) {
        parent::__construct($registry, LigneFraisHorsForfait::class);
    }

    public function getLesFraisHorsForfait(int $id, string $mois) {
        $conn = $this->getEntityManager()->getConnection();
        $sql = "select id, id_visiteur_id, mois, libelle, datehf as date,montant from ligne_frais_hors_forfait where id_visiteur_id = :id and mois = :mois";
        $stmt = $conn->prepare($sql);
        $resultSet = $stmt->executeQuery(['id' => $id, 'mois' => $mois]);
        $res = $resultSet->fetchAllAssociative();
        dump($res);
        $nbLignes = count($res);
        for ($i = 0; $i < $nbLignes; $i++) {
            $res[$i]['date'] = dateAnglaisVersFrancais($res[$i]['date']);
        }
        return $res;
    }

    public function creeNouveauFraisHorsForfait(int $id, string $mois, string $libelle, string $date, int $montant) {
        $conn = $this->getEntityManager()->getConnection();

        $dateFr = dateFrancaisVersAnglais($date);
        $sql = "insert into ligne_frais_hors_forfait (idVisiteur, mois, libelle,datehf,montant)
		values(:id,:mois,:libelle,:date,:montant)";
        $stmt = $conn->prepare($sql);
        $stmt->executeQuery(['id' => $id, 'mois' => $mois, 'libelle' => $libelle, 'date' => $dateFr, "montant" => $montant]);
    }

    public function supprimerFraisHorsForfait(int $id) {
                $conn = $this->getEntityManager()->getConnection();

        $sql = "delete from ligne_frais_hors_forfait where id =:id ";
        $stmt = $conn->prepare($sql);
        $stmt->executeQuery(['id' => $id]);
    }
    // /**
    //  * @return LigneFraisHorsForfait[] Returns an array of LigneFraisHorsForfait objects
    //  */
    /*
      public function findByExampleField($value)
      {
      return $this->createQueryBuilder('l')
      ->andWhere('l.exampleField = :val')
      ->setParameter('val', $value)
      ->orderBy('l.id', 'ASC')
      ->setMaxResults(10)
      ->getQuery()
      ->getResult()
      ;
      }
     */

    /*
      public function findOneBySomeField($value): ?LigneFraisHorsForfait
      {
      return $this->createQueryBuilder('l')
      ->andWhere('l.exampleField = :val')
      ->setParameter('val', $value)
      ->getQuery()
      ->getOneOrNullResult()
      ;
      }
     */
}
