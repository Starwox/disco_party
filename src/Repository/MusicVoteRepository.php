<?php

namespace App\Repository;

use App\Entity\MusicVote;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method MusicVote|null find($id, $lockMode = null, $lockVersion = null)
 * @method MusicVote|null findOneBy(array $criteria, array $orderBy = null)
 * @method MusicVote[]    findAll()
 * @method MusicVote[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MusicVoteRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, MusicVote::class);
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function add(MusicVote $entity, bool $flush = true): void
    {
        $this->_em->persist($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function remove(MusicVote $entity, bool $flush = true): void
    {
        $this->_em->remove($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function findRoomMusic($code) {
        return $this->createQueryBuilder('m')
            ->innerJoin('m.room', 'r')
            ->andWhere('r.code = :code')
            ->setParameter('code', $code)
            ->orderBy('m.id', 'DESC')
            ->setMaxResults(4)
            ->getQuery()
            ->getResult()
            ;
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function findMusicByCode($code) {
        return $this->createQueryBuilder('m')
            ->andWhere('m.codeRound = :code')
            ->setParameter('code', $code)
            ->orderBy('m.vote', 'DESC')
            ->setMaxResults(1)
            ->getQuery()
            ->getResult()
            ;
    }

    // /**
    //  * @return MusicVote[] Returns an array of MusicVote objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('m.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?MusicVote
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
