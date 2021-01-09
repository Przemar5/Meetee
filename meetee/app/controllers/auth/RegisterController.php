<?php

namespace Meetee\App\Controllers\Auth;

use Meetee\App\Controllers\ControllerTemplate;
use Meetee\App\Entities\User;
use Meetee\Libs\Http\Routing\RoutingFacade;

class RegisterController extends ControllerTemplate
{
	public function page(): void
	{
		$this->render('auth/register');
	}

	public function process(): void
	{
		try {
			$this->validateData();
		}
		catch (\Exception $e) {
			die($e->getMessage());
			$this->page();
		}
	}

	private function validateData(): bool
	{
		return $this->validateLogin() &&
			$this->validatePassword();
	}

	private function validateLogin(): bool
	{
		$login = trim($_POST['login']) ?? null;

		try {
			
		}
		
		if (!is_string($login))
			throw new \Excpeiton('Login must be a string.');

		$login = trim($login);

		if (strlen($login) < 3)
			throw new \Exception(
				'Login must be at least 3 characters long.');
		
		if (strlen($login) > 60)
			throw new \Exception(
				'Login must be not longer 60 than characters long.');

		if (!preg_match('/^[\w\d\-]+$/', $login))
			throw new \Exception(
				'Login may contain only letters, numbers and hyphens.');

		$user = User::findByLogin($login);
		
		if ($user)
			throw new \Exception(
				'Login already exists. Please try another login.');

		return true;
	}

	private function validatePassword(): bool
	{
		$password = $_POST['password'];

		if (!is_string($password))
			throw new \Excpeiton('Password must be a string.');

		$password = trim($password);

		if (strlen($password) < 8)
			throw new \Exception(
				'Password must be at least 8 characters long.');
		
		if (strlen($password) > 60)
			throw new \Exception(
				'Password must be not longer 60 than characters long.');

		// if (!preg_match('/^[\w\d \-]+$/', $password))


		return true;
	}
}