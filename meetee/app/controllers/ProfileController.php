<?php

namespace Meetee\App\Controllers;

use Meetee\App\Controllers\ControllerTemplate;
use Meetee\Libs\View\Utils\Notification;
use Meetee\Libs\Security\AuthFacade;
use Meetee\Libs\Database\Tables\UserTable;
use Meetee\App\Controllers\ControllerFactory;
use Meetee\App\Entities\Factories\TokenFactory;

class ProfileController extends ControllerTemplate
{
	public function page(int $id): void
	{
		$table = new UserTable();
		$user = $table->find($id);
		$token = TokenFactory::generate('csrf_token');

		if (!$user)
			$this->renderNotFoundPage();
		else {
			$this->render('profiles/show', [
				'user' => $user,
				'token' => $token,
			]);
		}
	}

	private function renderNotFoundPage(): void
	{
		$controller = ControllerFactory::createErrorControllerForBrowser();
		$controller->notFound();
	}
}