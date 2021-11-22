<?php

namespace App\Repository;

use App\Entity\LigneFraisForfait;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method LigneFraisForfait|null find($id, $lockMode = null, $lockVersion = null)
 * @method LigneFraisForfait|null findOneBy(array $criteria, array $orderBy = null)
 * @method LigneFraisForfait[]    findAll()
 * @method LigneFraisForfait[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class LigneFraisForfaitRepository extends ServiceEntityRepository {

    public function __construct(ManagerRegistry $registry) {
        parent::__construct($registry, LigneFraisForfait::class);
    }

    public function getLesFraisForfait(int $id, int $mois) {
        $conn = $this->getEntityManager()->getConnection();
        $sql = "select FF.ini_libelle as idfrais, FF.libelle as libelle, 
		LFF.quantite as quantite from ligne_frais_forfait LFF inner join frais_forfait FF
		on FF.id = LFF.id_frais_forfait_id
		where LFF.id_visiteur_id =:id and LFF.mois=:mois 
		order by LFF.id_frais_forfait_id";
        $stmt = $conn->prepare($sql);
        $resultSet = $stmt->executeQuery(['id' => $id, 'mois' => $mois]);
        $lesLignes = $resultSet->fetchAllAssociative();
        return $lesLignes;
    }

    public function majFraisForfait(int $id, string $mois, array $lesFrais) {
        $conn = $this->getEntityManager()->getConnection();

        $lesCles = array_keys($lesFrais);
        foreach ($lesCles as $unIdFrais) {
            $qte = $lesFrais[$unIdFrais];
            $sql = "update ligne_frais_forfait set quantite = :qte
			where idVisiteur = :id and mois = :mois
			and idFraisForfait = :unIdFrais";
            $stmt = $conn->prepare($sql);
            $stmt->executeQuery(['id' => $id, 'mois' => $mois, 'qte'=>$qte, "unIdFrais" => $unIdFrais]);
        }
    }

    // /**
    //  * @return LigneFraisForfait[] Returns an array of LigneFraisForfait objects
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
      public function findOneBySomeField($value): ?LigneFraisForfait
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
