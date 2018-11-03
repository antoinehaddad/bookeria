<?php

namespace App\Repository;

use App\Entity\Tag;
use App\Entity\Book;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

/**
 * @method Book|null find($id, $lockMode = null, $lockVersion = null)
 * @method Book|null findOneBy(array $criteria, array $orderBy = null)
 * @method Book[]    findAll()
 * @method Book[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BookRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Book::class);
    }

    public function findByTag(Tag $tag)
    {
        return $this->createQueryBuilder('b')
            ->join('b.tags', 't')
            ->select('b')
            ->andWhere('t.id = :tag_id')
            ->setParameter('tag_id', $tag->getId())
            ->getQuery();
        ;
    }

    // TODO: findBooks(array $criterias)
    public function findBooks($before, $after, $nationality){
        $beforeDate = new \DateTime($before);
        $afterDate = new \DateTime($after);
        $countryCode = $nationality;

        $queryBuilder = $this->createQueryBuilder('b');
        if($before && $beforeDate instanceof \DateTime){
            $queryBuilder->where("b.createdAt < :before")->setParameter('before', $beforeDate);
        }
        if($after && $afterDate instanceof \DateTime){
            $queryBuilder->AndWhere("b.createdAt > :after")->setParameter('after', $afterDate);
        }

        if($countryCode){
            $queryBuilder->join('App\Entity\Author', 'a', 'WITH', 'b.author=a.id');
            $queryBuilder->AndWhere("a.nationality = :countryCode")->setParameter('countryCode', $countryCode);
        }
        
        $books = $queryBuilder->getQuery()->getResult();
        return $books;
    }
}
