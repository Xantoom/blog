<?php

namespace App\Service;

use App\Entity\AuthToken;
use App\Entity\Comment;
use App\Entity\CommentApproval;
use App\Entity\CommentDeletion;
use App\Entity\CommentEdit;
use App\Entity\Post;
use App\Entity\PostCategory;
use App\Entity\PostEdit;
use App\Entity\PostPublish;
use App\Entity\User;
use Doctrine\DBAL\DriverManager;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\ORMSetup;

class RepositoryService
{
    private EntityManager $entityManager;
    private $_entityManager = null;

    public function __construct()
    {
        $this->entityManager = $this->getEntityManager();
    }

    public function getEntityManager(): EntityManager
    {
        // if (!$this->_entityManager) {
        //     $this->_entityManager = new EntityManager();
        // }
        //
        // return $this->_entityManager;
        global $entityManager;
        return $entityManager;
    }

    public function getAuthTokenRepository(): EntityRepository
    {
        return $this->entityManager->getRepository(AuthToken::class);
    }

    public function getCommentApprovalRepository(): EntityRepository
    {
        return $this->entityManager->getRepository(CommentApproval::class);
    }

    public function getCommentDeletionRepository(): EntityRepository
    {
        return $this->entityManager->getRepository(CommentDeletion::class);
    }

    public function getCommentEditRepository(): EntityRepository
    {
        return $this->entityManager->getRepository(CommentEdit::class);
    }

    public function getCommentRepository(): EntityRepository
    {
        return $this->entityManager->getRepository(Comment::class);
    }

    public function getPostCategoryRepository(): EntityRepository
    {
        return $this->entityManager->getRepository(PostCategory::class);
    }

    public function getPostEditRepository(): EntityRepository
    {
        return $this->entityManager->getRepository(PostEdit::class);
    }

    public function getPostPublishRepository(): EntityRepository
    {
        return $this->entityManager->getRepository(PostPublish::class);
    }

    public function getPostRepository(): EntityRepository
    {
        return $this->entityManager->getRepository(Post::class);
    }

    public function getUserRepository(): EntityRepository
    {
        return $this->entityManager->getRepository(User::class);
    }
}
