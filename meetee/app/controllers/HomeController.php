<?php

namespace Meetee\App\Controllers;

use Meetee\App\Controllers\ControllerTemplate;

class HomeController extends ControllerTemplate
{
	public function index(): void
	{
		$this->render('pages/home');
	}
}