<?php

namespace Smart\ParameterBundle\Repository;

use Doctrine\ORM\EntityRepository;
use Smart\ParameterBundle\Entity\Parameter;

/**
 * @author Mathieu Ducrot <mathieu.ducrot@smartbooster.io>
 *
 * @extends \Doctrine\ORM\EntityRepository<Parameter>
 */
class ParameterRepository extends EntityRepository
{
    /**
     * @return Parameter[]
     */
    public function findAllByCode(): array
    {
        return $this->createQueryBuilder('p', 'p.code')
            ->getQuery()
            ->getResult()
        ;
    }
}
