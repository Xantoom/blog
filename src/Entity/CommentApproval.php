<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
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

    #[ORM\OneToOne(targetEntity: Comment::class)]
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

    public function setApprovedAt(\DateTimeImmutable $approvedAt): self
    {
        $this->approvedAt = $approvedAt;

		return $this;
    }

    public function isApproved(): bool
    {
        return $this->approved;
    }

    public function setApproved(bool $approved): self
    {
        $this->approved = $approved;

		return $this;
    }

    public function getApprovedBy(): User
    {
        return $this->approvedBy;
    }

    public function setApprovedBy(User $approvedBy): self
    {
        $this->approvedBy = $approvedBy;

		return $this;
    }

    public function getComment(): Comment
    {
        return $this->comment;
    }

    public function setComment(Comment $comment): self
    {
        $this->comment = $comment;

		return $this;
    }

}
