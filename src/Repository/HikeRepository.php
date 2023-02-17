<?php

namespace App\Repository;

use App\Entity\Hike;
use App\Entity\User;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

/**
 * @extends ServiceEntityRepository<Hike>
 *
 * @method Hike|null find($id, $lockMode = null, $lockVersion = null)
 * @method Hike|null findOneBy(array $criteria, array $orderBy = null)
 * @method Hike[]    findAll()
 * @method Hike[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class HikeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Hike::class);
    }

    public function save(Hike $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Hike $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function findAllByUserClub(User $user)
    {
        // get the first club of the user by filtering the clubs of the user
        // and then get the hikes of the club
        $result = $this->createQueryBuilder('h')
            ->join('h.club', 'c')
            ->where('c.id = :clubId')
            ->setParameter('clubId', $user->getClubs()[0]->getId())
            ->getQuery()
            ->getResult();
        return $result;
    }

//    /**
//     * @return Hike[] Returns an array of Hike objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('h')
//            ->andWhere('h.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('h.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Hike
//    {
//        return $this->createQueryBuilder('h')
//            ->andWhere('h.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
