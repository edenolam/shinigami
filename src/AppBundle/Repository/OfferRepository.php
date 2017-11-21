<?php

namespace AppBundle\Repository;
use Doctrine\ORM\Query\Expr\Join;

/**
 * OfferRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class OfferRepository extends \Doctrine\ORM\EntityRepository
{
    public function findUsableOffersByCustomer($type, $card, $count)
    {
        return $this->createQueryBuilder('o')
            ->leftJoin('o.cardsOffers', "co", Join::WITH, "co.card = :card")
            ->where('o.offerType = :type')
            ->andWhere('o.count <= :count')
            ->andWhere('o.isActive = true')
            ->setParameters(array(
                "card" => $card,
                "type" => $type,
                "count" => $count
            ))
            ->getQuery()
            ->getResult();
    }
}
