<?php

namespace App\Repository;

use App\Entity\CommentEdit;
use Doctrine\Bundle\DoctrineBundle\Repository\LazyServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends LazyServiceEntityRepository<CommentEdit>
 *
 * @method CommentEdit|null find($id, $lockMode = null, $lockVersion = null)
 * @method CommentEdit|null findOneBy(array $criteria, array $orderBy = null)
 * @method CommentEdit[]    findAll()
 * @method CommentEdit[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CommentEditRepository extends LazyServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CommentEdit::class);
    }

    public function save(CommentEdit $commentEdit): void
    {
        $this->getEntityManager()->persist($commentEdit);
        $this->getEntityManager()->flush();
    }

    public function remove(CommentEdit $commentEdit): void
    {
        $this->getEntityManager()->remove($commentEdit);
        $this->getEntityManager()->flush();
    }
}
