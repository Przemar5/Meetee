<?php

namespace Meetee\App\Controllers;

use Meetee\App\Controllers\ControllerTemplate;
use Meetee\App\Entities\Utils\TokenFacade;
use Meetee\App\Entities\Factories\TokenFactory;
use Meetee\App\Entities\Factories\PostFactory;
use Meetee\App\Forms\PostForm;
use Meetee\App\Entities\User;
use Meetee\Libs\Http\Routing\Routers\Factories\RouterFactory;
use Meetee\Libs\View\Utils\Notification;
use Meetee\Libs\Security\AuthFacade;

class PostController extends ControllerTemplate
{
	private static string $tokenName = 'csrf_token';
	private array $errors = [];

	public function create(): void
	{
		try {
			$this->trimPostValues();
			$this->dieIfTokenInvalid(self::$tokenName);
			$this->dieAndPrintErrorsIfFormDataInvalid();

			$this->successfulRequestValidationEvent();
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

		if (!TokenFacade::popTokenIfValidByName($name, $user)) {
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

	private function successfulRequestValidationEvent(): void
	{
		$post = PostFactory::createAndSavePostFromRequest();
		echo json_encode($post);
		die;
	}
}