<?php

namespace Meetee\App\Controllers;

use Meetee\App\Controllers\ControllerTemplate;
use Meetee\Libs\Storage\Session;
use Meetee\Libs\View\Utils\Notification;

class HomeController extends ControllerTemplate
{
	public function index(): void
	{
		$this->render('pages/home');
	}
}