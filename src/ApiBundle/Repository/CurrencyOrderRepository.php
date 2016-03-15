<?php

namespace ApiBundle\Repository;

use Doctrine\ORM\EntityRepository;

/**
 * CurrencyOrderRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class CurrencyOrderRepository extends EntityRepository
{

    public function getOrders()
    {
        $results = $this->getEntityManager()
                ->createQuery(
                        'SELECT o, a.discountAmount '
                        . 'FROM ApiBundle:CurrencyOrder o '
                        . 'LEFT JOIN ApiBundle:Additional a With a.id = o.additional '
                        . 'ORDER BY o.id DESC')
                ->getSQL();
//                ->getResult();
        die($results);
        return $results;
    }

}
