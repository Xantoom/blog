<?php

namespace App\Entity;

use App\Repository\CommentApprovalRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CommentApprovalRepository::class)]
#[ORM\Table(name: 'comment_approvals')]
class CommentApproval
{
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'SEQUENCE')]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: 'datetime_immutable', nullable: false)]
    private ?\DateTimeImmutable $approvedAt;

    #[ORM\Column(type: 'boolean', nullable: false)]
    private ?bool $approved = true;

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'commentApprovals')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $approvedBy = null;

    #[ORM\ManyToOne(targetEntity: Comment::class)]
    #[ORM\JoinColumn(nullable: false)]
    private ?Comment $comment = null;

    public function __construct()
    {
        $this->approvedAt = new \DateTimeImmutable();
    }

    public function __toString(): string
    {
        return 'CommentApproval n°' . $this->id;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getApprovedAt(): \DateTimeImmutable
    {
        return $this->approvedAt;
    }

    public function setApprovedAt(\DateTimeImmutable $approvedAt): void
    {
        $this->approvedAt = $approvedAt;
    }

    public function isApproved(): bool
    {
        return $this->approved;
    }

    public function setApproved(bool $approved): void
    {
        $this->approved = $approved;
    }

    public function getApprovedBy(): User
    {
        return $this->approvedBy;
    }

    public function setApprovedBy(User $approvedBy): void
    {
        $this->approvedBy = $approvedBy;
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
