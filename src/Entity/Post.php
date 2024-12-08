<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'posts')]
class Post
{
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'SEQUENCE')]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255, nullable: false)]
    private ?string $title = null;

    #[ORM\Column(type: 'text', nullable: false)]
    private ?string $content = null;

    #[ORM\Column(type: 'text', nullable: false)]
    private ?string $preview = null;

    #[ORM\Column(type: 'text', nullable: false)]
    private ?string $banner = 'default.jpg';

    #[ORM\Column(type: 'integer', nullable: false)]
    private ?int $views = 0;

    #[ORM\Column(type: 'datetime_immutable', nullable: false)]
    private ?\DateTimeImmutable $createdAt;

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'postsCreated')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $createdBy = null;

    #[ORM\ManyToOne(targetEntity: PostCategory::class, inversedBy: 'posts')]
    #[ORM\JoinColumn(nullable: false)]
    private ?PostCategory $category = null;

    #[ORM\OneToMany(targetEntity: Comment::class, mappedBy: 'post')]
    #[ORM\OrderBy(['createdAt' => 'DESC'])]
    private Collection $comments;

    #[ORM\OneToMany(targetEntity: PostEdit::class, mappedBy: 'post')]
    #[ORM\OrderBy(['editedAt' => 'DESC'])]
    private Collection $edits;

    #[ORM\OneToMany(targetEntity: PostPublish::class, mappedBy: 'post', cascade: ['persist', 'remove'])]
    #[ORM\OrderBy(['publishedAt' => 'DESC'])]
    private Collection $publishes;

    public function __construct()
    {
        $this->createdAt = new \DateTimeImmutable();

        $this->comments = new ArrayCollection();
        $this->edits = new ArrayCollection();
        $this->publishes = new ArrayCollection();
    }

    public function __toString(): string
    {
        return 'Post n°' . $this->id;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(string $content): self
    {
        $this->content = $content;

        return $this;
    }

    public function getPreview(): ?string
    {
        return $this->preview;
    }

    public function setPreview(string $preview): self
    {
        $this->preview = $preview;

        return $this;
    }

    public function getBanner(): ?string
    {
        return $this->banner;
    }

    public function setBanner(string $banner): self
    {
        $this->banner = $banner;

        return $this;
    }

    public function getViews(): ?int
    {
        return $this->views;
    }

    public function setViews(int $views): self
    {
        $this->views = $views;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getCreatedBy(): ?User
    {
        return $this->createdBy;
    }

    public function setCreatedBy(User $createdBy): self
    {
        $this->createdBy = $createdBy;

        return $this;
    }

    public function getCategory(): ?PostCategory
    {
        return $this->category;
    }

    public function setCategory(?PostCategory $category): self
    {
        $this->category = $category;

        return $this;
    }

    public function getComments(): Collection
    {
        return $this->comments;
    }

    public function addComment(Comment $comment): self
    {
        if (!$this->comments->contains($comment)) {
            $this->comments[] = $comment;
            $comment->setPost($this);
        }

        return $this;
    }

    public function removeComment(Comment $comment): self
    {
        if ($this->comments->removeElement($comment)) {
            // set the owning side to null (unless already changed)
            if ($comment->getPost() === $this) {
                $comment->setPost(null);
            }
        }

        return $this;
    }

    public function getEdits(): Collection
    {
        return $this->edits;
    }

    public function addEdit(PostEdit $edit): self
    {
        if (!$this->edits->contains($edit)) {
            $this->edits[] = $edit;
            $edit->setPost($this);
        }

        return $this;
    }

    public function removeEdit(PostEdit $edit): self
    {
        if ($this->edits->removeElement($edit)) {
            // set the owning side to null (unless already changed)
            if ($edit->getPost() === $this) {
                $edit->setPost(null);
            }
        }

        return $this;
    }

    public function getPublishes(): Collection
    {
        return $this->publishes;
    }

    public function addPublish(PostPublish $publish): self
    {
        if (!$this->publishes->contains($publish)) {
            $this->publishes[] = $publish;
            $publish->setPost($this);
        }

        return $this;
    }

    public function removePublish(PostPublish $publish): self
    {
        if ($this->publishes->removeElement($publish)) {
            // set the owning side to null (unless already changed)
            if ($publish->getPost() === $this) {
                $publish->setPost(null);
            }
        }

        return $this;
    }
}
