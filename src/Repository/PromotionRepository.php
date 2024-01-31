<?php

namespace App\Repository;

use App\Entity\Product;
use App\Entity\Promotion;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Promotion>
 *
 * @method Promotion|null find($id, $lockMode = null, $lockVersion = null)
 * @method Promotion|null findOneBy(array $criteria, array $orderBy = null)
 * @method Promotion[]    findAll()
 * @method Promotion[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PromotionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Promotion::class);
    }

    /**
     * @return Promotion[] Returns an array of Promotion objects
     */
    public function findValidForProduct(Product $product, \DateTimeInterface $requestDate): array
    {
        return $this->createQueryBuilder('p')
            ->innerJoin('p.productPromotions', 'pp')
            ->andWhere('pp.product = :product')
            ->andWhere('pp.validTo > :requestDate OR pp.validTo IS NULL')
            ->setParameter('product', $product)
            ->setParameter('requestDate', $requestDate)
            ->getQuery()
            ->getResult()
        ;
    }

//    public function findOneBySomeField($value): ?Promotion
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
