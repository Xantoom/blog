<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'comment_edits')]
class CommentEdit
{
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'SEQUENCE')]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: 'text', nullable: false)]
    private ?string $content = null;

    #[ORM\Column(type: 'datetime_immutable', nullable: false)]
    private ?\DateTimeImmutable $editedAt;

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'commentEdits')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $editedBy = null;

    #[ORM\ManyToOne(targetEntity: Comment::class)]
    #[ORM\JoinColumn(nullable: false)]
    private ?Comment $comment = null;

    public function __construct()
    {
        $this->editedAt = new \DateTimeImmutable();
    }

    public function __toString(): string
    {
        return 'CommentEdit n°' . $this->id;
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

    public function getEditedAt(): \DateTimeImmutable
    {
        return $this->editedAt;
    }

	public function setEditedAt(\DateTimeImmutable $editedAt): self
	{
		$this->editedAt = $editedAt;

		return $this;
	}

    public function getEditedBy(): User
    {
        return $this->editedBy;
    }

    public function setEditedBy(User $editedBy): self
    {
        $this->editedBy = $editedBy;

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
