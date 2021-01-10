<?php

namespace Meetee\App\Controllers\Auth;

use Meetee\App\Controllers\ControllerTemplate;
use Meetee\App\Entities\User;
use Meetee\Libs\Security\Validators\Compound\Forms\RegistrationFormValidator;
use Meetee\Libs\Security\AuthFacade;

// use Meetee\Libs\Security\Validators\Compound\Users\NewUserEmailValidator;
// use Meetee\Libs\Security\Validators\Factories\ValidatorFactory;
// use Meetee\Libs\Http\Routing\RoutingFacade;

class RegisterController extends ControllerTemplate
{
	public function page(): void
	{
		$token = AuthFacade::generateCsrfToken();

		var_dump($token);die;

		$this->render('auth/register', [
			// 'csrf_token' => Token::generate('csrf_token'),
		]);
	}

	public function process(): void
	{
		try {
			if (!$this->validateData())
				$this->page();
			else
				die('GOOD');
		}
		catch (\Exception $e) {
			die($e->getMessage());
		}
	}

	private function validateData()
	{
		$data = $this->getRequestData();
		$validator = new RegistrationFormValidator();
		$validator->run($data);

		var_dump($validator->getErrors());
	}

	private function getRequestData()
	{
		return [
			'login' => $_POST['login'],
			'email' => $_POST['email'],
			'name' => $_POST['name'],
			'surname' => $_POST['surname'],
			'birth' => $_POST['birth'],
			'password' => $_POST['password'],
		];
	}
}