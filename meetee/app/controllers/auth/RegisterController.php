<?php

namespace Meetee\App\Controllers\Auth;

use Meetee\App\Controllers\ControllerTemplate;
use Meetee\App\Entities\User;
use Meetee\Libs\Security\Validators\Compound\Forms\RegistrationFormValidator;

use Meetee\Libs\Security\Validators\EmailValidator;
use Meetee\Libs\Security\Validators\Factories\ValidatorFactory;
// use Meetee\Libs\Http\Routing\RoutingFacade;

class RegisterController extends ControllerTemplate
{
	public function page(): void
	{
		$validator = new EmailValidator();

		echo $validator->run('login');
		// echo $validator->errorMsg ?? null;
		die;

		$this->render('auth/register');
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