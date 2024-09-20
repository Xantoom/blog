<?php

namespace App\Repository;

use App\Entity\CommentDeletion;
use Doctrine\Bundle\DoctrineBundle\Repository\LazyServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends LazyServiceEntityRepository<CommentDeletion>
 *
 * @method CommentDeletion|null find($id, $lockMode = null, $lockVersion = null)
 * @method CommentDeletion|null findOneBy(array $criteria, array $orderBy = null)
 * @method CommentDeletion[]    findAll()
 * @method CommentDeletion[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CommentDeletionRepository extends LazyServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CommentDeletion::class);
    }

    public function save(CommentDeletion $commentDeletion): void
    {
        $this->getEntityManager()->persist($commentDeletion);
        $this->getEntityManager()->flush();
    }

    public function remove(CommentDeletion $commentDeletion): void
    {
        $this->getEntityManager()->remove($commentDeletion);
        $this->getEntityManager()->flush();
    }

}
