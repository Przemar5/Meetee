<?php

namespace Meetee\App\Controllers\Settings;

use Meetee\App\Controllers\ControllerTemplate;
use Meetee\Libs\View\Utils\Notification;
use Meetee\Libs\Security\AuthFacade;

class AccountController extends ControllerTemplate
{
	public function page(): void
	{
		$user = AuthFacade::getUser();
		
		$this->render('settings/account', [
			'user' => $user,
		]);
	}
}