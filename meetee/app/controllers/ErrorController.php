<?php

namespace Meetee\App\Controllers;

use Meetee\App\Controllers\ControllerTemplate;

class ErrorController extends ControllerTemplate
{
	public function notFound(): void
	{
		$this->render('errors/not_found');
	}

	public function accessRestricted(): void
	{
		$this->render('errors/access_restricted');
	}
}