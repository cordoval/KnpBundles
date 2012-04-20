<?php

namespace Knp\Bundle\KnpBundlesBundle\Repository;

use Knp\Bundle\KnpBundlesBundle\Entity\Dependency;
use Doctrine\ORM\EntityRepository;

class DependencyRepository extends EntityRepository
{
    /**
     * Find dependency with given value or create new one
     * 
     * @return Dependency
     */
    public function findOrCreateOne($value)
    {
        $dependency = $this->findOneByValue($value);

        if (!$dependency) {
            $class = $this->getClassName();
            $dependency = new $class;
            $dependency->setValue($value);
        }

        return $dependency;
    }
}
