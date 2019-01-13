<?php

namespace App\Repository;

use App\Entity\Article;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * Class ArticleRepository
 */
class ArticleRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Article::class);
    }

    /**
     * @param $currentPage
     * @param $paginationLimit
     * @return \Doctrine\ORM\Tools\Pagination\Paginator
     */
    public function getAllPostsPaginate($currentPage, $paginationLimit)
    {

        $query = $this->createQueryBuilder('p')
            //->orderBy('p.title', 'DESC')
            ->getQuery();

        $paginator = $this->paginate($query, $currentPage, $paginationLimit);

        return $paginator;
    }

    /**
     * Paginator Helper
     *
     * Pass through a query object, current page & limit
     * the offset is calculated from the page and limit
     * returns an `Paginator` instance, which you can call the following on:
     *
     *     $paginator->getIterator()->count() # Total fetched (ie: `5` posts)
     *     $paginator->count() # Count of ALL posts (ie: `20` posts)
     *     $paginator->getIterator() # ArrayIterator
     *
     *
     * @param $dql
     * @param $page
     * @param $paginationLimit
     * @return \Doctrine\ORM\Tools\Pagination\Paginator
     */
    protected function paginate($dql, $page, $paginationLimit)
    {
        $paginator = new Paginator($dql);

        $paginator->getQuery()
            ->setFirstResult($paginationLimit * ($page - 1)) // Offset
            ->setMaxResults($paginationLimit);// Limit
        //->getResult();

        return $paginator;
    }

    // /**
    //  * @return Article[] Returns an array of Article objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('a.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Article
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
