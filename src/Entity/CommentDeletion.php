<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'comment_deletions')]
class CommentDeletion
{
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'SEQUENCE')]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: 'datetime_immutable', nullable: false)]
    private ?\DateTimeImmutable $deletedAt;

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'commentsDeleted')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $deletedBy = null;

    #[ORM\ManyToOne(targetEntity: Comment::class)]
    #[ORM\JoinColumn(nullable: false)]
    private ?Comment $comment = null;

    public function __construct()
    {
        $this->deletedAt = new \DateTimeImmutable();
    }

    public function __toString(): string
    {
        return 'CommentDeletion n°' . $this->id;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getDeletedAt(): \DateTimeImmutable
    {
        return $this->deletedAt;
    }

    public function setDeletedAt(\DateTimeImmutable $deletedAt): void
    {
        $this->deletedAt = $deletedAt;
    }

    public function getDeletedBy(): User
    {
        return $this->deletedBy;
    }

    public function setDeletedBy(User $deletedBy): void
    {
        $this->deletedBy = $deletedBy;
    }

    public function getComment(): Comment
    {
        return $this->comment;
    }

    public function setComment(Comment $comment): void
    {
        $this->comment = $comment;
    }

}
