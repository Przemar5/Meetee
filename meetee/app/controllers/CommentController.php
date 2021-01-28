<?php

namespace Meetee\App\Controllers;

use Meetee\App\Controllers\ControllerTemplate;
use Meetee\App\Entities\Post;
use Meetee\App\Entities\Utils\TokenFacade;
use Meetee\App\Entities\Factories\TokenFactory;
// use Meetee\App\Entities\Factories\PostFactory;
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
		// $userId = (int) trim($_GET['user-id']);
		// $maxId = (int) trim($_GET['max-id']);
		// $amount = (int) trim($_GET['limit']);
		// $table = new PostTable();
		// $comments = $table->findLastFromByAuthorId($maxId, $amount, $userId);

		// echo json_encode($comments);
		// die;
	}

	public function create(): void
	{
		try {
			$this->trimPostValues();
			$this->dieIfTokenInvalid(self::$tokenName);
			$this->dieAndPrintErrorsIfFormDataInvalid();

			$this->createAndPrintJsonPostData();
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

	private function trimPostValues(): void
	{
		foreach ($_POST as $key => $value)
			if (is_string($value))
				$_POST[$key] = trim($value);
	}

	private function createAndPrintJsonPostData(): void
	{
		$comment = new Comment();
		$comment->content = trim($_POST['content']);
		
		$this->saveCommentAndPrintJsonData($post);
	}

	public function update($id): void
	{
		try {
			$this->trimPostValues();
			$this->dieIfTokenInvalid(self::$tokenName);
			$this->dieAndPrintErrorsIfUpdateFormDataInvalid($id);

			$this->updateAndPrintJsonPostData((int) $id);
		}
		catch (\Exception $e) {
			die($e->getMessage());
		}
	}

	private function dieAndPrintErrorsIfUpdateFormDataInvalid($commentId): void
	{
		$this->dieIfCommentIdInvalid($commentId);

		$validator = new PostBodyValidator();

		if (!$validator->run(trim($_POST['content'] ?? null))) {
			echo json_encode(['error' => $validator->errorMsg]);
			die;
		}

		$table = new PostTable();
		$comment = $table->find((int) $commentId);

		if (!$comment || $comment->authorId !== AuthFacade::getUserId())
			die;
	}

	private function dieIfCommentIdInvalid($commentId): void
	{
		if (!preg_match('/^[1-9][0-9]*$/', $commentId))
			die;

		$validator = new PostIdValidator();

		if (!$validator->run((int) $commentId))
			die;
	}

	private function updateAndPrintJsonPostData(int $id): void
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
			$this->trimPostValues();
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