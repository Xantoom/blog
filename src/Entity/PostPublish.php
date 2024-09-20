<?php

namespace App\Entity;

use App\Repository\PostPublishRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PostPublishRepository::class)]
#[ORM\Table(name: 'post_publishes')]
class PostPublish
{
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'SEQUENCE')]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: 'boolean', nullable: false)]
    private ?bool $published = true;

    #[ORM\Column(type: 'datetime_immutable', nullable: false)]
    private ?\DateTimeImmutable $publishedAt;

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'postsPublished')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $publishedBy = null;

    #[ORM\ManyToOne(targetEntity: Post::class, inversedBy: 'publishes')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Post $post = null;

    public function __construct()
    {
        $this->publishedAt = new \DateTimeImmutable();
    }

    public function __toString(): string
    {
        return 'PostPublish n°'.$this->id;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPublished(): ?bool
    {
        return $this->published;
    }

    public function setPublished(bool $published): self
    {
        $this->published = $published;

        return $this;
    }

    public function getPublishedAt(): ?\DateTimeImmutable
    {
        return $this->publishedAt;
    }

    public function setPublishedAt(\DateTimeImmutable $publishedAt): self
    {
        $this->publishedAt = $publishedAt;

        return $this;
    }

    public function getPost(): ?Post
    {
        return $this->post;
    }

    public function setPost(Post $post): self
    {
        $this->post = $post;

        return $this;
    }

    public function getPublishedBy(): ?User
    {
        return $this->publishedBy;
    }

    public function setPublishedBy(User $publishedBy): self
    {
        $this->publishedBy = $publishedBy;

        return $this;
    }

}
