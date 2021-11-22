<?php

namespace App\Repository;

use App\Entity\Visiteur;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Visiteur|null find($id, $lockMode = null, $lockVersion = null)
 * @method Visiteur|null findOneBy(array $criteria, array $orderBy = null)
 * @method Visiteur[]    findAll()
 * @method Visiteur[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class VisiteurRepository extends ServiceEntityRepository {

    public function __construct(ManagerRegistry $registry) {
        parent::__construct($registry, Visiteur::class);
    }

    public function getVisiteur(int $id): ?array {
        $conn = $this->getEntityManager()->getConnection();
        $sql = ' SELECT * FROM visiteur WHERE id = :id ';
        $stmt = $conn->prepare($sql);
        $resultSet = $stmt->executeQuery(['id' => $id]);
        return $resultSet->fetchAssociative();
    }
    
    public function isConnected(string $login, string $mdp):int{
        
        $id=0;
        $conn = $this->getEntityManager()->getConnection();
        $sql = ' SELECT id FROM visiteur WHERE login = :login and mdp = :mdp ';
        $stmt = $conn->prepare($sql);
        $resultSet = $stmt->executeQuery(['login' => $login, 'mdp'=> $mdp]);
        $resultat = $resultSet->fetchAssociative();
        if(is_array($resultat)){
            
            $id=$resultat["id"];
            
        }
        return $id;
        
    }
    
    

    // /**
    //  * @return Visiteur[] Returns an array of Visiteur objects
    //  */
    /*
      public function findByExampleField($value)
      {
      return $this->createQueryBuilder('v')
      ->andWhere('v.exampleField = :val')
      ->setParameter('val', $value)
      ->orderBy('v.id', 'ASC')
      ->setMaxResults(10)
      ->getQuery()
      ->getResult()
      ;
      }
     */

    /*
      public function findOneBySomeField($value): ?Visiteur
      {
      return $this->createQueryBuilder('v')
      ->andWhere('v.exampleField = :val')
      ->setParameter('val', $value)
      ->getQuery()
      ->getOneOrNullResult()
      ;
      }
     */
}