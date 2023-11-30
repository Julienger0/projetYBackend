<?php

namespace App\Repository;

use App\Entity\User;
use App\Entity\UserRelation;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<UserRelation>
 *
 * @method UserRelation|null find($id, $lockMode = null, $lockVersion = null)
 * @method UserRelation|null findOneBy(array $criteria, array $orderBy = null)
 * @method UserRelation[]    findAll()
 * @method UserRelation[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserRelationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, UserRelation::class);
    }

    public function findMatchesForUser($userId)
    {
        // Obtenir les utilisateurs que l'utilisateur actuel a "likés"
        $likedUsers = $this->createQueryBuilder('ur')
            ->select('IDENTITY(ur.targetUser)')
            ->where('ur.user = :user')
            ->andWhere('ur.type = :type')
            ->setParameter('user', $userId)
            ->setParameter('type', 'like')
            ->getQuery()
            ->getResult();
    
        // Obtenir les utilisateurs qui ont "liké" l'utilisateur actuel
        $likedByUsers = $this->createQueryBuilder('ur')
            ->select('IDENTITY(ur.user)')
            ->where('ur.targetUser = :user')
            ->andWhere('ur.type = :type')
            ->setParameter('user', $userId)
            ->setParameter('type', 'like')
            ->getQuery()
            ->getResult();
    
        // Trouver les matchs (les utilisateurs présents dans les deux listes)
        $matches = array_intersect(array_column($likedUsers, 1), array_column($likedByUsers, 1));
    
        return $this->createQueryBuilder('ur')
            ->where('ur.user = :user AND ur.targetUser IN (:matches)')
            ->orWhere('ur.targetUser = :user AND ur.user IN (:matches)')
            ->setParameter('user', $userId)
            ->setParameter('matches', $matches)
            ->getQuery()
            ->getResult();
    }


//    /**
//     * @return UserRelation[] Returns an array of UserRelation objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('u')
//            ->andWhere('u.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('u.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?UserRelation
//    {
//        return $this->createQueryBuilder('u')
//            ->andWhere('u.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
