<?php

namespace Meetee\App\Controllers\Auth;

use Meetee\App\Controllers\ControllerTemplate;

class RegisterController extends ControllerTemplate
{
	public function page(): void
	{
		$this->render('auth/register');
	}
}