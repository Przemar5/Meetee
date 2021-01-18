<?php

namespace Meetee\App\Controllers;

use Meetee\App\Controllers\ControllerTemplate;
use Meetee\Libs\View\Utils\Notification;
use Meetee\Libs\Security\AuthFacade;
use Meetee\Libs\Database\Tables\UserTable;
use Meetee\App\Controllers\ControllerFactory;

class ProfileController extends ControllerTemplate
{
	public function page(int $id): void
	{
		$table = new UserTable();
		$user = $table->find($id);
		
		// dd($_SERVER['PATH_INFO']);

		if (!$user)
			$this->renderNotFoundPage();
			


		$user = AuthFacade::getUser();

		$this->render('profiles/show', [
			'user' => $user,
		]);
		dd(AuthFacade::getUser());
	}

	private function renderNotFoundPage(): void
	{
		$controller = ControllerFactory::createErrorControllerForBrowser();
		$controller->notFound();
		// die;
	}
}