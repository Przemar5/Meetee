<?php

namespace Meetee\App\Controllers;

use Meetee\App\Controllers\ControllerTemplate;
use Meetee\App\Entities\Comment;
use Meetee\App\Entities\Utils\TokenFacade;
use Meetee\App\Entities\Factories\TokenFactory;
use Meetee\App\Entities\Factories\CommentFactory;
use Meetee\App\Forms\CommentForm;
use Meetee\Libs\Database\Tables\CommentTable;
use Meetee\Libs\Http\Routing\Routers\Factories\RouterFactory;
use Meetee\Libs\View\Utils\Notification;
use Meetee\Libs\Security\AuthFacade;
use Meetee\Libs\Security\Validators\Compound\Comments\CommentIdValidator;
use Meetee\Libs\Security\Validators\Compound\Comments\CommentBodyValidator;

class CommentController extends ControllerTemplate
{
	private static string $tokenName = 'csrf_token';
	private array $errors = [];

	public function select(): void
	{
		$userId = (int) trim($_GET['user-id']);
		$maxId = (int) trim($_GET['max-id']);
		$amount = (int) trim($_GET['limit']);
		$parentId = (isset($_GET['parent'])) ? (int) $_GET['parent'] : null;

		$table = new commentTable();
		$comments = $table->findLastFromByAuthorIdAndParentId(
			$maxId, $amount, $userId, $parentId);

		echo json_encode($comments);
		die;
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

		if (!$form->validate()) {
			echo json_encode($form->getErrors());
			die;
		}
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

		if (!$comment->authorId = AuthFacade::getUserId())
			die;
		
		$this->savecommentAndPrintJsonData($comment);
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

		if (!$validator->run(trim($_POST['content'] ?? null))) {
			echo json_encode(['error' => $validator->errorMsg]);
			die;
		}

		$table = new CommentTable();
		$comment = $table->find((int) $commentId);

		if (!$comment || $comment->authorId !== AuthFacade::getUserId())
			die;
	}

	private function dieIfCommentIdInvalid($commentId): void
	{
		if (!preg_match('/^[1-9][0-9]*$/', $commentId))
			die;

		$validator = new CommentIdValidator();

		if (!$validator->run((int) $commentId))
			die;
	}

	private function updateAndPrintJsoncommentData(int $id): void
	{
		$table = new CommentTable();
		$comment = $table->find($id);
		$comment->content = trim($_POST['content']);
		
		$this->savecommentAndPrintJsonData($comment);
	}

	private function savecommentAndPrintJsonData(Comment $comment): void
	{
		$table = new CommentTable();
		$comment = $table->saveComplete($comment);

		echo json_encode([
			'id' => $comment->getId(),
			'content' => $comment->content,
			'author_id' => $comment->authorId,
			'created_at' => $comment->getCreatedAtString(),
			'updated_at' => $comment->getUpdatedAtString(),
		]);
		die;
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