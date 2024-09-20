<?php

namespace App\Repository;

use App\Entity\PostPublish;
use Doctrine\Bundle\DoctrineBundle\Repository\LazyServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends LazyServiceEntityRepository<PostPublish>
 *
 * @method PostPublish|null find($id, $lockMode = null, $lockVersion = null)
 * @method PostPublish|null findOneBy(array $criteria, array $orderBy = null)
 * @method PostPublish[]    findAll()
 * @method PostPublish[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PostPublishRepository extends LazyServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, PostPublish::class);
    }

    public function save(PostPublish $postPublish): void
    {
        $this->getEntityManager()->persist($postPublish);
        $this->getEntityManager()->flush();
    }

    public function remove(PostPublish $postPublish): void
    {
        $this->getEntityManager()->remove($postPublish);
        $this->getEntityManager()->flush();
    }
}
