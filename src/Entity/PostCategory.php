<?php

namespace App\Entity;

use App\Repository\PostCategoryRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PostCategoryRepository::class)]
#[ORM\Table(name: 'post_categories')]
class PostCategory
{
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'SEQUENCE')]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 64, nullable: false)]
    private ?string $name = null;

    #[ORM\OneToMany(targetEntity: Post::class, mappedBy: 'category')]
    private Collection $posts;

    #[ORM\OneToMany(targetEntity: PostEdit::class, mappedBy: 'category')]
    private Collection $postEdits;

    public function __construct()
    {
        $this->posts = new ArrayCollection();
    }

    public function __toString(): string
    {
        return 'PostCategory: n°' . $this->id;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getPosts(): Collection
    {
        return $this->posts;
    }

    public function addPost(Post $post): self
    {
        if (!$this->posts->contains($post)) {
            $this->posts[] = $post;
            $post->setCategory($this);
        }

        return $this;
    }

    public function removePost(Post $post): self
    {
        if ($this->posts->removeElement($post)) {
            // set the owning side to null (unless already changed)
            if ($post->getCategory() === $this) {
                $post->setCategory(null);
            }
        }

        return $this;
    }

    public function getPostEdits(): Collection
    {
        return $this->postEdits;
    }

    public function addPostEdit(PostEdit $postEdit): self
    {
        if (!$this->postEdits->contains($postEdit)) {
            $this->postEdits[] = $postEdit;
            $postEdit->setCategory($this);
        }

        return $this;
    }

    public function removePostEdit(PostEdit $postEdit): self
    {
        if ($this->postEdits->removeElement($postEdit)) {
            // set the owning side to null (unless already changed)
            if ($postEdit->getCategory() === $this) {
                $postEdit->setCategory(null);
            }
        }

        return $this;
    }
}
