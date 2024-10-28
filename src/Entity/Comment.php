<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'comments')]
class Comment
{
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'SEQUENCE')]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: 'text', nullable: false)]
    private ?string $content = null;

    #[ORM\Column(type: 'datetime_immutable', nullable: false)]
    private ?\DateTimeImmutable $createdAt;

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'comments')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $createdBy = null;

    #[ORM\ManyToOne(targetEntity: Post::class, inversedBy: 'comments')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Post $post = null;

    #[ORM\OneToMany(targetEntity: CommentApproval::class, mappedBy: 'comment')]
    private Collection $commentApprovals;

    #[ORM\OneToMany(targetEntity: CommentDeletion::class, mappedBy: 'comment')]
    private Collection $commentsDeleted;

    #[ORM\OneToMany(targetEntity: CommentEdit::class, mappedBy: 'comment')]
    private Collection $commentEdits;

    public function __construct()
    {
        $this->createdAt = new \DateTimeImmutable();

        $this->commentApprovals = new ArrayCollection();
        $this->commentsDeleted = new ArrayCollection();
        $this->commentEdits = new ArrayCollection();
    }

    public function __toString(): string
    {
        return 'Comment n°' . $this->id;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getContent(): string
    {
        return $this->content;
    }

    public function setContent(string $content): self
    {
        $this->content = $content;

        return $this;
    }

    public function getCreatedAt(): \DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getCreatedBy(): User
    {
        return $this->createdBy;
    }

    public function setCreatedBy(User $createdBy): self
    {
        $this->createdBy = $createdBy;

        return $this;
    }

    public function getPost(): Post
    {
        return $this->post;
    }

    public function setPost(Post $post): self
    {
        $this->post = $post;

        return $this;
    }

    public function getCommentApprovals(): Collection
    {
        return $this->commentApprovals;
    }

    public function addCommentApproval(CommentApproval $commentApproval): self
    {
        if (!$this->commentApprovals->contains($commentApproval)) {
            $this->commentApprovals[] = $commentApproval;
            $commentApproval->setComment($this);
        }

        return $this;
    }

    public function getCommentsDeleted(): Collection
    {
        return $this->commentsDeleted;
    }

    public function addCommentsDeleted(CommentDeletion $commentsDeleted): self
    {
        if (!$this->commentsDeleted->contains($commentsDeleted)) {
            $this->commentsDeleted[] = $commentsDeleted;
            $commentsDeleted->setComment($this);
        }

        return $this;
    }

    public function getCommentEdits(): Collection
    {
        return $this->commentEdits;
    }

    public function addCommentEdit(CommentEdit $commentEdit): self
    {
        if (!$this->commentEdits->contains($commentEdit)) {
            $this->commentEdits[] = $commentEdit;
            $commentEdit->setComment($this);
        }

        return $this;
    }
}
