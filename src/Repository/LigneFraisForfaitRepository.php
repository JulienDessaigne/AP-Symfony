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

    /**
     * Retourne les frais forfait d'une fiche frais d'un visiteur
     * @param int $id
     * @param int $mois
     * @return array|null
     */
    public function getLesFraisForfait(int $id, int $mois):?array {
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

    /**
     * Met à jour les frais forfait d'une fiche frais d'un visiteur
     * @param int $id
     * @param string $mois
     * @param array $lesFrais
     */
    public function majFraisForfait(int $id, string $mois, array $lesFrais) {
        $conn = $this->getEntityManager()->getConnection();
        $lesCles = array_keys($lesFrais);
        dump($lesCles);
        foreach ($lesCles as $unIdFrais) {
            $qte = $lesFrais[$unIdFrais];
            dump($qte);
            $sql = "update ligne_frais_forfait set quantite = :qte
			where id_visiteur_id = :id and mois = :mois
			and id_frais_forfait_id = (select id from frais_forfait where ini_libelle=:unIdFrais)";
            $stmt = $conn->prepare($sql);
            dump($id);
            dump($mois);
            dump($qte);
            dump($unIdFrais);

            $stmt->executeQuery(['id' => $id, 'mois' => $mois, 'qte' => $qte, "unIdFrais" => $unIdFrais]);
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
