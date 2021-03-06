<?php

namespace Meetee\App\Controllers;

use Meetee\App\Controllers\ControllerTemplate;
use Meetee\Libs\Storage\Session;
use Meetee\Libs\View\Utils\Notification;
use Meetee\Libs\Security\AuthFacade;

class MainController extends ControllerTemplate
{
	public function page(): void
	{
		$this->render('pages/main');
	}
}