<?php

namespace Meetee\App\Controllers;

use Meetee\App\Controllers\ControllerTemplate;
use Meetee\App\Entities\Comment;
use Meetee\App\Entities\Utils\TokenFacade;
use Meetee\App\Entities\Factories\TokenFactory;
use Meetee\App\Entities\Factories\CommentFactory;
use Meetee\App\Forms\CommentForm;
use Meetee\Libs\Database\Tables\CommentTable;
use Meetee\Libs\Database\Tables\TagTable;
use Meetee\Libs\Database\Tables\Pivots\CommentTagTable;
use Meetee\Libs\Http\Routing\Routers\Factories\RouterFactory;
use Meetee\Libs\View\Utils\Notification;
use Meetee\Libs\Security\AuthFacade;
use Meetee\Libs\Security\Validators\Compound\Comments\CommentIdValidator;
use Meetee\Libs\Security\Validators\Compound\Comments\CommentBodyValidator;
use Meetee\Libs\Security\Validators\Compound\Forms\CommentSelectValidator;

class CommentController extends ControllerTemplate
{
	private static string $tokenName = 'csrf_token';
	private array $errors = [];

	public function select(): void
	{
		$conditions = $this->prepareConditionsForSelect();
		$clauses = $this->getClausesIfValidOrDie();
		$validator = new CommentSelectValidator();

		if (!$validator->run($conditions))
			die;

		$comments = $this->selectRawCommentsData($conditions, $clauses);

		die(json_encode($comments));
	}

	private function prepareConditionsForSelect(): array
	{
		$maxId = trim($_GET['max_id']);
		
		if (!preg_match('/^\d{1,9}$/', $maxId))
			die;

		return [
			'user_id' => (int) trim($_GET['user_id']),
			'id' => ['<=', (int) $maxId],
			'parent_id' => (isset($_GET['parent_id'])) 
				? (int) $_GET['parent_id'] : null,
			'topic_id' => (isset($_GET['topic_id'])) 
				? (int) $_GET['topic_id'] : null,
			'group_id' => (isset($_GET['group_id'])) 
				? (int) $_GET['group_id'] : null,
			'on_profile' => (isset($_GET['on_profile'])) 
				? (bool) $_GET['on_profile'] : false,
		];
	}

	private function getClausesIfValidOrDie(): array
	{
		if (!preg_match('/^\d{1,9}$/', $_GET['amount']))
			die;

		return [
			'orderDesc' => null,
			'orderBy' => ['id'],
			'limit' => (int) trim($_GET['amount']),
		];
	}

	private function selectRawCommentsData(
		array $conditions, 
		array $clauses
	): array
	{
		$table = new CommentTable();

		return $table->findDataRecursive($conditions, $clauses);
	}

	public function create(): void
	{
		try {
			$this->trimcommentValues();
			$this->dieIfTokenInvalid(self::$tokenName);
			$this->dieAndPrintErrorsIfFormDataInvalid();

			$this->createAndPrintJsonResponse();
		}
		catch (\Exception $e) {
			die($e->getMessage());
		}
	}

	private function redirect(string $route): void
	{
		$router = RouterFactory::createComplete();
		$router->redirectTo($route);
	}

	private function dieIfTokenInvalid(string $name): void
	{
		$user = AuthFacade::getUser();

		if (!TokenFacade::getTokenFromPostRequestIfValidByNameAndUser(
			$name, $user)) {
			die;
		}
	}

	private function dieAndPrintErrorsIfFormDataInvalid(): void
	{
		$form = new CommentForm();

		if (!$form->validate())
			die(json_encode($form->getErrors()));
	}

	private function trimCommentValues(): void
	{
		foreach ($_POST as $key => $value)
			if (is_string($value))
				$_POST[$key] = trim($value);
	}

	private function createAndPrintJsonResponse(): void
	{
		$comment = new Comment();
		$comment->content = trim($_POST['content']);
		$comment->onProfile = ($_POST['on_profile'] == 1) ? true : false;
		$comment->parentId = (preg_match('/^\d{1,9}$/', $_POST['parent_id']))
			? $_POST['parent_id'] : null;
		$comment->topicId = (preg_match('/^\d{1,9}$/', $_POST['topic_id']))
			? $_POST['topic_id'] : null;
		$comment->groupId = (preg_match('/^\d{1,9}$/', $_POST['group_id']))
			? $_POST['group_id'] : null;

		if (!$comment->authorId = AuthFacade::getUserId())
			die;
		
		$this->saveCommentAndPrintJsonData($comment);
	}

	public function update($id): void
	{
		try {
			$this->trimCommentValues();
			$this->dieIfTokenInvalid(self::$tokenName);
			$this->dieAndPrintErrorsIfUpdateFormDataInvalid($id);

			$this->updateAndPrintJsoncommentData((int) $id);
		}
		catch (\Exception $e) {
			die($e->getMessage());
		}
	}

	private function dieAndPrintErrorsIfUpdateFormDataInvalid($commentId): void
	{
		$this->dieIfcommentIdInvalid($commentId);

		$validator = new commentBodyValidator();

		if (!$validator->run(trim($_POST['content'] ?? null)))
			die(json_encode(['error' => $validator->errorMsg]));

		$table = new CommentTable();
		$comment = $table->find((int) $commentId);

		if (!$comment || $comment->authorId !== AuthFacade::getUserId())
			die;
	}

	private function dieIfCommentIdInvalid($commentId): void
	{
		if (!preg_match('/^[1-9][0-9]*$/', $commentId))
			die;
	}

	private function updateAndPrintJsoncommentData(int $id): void
	{
		$table = new CommentTable();
		$comment = $table->find($id);
		$comment->content = trim($_POST['content']);
		
		$this->saveCommentAndPrintJsonData($comment);
	}

	private function saveCommentAndPrintJsonData(Comment $comment): void
	{
		$table = new CommentTable();
		$comment = $table->saveComplete($comment);
		$this->updateTagsForComment($comment);

		die(json_encode([
			'id' => $comment->getId(),
			'content' => $comment->content,
			'author_id' => $comment->authorId,
			'topic_id' => $comment->topicId,
			'group_id' => $comment->groupId,
			'parent_id' => $comment->parentId,
			'on_profile' => $comment->onProfile,
			'created_at' => $comment->getCreatedAtString(),
			'updated_at' => $comment->getUpdatedAtString(),
		]));
	}

	private function updateTagsForComment(Comment $comment): void
	{
		$table = new CommentTagTable();
		$tags = $this->getTagsForComment($comment);

		if (empty($tags))
			return;
		
		$comment->tags = $this->saveGetCompleteTags($tags);
		$table->setTagsForComment($comment);
	}

	private function getTagsForComment(Comment $comment): array
	{
		$matches = [];
		preg_match_all('/(?:#)(\w+)/', $comment->content, $matches);

		return $matches[1];
	}

	private function saveGetCompleteTags(array $tags): array
	{
		$table = new TagTable();
		$table->completeMultiple($tags);

		return $tags;
	}

	public function delete($id): void
	{
		try {
			$this->trimcommentValues();
			$this->dieIfTokenInvalid(self::$tokenName);
			$this->dieIfCommentIdInvalid($id);

			$this->deleteComment((int) $id);
		}
		catch (\Exception $e) {
			die($e->getMessage());
		}
	}

	private function deleteComment(int $id): void
	{
		$table = new CommentTable();
		$table->delete($id);
	}
}