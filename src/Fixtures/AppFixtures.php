<?php

namespace App\Fixtures;

use App\Entity\Comment;
use App\Entity\CommentApproval;
use App\Entity\CommentDeletion;
use App\Entity\CommentEdit;
use App\Entity\Post;
use App\Entity\PostCategory;
use App\Entity\PostEdit;
use App\Entity\PostPublish;
use App\Entity\User;
use App\enums\Roles;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Faker\Generator;

class AppFixtures implements FixtureInterface
{
    private Generator $faker;
    private ObjectManager $manager;
	private array $persistentUsers = [];
    private array $users = [];
	private array $postCategories = [];
	private array $posts = [];
	private array $postEdits = [];
	private array $postPublishes = [];
	private array $comments = [];
	private array $commentApprovals = [];
	private array $commentDeletions = [];
	private array $commentEdits = [];

    public function load(ObjectManager $manager): void
    {
        $this->faker = Factory::create('fr_FR');
        $this->manager = $manager;

        $this->createUsers();
        $this->createPersistentUsers();

		$this->createPosts();
    }

    private function createUsers(): void
    {
        $nbUsers = 8;
        for ($i = 0; $i < $nbUsers; $i++) {
            $user = new User();
            $user
                ->setEmail($this->faker->email())
                ->setPassword('password')
                ->setFirstname($this->faker->firstName())
                ->setLastname($this->faker->lastName())
                ->setCreatedAt(new \DateTimeImmutable())
                ->setActive($this->faker->boolean(90))
            ;
            $this->manager->persist($user);
            $this->users[] = $user;
        }

		$this->manager->flush();
    }

    private function createPersistentUsers(): void
    {
        // ADMIN
        $admin = new User();
        $admin
            ->setEmail('admin@test.com')
            ->setPassword('password')
            ->setFirstname('Admin')
            ->setLastname('Admin')
            ->setRoles([Roles::ROLE_ADMIN, Roles::ROLE_EDITOR, Roles::ROLE_USER])
        ;
        $this->manager->persist($admin);
        $this->persistentUsers[] = $admin;

        // EDITOR
        $editor = new User();
        $editor
            ->setEmail('editor@test.com')
            ->setPassword('password')
            ->setFirstname('Editor')
            ->setLastname('Editor')
            ->setRoles([Roles::ROLE_EDITOR, Roles::ROLE_USER])
        ;
        $this->manager->persist($editor);
        $this->persistentUsers[] = $editor;

        // USER
        $user = new User();
        $user
            ->setEmail('user@test.com')
            ->setPassword('password')
            ->setFirstname('User')
            ->setLastname('User')
            ->setRoles([Roles::ROLE_USER])
        ;
        $this->manager->persist($user);
        $this->persistentUsers[] = $user;

		$this->manager->flush();
    }

	private function createPosts(): void
	{
		$nbPostsCategory = 3;
		for ($i = 0; $i < $nbPostsCategory; $i++) {
			$postCategory = new PostCategory();
			$postCategory
				->setName($this->faker->word())
			;
			$this->manager->persist($postCategory);
			$this->postCategories[] = $postCategory;
		}

		$this->manager->flush();

		$nbPosts = 12;
		for ($i = 0; $i < $nbPosts; $i++) {
			$post = new Post();
			$post
				->setBanner('https://picsum.photos/1200/300')
				->setCategory($this->postCategories[$this->faker->numberBetween(0, $nbPostsCategory - 1)])
				->setContent($this->faker->paragraphs(5, true))
				->setCreatedAt(\DateTimeImmutable::createFromMutable($this->faker->dateTimeBetween('-6 months')))
				->setCreatedBy($this->persistentUsers[$this->faker->numberBetween(0, 2)])
				->setPreview($this->faker->paragraph())
				->setTitle($this->faker->sentence())
				->setViews($this->faker->numberBetween(0, 1000))
			;
			$this->manager->persist($post);
			$this->posts[] = $post;

			// 20% de chance d'avoir un post édité
			if ($this->faker->boolean(20)) {
				$postEdit = new PostEdit();
				$postEdit
					->setContent('EDITED: ' . $this->faker->paragraphs(5, true))
					->setEditedBy($this->persistentUsers[$this->faker->numberBetween(0, 2)])
					->setPost($post)
				;
				$this->manager->persist($postEdit);
				$this->postEdits[] = $postEdit;
			}

			// 80% de chance d'avoir un post publié
			if ($this->faker->boolean(80)) {
				$postPublish = new PostPublish();
				$postPublish
					->setPost($post)
					->setPublished(true)
					->setPublishedAt(\DateTimeImmutable::createFromMutable($this->faker->dateTimeBetween('-6 months')))
					->setPublishedBy($this->persistentUsers[$this->faker->numberBetween(0, 2)])
				;
				$this->manager->persist($postPublish);
				$this->postPublishes[] = $postPublish;
			}

			// 80% de chance d'avoir un ou plusieurs commentaires
			if ($this->faker->boolean(80)) {
				$this->createComment($post);
			}
		}

		$this->manager->flush();
	}

	private function createComment(Post $post): void
	{
		for ($i = 0; $i < $this->faker->numberBetween(1, 12); $i++) {
			$comment = new Comment();
			$comment
				->setContent($this->faker->paragraph())
				->setCreatedAt(\DateTimeImmutable::createFromMutable($this->faker->dateTimeBetween
				('-6 months')))
				->setCreatedBy($this->users[$this->faker->numberBetween(0, 7)])
				->setPost($post)
			;
			$this->manager->persist($comment);
			$this->comments[] = $comment;

			// 75% de chance d'avoir un commentaire approuvé
			if ($this->faker->boolean(75)) {
				$commentApproval = new CommentApproval();
				$commentApproval
					->setApproved(true)
					->setApprovedAt(\DateTimeImmutable::createFromMutable($this->faker->dateTimeBetween
					('-6 months')))
					->setApprovedBy($this->persistentUsers[$this->faker->numberBetween(0, 2)])
					->setComment($comment)
				;
				$this->manager->persist($commentApproval);
				$this->commentApprovals[] = $commentApproval;
			}

			// 20% de chance d'avoir un commentaire supprimé
			if ($this->faker->boolean(20)) {
				$commentDeletion = new CommentDeletion();
				$commentDeletion
					->setComment($comment)
					->setDeletedAt(\DateTimeImmutable::createFromMutable($this->faker->dateTimeBetween
					('-6 months')))
					->setDeletedBy($this->persistentUsers[$this->faker->numberBetween(0, 2)])
				;
				$this->manager->persist($commentDeletion);
				$this->commentDeletions[] = $commentDeletion;
			}

			// 20% de chance d'avoir un commentaire édité
			if ($this->faker->boolean(20)) {
				$commentEdit = new CommentEdit();
				$commentEdit
					->setComment($comment)
					->setContent('EDITED: ' . $this->faker->paragraph())
					->setEditedAt(\DateTimeImmutable::createFromMutable($this->faker->dateTimeBetween
					('-6 months')))
					->setEditedBy($this->persistentUsers[$this->faker->numberBetween(0, 2)])
				;
				$this->manager->persist($commentEdit);
				$this->commentEdits[] = $commentEdit;
			}
		}
	}
}
