<?php

namespace LocationBundle\Repository;

/**
 * LocationRepository
 */
class LocationRepository extends \Doctrine\ORM\EntityRepository
{
    public function findByPrefix($term) {

        $qb = $this->createQueryBuilder('l')
            ->where("l.name LIKE :term")
            ->setMaxResults(5)
            ->setParameter("term", "%" . $term . "%")
            ->orderBy("l.score", "DESC")
            ->getQuery();

        return $qb->getArrayResult();
    }
}
