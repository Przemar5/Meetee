<?php

namespace Meetee\App\Controllers\Auth;

use Meetee\App\Controllers\ControllerTemplate;
use Meetee\App\Entities\Factories\TokenFactory;
use Meetee\App\Entities\Utils\TokenHandler;
use Meetee\App\Entities\User;
use Meetee\Libs\Database\Tables\UserTable;
use Meetee\Libs\Http\Routing\Routers\Factories\RouterFactory;
use Meetee\Libs\Http\Routing\RoutingFacade;
use Meetee\Libs\Security\AuthFacade;
use Meetee\Libs\Security\Validators\Compound\Users\UserEmailValidator;
use Meetee\App\Emails\Controllers\EmailController;
use Meetee\App\Emails\EmailFacade;

class ForgotPasswordController extends ControllerTemplate
{
	private static string $tokenName = 'forgot_password_token';
	private ?User $user = null;

	public function page(?array $errors = []): void
	{
		if (AuthFacade::getLoggedUser()) {
			$router = RouterFactory::createComplete();
			$router->redirectTo('home');
		}

		if (isset($_POST['email']) && is_string($_POST['email']))
			$_POST['email'] = trim($_POST['email']);
		
		$token = TokenFactory::generate(self::$tokenName);

		$this->render('auth/forgot_password', [
			'token' => $token,
			'errors' => $errors,
		]);
	}

	public function process(): void
	{
		try {
			$this->returnToPageIfTokenInvalid(self::$tokenName);
			$this->returnToPageWithErrorsIfEmailInvalid();
			EmailFacade::sendResetPasswordEmail($this->user);

			$router = RouterFactory::createComplete();
			$router->redirectTo('login');
		}
		catch (\Exception $e) {
			die($e->getMessage());
		}
	}

	private function returnToPageIfTokenInvalid(
		string $name, 
		?User $user = null
	): void
	{
		if (!TokenHandler::validate($name, $user)) {
			$this->page();
			die;
		}
	}

	private function returnToPageWithErrorsIfEmailInvalid(): void
	{
		if (!is_string($_POST['email'])) {
			$this->page();
			die;
		}

		$email = $_POST['email'] = trim($_POST['email']);
		$validator = new UserEmailValidator();
		
		if (!$validator->run($email))
			$this->returnPageWithError();

		$userTable = new UserTable();
		$this->user = $userTable->findOneWhere(['email' => $email]);

		if (!$this->user)
			$this->returnPageWithError();
	}

	private function returnPageWithError(): void
	{
		$this->page(['general' =>  
			'Given email does not exist in database.']);
		die;
	}

	public function verify(): void
	{
		try {
			$this->returnToPageIfTokenInvalid('forgot_password_email_token');
			// $token = TokenFactory::getFromRequestForUser(
				// 'forgot_password_email_token', $this->user);
		}
		catch (\Exception $e) {
			die($e->getMessage());
		}
	}
}