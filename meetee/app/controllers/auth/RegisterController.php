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
			$this->validateUsername();
			$this->validatePassword();
		}
		catch (\Exception $e) {
			die($e->getMessage());
			$this->page();
		}
	}

	private function validateData(): bool
	{
		return $this->validateUsername() &&
			$this->validatePassword();
	}

	private function validateUsername(): bool
	{
		$username = trim($_POST['username']) ?? null;

		if (!is_string($username))
			throw new \Excpeiton('Username must be a string.');

		$username = trim($username);

		if (strlen($username) < 3)
			throw new \Exception(
				'Username must be at least 3 characters long.');
		
		if (strlen($username) > 60)
			throw new \Exception(
				'Username must be not longer 60 than characters long.');

		if (!preg_match('/^[\w\d\-]+$/', $username))
			throw new \Exception(
				'Username may contain only letters, numbers and hyphens.');

		$user = User::findByUsername($username);
		
		if ($user)
			throw new \Exception(
				'User already exists. Please try another username.');

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