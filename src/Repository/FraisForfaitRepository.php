<?php

namespace App\Repository;

use App\Entity\FraisForfait;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method FraisForfait|null find($id, $lockMode = null, $lockVersion = null)
 * @method FraisForfait|null findOneBy(array $criteria, array $orderBy = null)
 * @method FraisForfait[]    findAll()
 * @method FraisForfait[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class FraisForfaitRepository extends ServiceEntityRepository {

    public function __construct(ManagerRegistry $registry) {
        parent::__construct($registry, FraisForfait::class);
    }

    

    public function getLesIdFrais() {
        $conn = $this->getEntityManager()->getConnection();
        $sql = "select ini_libelle as idfrais from frais_forfait order by ini_libelle";
        $stmt = $conn->prepare($sql);
        $resultSet = $stmt->executeQuery();
        $lesLignes = $resultSet->fetchAllAssociative();
        return $lesLignes;
    }
    
    
    public function getIdByIni(sintrg $etat){
        $conn = $this->getEntityManager()->getConnection();
        $sql = "select id from frais_forfait where ini_lib=:etat ";
        $stmt = $conn->prepare($sql);
        $resultSet = $stmt->executeQuery(['etat'=>$etat]);
        return $resultSet->fetchAssociative();
         
        
        
    }
    // /**
    //  * @return FraisForfait[] Returns an array of FraisForfait objects
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
      public function findOneBySomeField($value): ?FraisForfait
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
