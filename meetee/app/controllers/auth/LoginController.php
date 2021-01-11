<?php

namespace Meetee\App\Controllers\Auth;

use Meetee\App\Controllers\ControllerTemplate;
use Meetee\App\Entities\Factories\TokenFactory;
use Meetee\App\Entities\Utils\TokenHandler;
use Meetee\App\Forms\RegistrationForm;
use Meetee\App\Entities\User;
use Meetee\Libs\Http\Routing\Routers\Factories\RouterFactory;

class LoginController extends ControllerTemplate
{
	public function page(?array $errors = []): void
	{
		echo 'Hello';
	}
}