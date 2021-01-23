<?php

namespace Meetee\App\Controllers;

use Meetee\App\Controllers\ControllerTemplate;
use Meetee\App\Entities\Post;
use Meetee\App\Entities\Utils\TokenFacade;
use Meetee\App\Entities\Factories\TokenFactory;
use Meetee\App\Entities\Factories\PostFactory;
use Meetee\App\Forms\PostForm;
use Meetee\Libs\Database\Tables\PostTable;
use Meetee\Libs\Http\Routing\Routers\Factories\RouterFactory;
use Meetee\Libs\View\Utils\Notification;
use Meetee\Libs\Security\AuthFacade;
use Meetee\Libs\Security\Validators\Compound\Posts\PostIdValidator;
use Meetee\Libs\Security\Validators\Compound\Posts\PostBodyValidator;

class PostController extends ControllerTemplate
{
	private static string $tokenName = 'csrf_token';
	private array $errors = [];

	public function select(): void
	{
		$userId = (int) trim($_GET['user-id']);
		$maxId = (int) trim($_GET['max-id']);
		$amount = (int) trim($_GET['limit']);
		$table = new PostTable();
		$posts = $table->findLastFromByAuthorId($maxId, $amount, $userId);

		echo json_encode($posts);
		die;
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
		$form = new PostForm();

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
		$post = new Post();
		$post->content = trim($_POST['content']);
		
		$this->savePostAndPrintJsonData($post);
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

	private function dieAndPrintErrorsIfUpdateFormDataInvalid($postId): void
	{
		$this->dieIfPostIdInvalid($postId);

		$validator = new PostBodyValidator();

		if (!$validator->run(trim($_POST['content'] ?? null))) {
			echo json_encode(['error' => $validator->errorMsg]);
			die;
		}

		$table = new PostTable();
		$post = $table->find((int) $postId);

		if (!$post || $post->authorId !== AuthFacade::getUserId())
			die;
	}

	private function dieIfPostIdInvalid($postId): void
	{
		if (!preg_match('/^[1-9][0-9]*$/', $postId))
			die;

		$validator = new PostIdValidator();

		if (!$validator->run((int) $postId))
			die;
	}

	private function updateAndPrintJsonPostData(int $id): void
	{
		$table = new PostTable();
		$post = $table->find($id);
		$post->content = trim($_POST['content']);
		
		$this->savePostAndPrintJsonData($post);
	}

	private function savePostAndPrintJsonData(Post $post): void
	{
		$table = new PostTable();
		$post = $table->saveComplete($post);

		echo json_encode([
			'id' => $post->getId(),
			'content' => $post->content,
			'author_id' => $post->authorId,
			'created_at' => $post->getCreatedAtString(),
			'updated_at' => $post->getUpdatedAtString(),
		]);
		die;
	}

	public function delete($id): void
	{
		try {
			$this->trimPostValues();
			$this->dieIfTokenInvalid(self::$tokenName);
			$this->dieIfPostIdInvalid($id);

			$this->deletePost((int) $id);
		}
		catch (\Exception $e) {
			die($e->getMessage());
		}
	}

	private function deletePost(int $id): void
	{
		$table = new PostTable();
		$table->delete($id);
	}
}