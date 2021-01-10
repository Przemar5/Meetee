<?php

namespace Meetee\App\Controllers\Auth;

use Meetee\App\Controllers\ControllerTemplate;
use Meetee\App\Entities\Factories\TokenFactory;
use Meetee\App\Entities\Utils\TokenHandler;
use Meetee\App\Forms\RegistrationForm;

use Meetee\Libs\Security\Validators\Compound\Users\UserPasswordValidator;

// use Meetee\Libs\Security\Validators\Factories\ValidatorFactory;
// use Meetee\Libs\Http\Routing\RoutingFacade;

class RegisterController extends ControllerTemplate
{
	public function page(): void
	{
		$validator = new UserPasswordValidator();
		$validator->run('password');

		$token = TokenFactory::generate('csrf_registration_token');

		$this->render('auth/register', [
			'token' => $token,
		]);
	}

	public function process(): void
	{
		try {
			$form = new RegistrationForm();

			if (!TokenHandler::validate('csrf_registration_token'))
				$this->page();

			if (!$form->validate())
				var_dump($form->getErrors());


		}
		catch (\Exception $e) {
			die($e->getMessage());
		}
	}
}