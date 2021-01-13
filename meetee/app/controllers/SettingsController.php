<?php

namespace Meetee\App\Controllers;

use Meetee\App\Controllers\ControllerTemplate;
use Meetee\Libs\View\Utils\Notification;
use Meetee\Libs\Security\AuthFacade;

class SettingsController extends ControllerTemplate
{
	public function index(): void
	{
		$user = AuthFacade::getUser();

		$this->render('settings/index', [
			'user' => $user,
		]);
	}

	public function accountPage(): void
	{
		$user = AuthFacade::getUser();

		$this->render('settings/account', [
			'user' => $user,
		]);
	}

	public function privacyPage(): void
	{
		$user = AuthFacade::getUser();

		$this->render('settings/privacy', [
			'user' => $user,
		]);
	}
}