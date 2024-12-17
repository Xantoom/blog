<?php

namespace App\Entity;

use App\enums\Roles;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'users')]
class User
{
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'SEQUENCE')]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 127, nullable: false)]
    private ?string $firstname = null;

    #[ORM\Column(length: 127, nullable: false)]
    private ?string $lastname = null;

    #[ORM\Column(length: 127, unique: true, nullable: false)]
    private ?string $email = null;

    #[ORM\Column(length: 255, nullable: false)]
    private ?string $password = null;

    #[ORM\Column(type: 'json', nullable: false)]
    private ?array $roles = [Roles::ROLE_USER];

    #[ORM\Column(type: 'boolean', nullable: false)]
    private ?bool $active = true;

    #[ORM\Column(nullable: false)]
    private ?string $profilePicture = 'https://cdn-icons-png.flaticon.com/512/3541/3541871.png';

    #[ORM\Column(type: 'datetime_immutable', nullable: false)]
    private ?\DateTimeImmutable $createdAt;

    #[ORM\OneToMany(targetEntity: Post::class, mappedBy: 'createdBy')]
    private Collection $postsCreated;

    #[ORM\OneToMany(targetEntity: PostPublish::class, mappedBy: 'publishedBy')]
    private Collection $postsPublished;

    #[ORM\OneToMany(targetEntity: PostEdit::class, mappedBy: 'editedBy')]
    private Collection $postsEdited;

    #[ORM\OneToMany(targetEntity: Comment::class, mappedBy: 'createdBy')]
    private Collection $comments;

    #[ORM\OneToMany(targetEntity: CommentApproval::class, mappedBy: 'approvedBy')]
    private Collection $commentApprovals;

    #[ORM\OneToMany(targetEntity: CommentDeletion::class, mappedBy: 'deletedBy')]
    private Collection $commentsDeleted;

    #[ORM\OneToMany(targetEntity: CommentEdit::class, mappedBy: 'editedBy')]
    private Collection $commentsEdited;

    #[ORM\OneToMany(targetEntity: AuthToken::class, mappedBy: 'user')]
    #[ORM\OrderBy(['createdAt' => 'DESC'])]
    private Collection $authTokens;

    public function __construct()
    {
        $this->createdAt = new \DateTimeImmutable();

        $this->postsCreated = new ArrayCollection();
        $this->postsPublished = new ArrayCollection();
        $this->postsEdited = new ArrayCollection();

        $this->comments = new ArrayCollection();
        $this->commentApprovals = new ArrayCollection();
        $this->commentsDeleted = new ArrayCollection();
        $this->commentsEdited = new ArrayCollection();

        $this->authTokens = new ArrayCollection();
    }

    public function __toString(): string
    {
        return 'User n°' . $this->id;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFirstname(): ?string
    {
        return $this->firstname;
    }

    public function setFirstname(string $firstname): self
    {
        $this->firstname = $firstname;

        return $this;
    }

    public function getLastname(): ?string
    {
        return $this->lastname;
    }

    public function setLastname(string $lastname): self
    {
        $this->lastname = $lastname;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = password_hash($password, PASSWORD_DEFAULT);

        return $this;
    }

    public function getRoles(): ?array
    {
		$roles = $this->roles;
		if (!in_array(Roles::ROLE_USER->value, $roles, true)) {
			$roles[] = Roles::ROLE_USER->value;
		}
        return $roles;
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    public function getActive(): ?bool
    {
        return $this->active;
    }

    public function setActive(bool $active): self
    {
        $this->active = $active;

        return $this;
    }

    public function getProfilePicture(): ?string
    {
        return $this->profilePicture;
    }

    public function setProfilePicture(string $profilePicture): self
    {
        $this->profilePicture = $profilePicture;

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

    public function getFullname(): ?string
    {
        return $this->firstname . ' ' . $this->lastname;
    }

    public function getPostsCreated(): Collection
    {
        return $this->postsCreated;
    }

    public function addPostCreated(Post $post): self
    {
        if (!$this->postsCreated->contains($post)) {
            $this->postsCreated[] = $post;
            $post->setCreatedBy($this);
        }

        return $this;
    }

    public function getPostsPublished(): Collection
    {
        return $this->postsPublished;
    }

    public function addPostPublished(PostPublish $postPublish): self
    {
        if (!$this->postsPublished->contains($postPublish)) {
            $this->postsPublished[] = $postPublish;
            $postPublish->setPublishedBy($this);
        }

        return $this;
    }

    public function getPostsEdited(): Collection
    {
        return $this->postsEdited;
    }

    public function addPostEdited(PostEdit $postEdit): self
    {
        if (!$this->postsEdited->contains($postEdit)) {
            $this->postsEdited[] = $postEdit;
            $postEdit->setEditedBy($this);
        }

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
            $comment->setCreatedBy($this);
        }

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
            $commentApproval->setApprovedBy($this);
        }

        return $this;
    }

    public function getCommentsDeleted(): Collection
    {
        return $this->commentsDeleted;
    }

    public function addCommentDeleted(CommentDeletion $commentDeletion): self
    {
        if (!$this->commentsDeleted->contains($commentDeletion)) {
            $this->commentsDeleted[] = $commentDeletion;
            $commentDeletion->setDeletedBy($this);
        }

        return $this;
    }

    public function getCommentsEdited(): Collection
    {
        return $this->commentsEdited;
    }

    public function addCommentEdited(CommentEdit $commentEdit): self
    {
        if (!$this->commentsEdited->contains($commentEdit)) {
            $this->commentsEdited[] = $commentEdit;
            $commentEdit->setEditedBy($this);
        }

        return $this;
    }

    public function getAuthTokens(): Collection
    {
        return $this->authTokens;
    }

    public function addAuthToken(AuthToken $authToken): self
    {
        if (!$this->authTokens->contains($authToken)) {
            $this->authTokens[] = $authToken;
            $authToken->setUser($this);
        }

        return $this;
    }

    public function removeAuthToken(AuthToken $authToken): self
    {
        if ($this->authTokens->removeElement($authToken)) {
            // set the owning side to null (unless already changed)
            if ($authToken->getUser() === $this) {
                $authToken->setUser(null);
            }
        }

        return $this;
    }
}
