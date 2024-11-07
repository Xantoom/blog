<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Entity\CommentApproval;
use App\Entity\CommentDeletion;
use App\Entity\CommentEdit;
use App\Entity\Post;
use Doctrine\ORM\Exception\ORMException;
use Doctrine\ORM\OptimisticLockException;

class PostController extends AbstractController
{
	private const int POSTS_PER_PAGE = 12;

    public function index(): string
    {
		$nbPages = ceil($this->getRepositoryService()->getPostRepository()->count() / self::POSTS_PER_PAGE);
		$page = $this->request->query->getInt('page', 1);

		if ($page < 1 || $page > $nbPages) {
			$this->redirect('/posts');
		}

		$posts = $this->getRepositoryService()->getPostRepository()->findBy(
			[],
			['createdAt' => 'DESC'],
			self::POSTS_PER_PAGE,
			($page - 1) * self::POSTS_PER_PAGE
		);

		return $this->render('pages/posts/listing/index.html.twig', [
			'posts' => $posts,
			'page' => $page,
			'nbPages' => $nbPages
		]);
    }

	/**
	 * @throws OptimisticLockException
	 * @throws ORMException
	 */
	public function show(string $id): string
	{
		$post = $this->getRepositoryService()->getPostRepository()->find($id);

		if (!($post instanceof Post)) {
			$this->addFlash('error', 'Post not found');
			$this->redirect('/not-found');
		}

		$post->setViews($post->getViews() + 1);
		$this->getEntityManager()->persist($post);

		// If there is a POST REQUEST
		if ($_SERVER['REQUEST_METHOD'] === 'POST') {

			if (!isset($_POST['content']) && !isset($_POST['edit_comment_id'], $_POST['edit_comment_content'])) {
				$this->addFlash('error', 'Your REQUEST is invalid');
				$this->redirect('/posts/' . $post->getId());
			}

			if (isset($_POST['content'])) {
				$content = $_POST['content'];
				if (empty($content)) {
					$this->addFlash('error', 'All fields are required');
					$this->redirect('/posts/' . $post->getId() . '#comment-section');
				}

				$comment = new Comment();
				$comment
					->setContent($content)
					->setCreatedBy($this->getCurrentUser())
					->setPost($post)
				;

				$this->getEntityManager()->persist($comment);

				if ($this->isGranted('ROLE_ADMIN')) {
					$approval = new CommentApproval();
					$approval
						->setApproved(true)
						->setApprovedAt(new \DateTimeImmutable())
						->setApprovedBy($this->getCurrentUser())
						->setComment($comment)
					;

					$this->getEntityManager()->persist($approval);

					$comment->setApproval($approval);
				}

				$this->getEntityManager()->flush();

				$this->addFlash('success', 'Your comment has been added');
				$this->redirect('/posts/' . $post->getId() . '#comment-section');
			}

			if (isset($_POST['edit_comment_id'], $_POST['edit_comment_content'])) {
				$commentId = $_POST['edit_comment_id'];
				$comment = $this->getRepositoryService()->getCommentRepository()->find($commentId);

				if (!($comment instanceof Comment)) {
					$this->addFlash('error', 'Comment not found');
					$this->redirect('/not-found');
				}

				$editCommentContent = $_POST['edit_comment_content'];
				if (empty($editCommentContent)) {
					$this->addFlash('error', 'All fields are required');
					$this->redirect('/posts/' . $post->getId() . '#comment-section');
				}

				$editComment = new CommentEdit();
				$editComment
					->setContent($editCommentContent)
					->setEditedAt(new \DateTimeImmutable())
					->setEditedBy($this->getCurrentUser())
					->setComment($comment)
				;

				$this->getEntityManager()->persist($editComment);

				$comment->addCommentEdit($editComment);

				$this->getEntityManager()->flush();

				$this->addFlash('success', 'Your comment has been edited');
				$this->redirect('/posts/' . $post->getId() . '#comment-section');
			}
		}

		$this->getEntityManager()->flush();

		$editCommentId = isset($_SESSION['edit_comment_id']);
		unset($_SESSION['edit_comment_id']);

		return $this->render('pages/posts/show/index.html.twig', [
			'post' => $post,
			'editCommentId' => $editCommentId,
		]);
	}

	/**
	 * @throws OptimisticLockException
	 * @throws ORMException
	 */
	public function approveComment(string $id): void
	{
		$comment = $this->getRepositoryService()->getCommentRepository()->find($id);

		if (!($comment instanceof Comment)) {
			$this->addFlash('error', 'Comment not found');
			$this->redirect('/not-found');
		}

		if ($this->isGranted('ROLE_ADMIN')) {
			$existingApproval = $this->getRepositoryService()->getCommentApprovalRepository()->findOneBy([
				'comment' => $comment,
			]);

			if ($existingApproval) {
				$existingApproval
					->setApproved(true)
					->setApprovedAt(new \DateTimeImmutable())
					->setApprovedBy($this->getCurrentUser())
				;

				$this->getEntityManager()->persist($existingApproval);
				$this->getEntityManager()->flush();

				$this->addFlash('success', 'Comment approved');
				$this->redirect('/posts/' . $comment->getPost()->getId(). '#comment-section');
			}

			$approval = new CommentApproval();
			$approval
				->setApproved(true)
				->setApprovedAt(new \DateTimeImmutable())
				->setApprovedBy($this->getCurrentUser())
				->setComment($comment)
			;

			$this->getEntityManager()->persist($approval);

			$comment->setApproval($approval);

			$this->getEntityManager()->flush();

			$this->addFlash('success', 'Comment approved');
			$this->redirect('/posts/' . $comment->getPost()->getId(). '#comment-section');
		}

		$this->addFlash('error', 'You are not allowed to do this');
		$this->redirect('/posts/' . $comment->getPost()->getId(). '#comment-section');
	}

	/**
	 * @throws OptimisticLockException
	 * @throws ORMException
	 */
	public function disapproveComment(string $id): void
	{
		$comment = $this->getRepositoryService()->getCommentRepository()->find($id);

		if (!($comment instanceof Comment)) {
			$this->addFlash('error', 'Comment not found');
			$this->redirect('/not-found');
		}

		if ($this->isGranted('ROLE_ADMIN')) {
			$approval = $this->getRepositoryService()->getCommentApprovalRepository()->findOneBy([
				'comment' => $comment,
			]);

			if ($approval) {
				$approval
					->setApproved(false)
					->setApprovedAt(new \DateTimeImmutable())
					->setApprovedBy($this->getCurrentUser())
				;

				$this->getEntityManager()->persist($approval);
				$this->getEntityManager()->flush();

				$this->addFlash('success', 'Comment unapproved');
				$this->redirect('/posts/' . $comment->getPost()->getId(). '#comment-section');
			}

			$this->addFlash('danger', 'This comment has not been approved');
			$this->redirect('/posts/' . $comment->getPost()->getId(). '#comment-section');
		}

		$this->addFlash('error', 'You are not allowed to do this');
		$this->redirect('/posts/' . $comment->getPost()->getId(). '#comment-section');
	}

	/**
	 * @throws OptimisticLockException
	 * @throws ORMException
	 */
	public function deleteComment(string $id): void
	{
		$comment = $this->getRepositoryService()->getCommentRepository()->find($id);

		if (!($comment instanceof Comment)) {
			$this->addFlash('error', 'Comment not found');
			$this->redirect('/not-found');
		}

		if ($this->isGranted('ROLE_ADMIN')) {
			$existingDeletion = $this->getRepositoryService()->getCommentDeletionRepository()->findOneBy([
				'comment' => $comment,
			]);

			if ($existingDeletion) {
				$this->addFlash('danger', 'This comment has already been deleted by ' . $existingDeletion->getDeletedBy()->getFullname());
				$this->redirect('/posts/' . $comment->getPost()->getId(). '#comment-section');
			}

			$deletion = new CommentDeletion();
			$deletion
				->setDeletedAt(new \DateTimeImmutable())
				->setDeletedBy($this->getCurrentUser())
				->setComment($comment)
			;

			$this->getEntityManager()->persist($deletion);

			$comment->setDeletion($deletion);

			$this->getEntityManager()->flush();

			$this->addFlash('success', 'Comment deleted');
			$this->redirect('/posts/' . $comment->getPost()->getId(). '#comment-section');
		}

		$this->addFlash('error', 'You are not allowed to do this');
		$this->redirect('/posts/' . $comment->getPost()->getId(). '#comment-section');
	}

	public function editComment(string $id): void
	{
		$comment = $this->getRepositoryService()->getCommentRepository()->find($id);

		if (!($comment instanceof Comment)) {
			$this->addFlash('error', 'Comment not found');
			$this->redirect('/not-found');
		}

		// set to session the comment id
		$_SESSION['edit_comment_id'] = $comment->getId();

		// redirect to the post page
		$this->redirect('/posts/' . $comment->getPost()->getId() . '#comment-section');
	}
}
