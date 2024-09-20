<?php

namespace App\Repository;

use App\Entity\PostEdit;
use Doctrine\Bundle\DoctrineBundle\Repository\LazyServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends LazyServiceEntityRepository<PostEdit>
 *
 * @method PostEdit|null find($id, $lockMode = null, $lockVersion = null)
 * @method PostEdit|null findOneBy(array $criteria, array $orderBy = null)
 * @method PostEdit[]    findAll()
 * @method PostEdit[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PostEditRepository extends LazyServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, PostEdit::class);
    }

    public function save(PostEdit $postEdit): void
    {
        $this->getEntityManager()->persist($postEdit);
        $this->getEntityManager()->flush();
    }

    public function remove(PostEdit $postEdit): void
    {
        $this->getEntityManager()->remove($postEdit);
        $this->getEntityManager()->flush();
    }

}
