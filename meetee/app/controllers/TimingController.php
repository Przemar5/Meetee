<?php

namespace Meetee\App\Controllers;

use Meetee\App\Controllers\ControllerTemplate;

class TimingController extends ControllerTemplate
{
	public function lastActivityUpdateEvent(): void
	{
		$token = TokenFactory::generate(self::$tokenName);

		$this->render('auth/login', [
			'token' => $token,
			'errors' => $this->errors,
		]);
	}
}