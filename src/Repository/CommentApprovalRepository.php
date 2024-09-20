<?php

namespace App\Repository;

use App\Entity\CommentApproval;
use Doctrine\Bundle\DoctrineBundle\Repository\LazyServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends LazyServiceEntityRepository<CommentApproval>
 *
 * @method CommentApproval|null find($id, $lockMode = null, $lockVersion = null)
 * @method CommentApproval|null findOneBy(array $criteria, array $orderBy = null)
 * @method CommentApproval[]    findAll()
 * @method CommentApproval[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CommentApprovalRepository extends LazyServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CommentApproval::class);
    }

    public function save(CommentApproval $commentApproval): void
    {
        $this->getEntityManager()->persist($commentApproval);
        $this->getEntityManager()->flush();
    }

    public function remove(CommentApproval $commentApproval): void
    {
        $this->getEntityManager()->remove($commentApproval);
        $this->getEntityManager()->flush();
    }

}
