<?php

namespace App\Repository;

use App\Entity\Genre;
use App\Entity\Movie;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query\ResultSetMappingBuilder;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Movie>
 *
 * @method Movie|null find($id, $lockMode = null, $lockVersion = null)
 * @method Movie|null findOneBy(array $criteria, array $orderBy = null)
 * @method Movie[]    findAll()
 * @method Movie[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MovieRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Movie::class);
    }

    public function add(Movie $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Movie $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function findLikeTitle(string $title): array
    {
        //$rsm  = new ResultSetMappingBuilder($this->getEntityManager());
        //$rsm->addRootEntityFromClassMetadata('App\Entity\Movie', 'm');
        //
        //return $this
        //    ->getEntityManager()
        //    ->createNativeQuery('SELECT * FROM movie WHERE title LIKE `%'. $title. '%`',
        //    $rsm
        //)
        //    ->getResult();

        //return $this
        //    ->getEntityManager()
        //    ->createQuery(
        //    'SELECT m,g
        //        FROM App\Entity\Movie m
        //        JOIN App\Entity\Genre g WITH m.poster = g.poster
        //        WHERE m.title LIKE `%:title%`'
        //)
        //    ->setParameter('title', $title)
        //    ->getResult();

        $qb = $this->createQueryBuilder('m');

        return $qb->andWhere(
            $qb->expr()->like(
                    'm.title',
                    $qb->expr()->literal("%:title%")
                )
            )
            ->setParameter('title', $title)
            ->getQuery()
            ->getResult();
    }

//    /**
//     * @return Movie[] Returns an array of Movie objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('m')
//            ->andWhere('m.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('m.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Movie
//    {
//        return $this->createQueryBuilder('m')
//            ->andWhere('m.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
