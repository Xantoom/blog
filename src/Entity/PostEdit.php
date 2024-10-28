<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'post_edits')]
class PostEdit
{
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'SEQUENCE')]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $title = null;

    #[ORM\Column(type: 'text', nullable: true)]
    private ?string $content = null;

    #[ORM\Column(type: 'text', nullable: true)]
    private ?string $preview = null;

    #[ORM\Column(type: 'text', nullable: true)]
    private ?string $banner = null;

    #[ORM\Column(type: 'datetime_immutable', nullable: false)]
    private ?\DateTimeImmutable $editedAt;

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'postsEdited')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $editedBy = null;

    #[ORM\ManyToOne(targetEntity: PostCategory::class, inversedBy: 'postEdits')]
    #[ORM\JoinColumn(nullable: true)]
    private ?PostCategory $category = null;

    #[ORM\ManyToOne(targetEntity: Post::class, inversedBy: 'edits')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Post $post = null;

    public function __construct()
    {
        $this->editedAt = new \DateTimeImmutable();
    }

    public function __toString(): string
    {
        return 'PostEdit n°' . $this->id;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(?string $title): static
    {
        $this->title = $title;

        return $this;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(?string $content): static
    {
        $this->content = $content;

        return $this;
    }

    public function getPreview(): ?string
    {
        return $this->preview;
    }

    public function setPreview(?string $preview): static
    {
        $this->preview = $preview;

        return $this;
    }

    public function getBanner(): ?string
    {
        return $this->banner;
    }

    public function setBanner(?string $banner): static
    {
        $this->banner = $banner;

        return $this;
    }

    public function getEditedAt(): ?\DateTimeImmutable
    {
        return $this->editedAt;
    }

    public function getEditedBy(): ?User
    {
        return $this->editedBy;
    }

    public function setEditedBy(User $editedBy): static
    {
        $this->editedBy = $editedBy;

        return $this;
    }

    public function getCategory(): ?PostCategory
    {
        return $this->category;
    }

    public function setCategory(?PostCategory $category): static
    {
        $this->category = $category;

        return $this;
    }

    public function getPost(): ?Post
    {
        return $this->post;
    }

    public function setPost(Post $post): static
    {
        $this->post = $post;

        return $this;
    }
}
