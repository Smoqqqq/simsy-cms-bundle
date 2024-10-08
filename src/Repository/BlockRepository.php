<?php

namespace Smoq\SimsyCMS\Repository;

use  Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Smoq\SimsyCMS\Contracts\BlockInterface;
use Smoq\SimsyCMS\Entity\Block;

/**
 * @extends ServiceEntityRepository<Block>
 */
class BlockRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Block::class);
    }

    /**
     * returns the following blocks of a given block
     * @param BlockInterface $block the first block to select
     * @return BlockInterface
     */
    public function findFollowingBlocks(BlockInterface $block) {
        return $this->createQueryBuilder('b')
            ->where('b.section = :section')
            ->andWhere('b.position >= :position')
            ->setParameter('section', $block->getSection())
            ->setParameter('position', $block->getPosition())
            ->orderBy('b.position', 'ASC')
            ->getQuery()
            ->getResult();
    }
}