<?php
// Src\Service\DatabaseTablePrefix.php

namespace Smoq\SimsyCMS;

use Doctrine\Bundle\DoctrineBundle\Attribute\AsDoctrineListener;
use \Doctrine\ORM\Event\LoadClassMetadataEventArgs;
use Doctrine\ORM\Events;

/**
 * This class is used to prefix all table names in the database with a given prefix. ('smisy_' in this case)
 * https://www.doctrine-project.org/projects/doctrine-orm/en/2.14/cookbook/sql-table-prefixes.html
 */
#[AsDoctrineListener(Events::loadClassMetadata)]
readonly class EntityTablePrefixer {
    public function __construct(private string $prefix)
    {
    }

    public function loadClassMetadata(LoadClassMetadataEventArgs $eventArgs)
    {
        $classMetadata = $eventArgs->getClassMetadata();

        if (!$classMetadata->isInheritanceTypeSingleTable() || $classMetadata->getName() === $classMetadata->rootEntityName) {
            $classMetadata->setPrimaryTable([
                'name' => $this->prefix . $classMetadata->getTableName()
            ]);
        }

        foreach ($classMetadata->getAssociationMappings() as $fieldName => $mapping) {
            if ($mapping['type'] == \Doctrine\ORM\Mapping\ClassMetadata::MANY_TO_MANY && $mapping['isOwningSide']) {
                $mappedTableName = $mapping['joinTable']['name'];
                $classMetadata->associationMappings[$fieldName]['joinTable']['name'] = $this->prefix . $mappedTableName;
            }
        }
    }
}