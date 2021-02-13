<?php

namespace Meetee\App\Controllers\Auth;

use Meetee\App\Controllers\ControllerTemplate;
use Meetee\App\Entities\Factories\TokenFactory;
use Meetee\App\Entities\Utils\TokenFacade;
use Meetee\App\Entities\User;
use Meetee\Libs\Database\Tables\UserTable;
use Meetee\Libs\Http\Routing\Routers\Factories\RouterFactory;
use Meetee\Libs\View\Utils\Notification;
use Meetee\Libs\Security\AuthFacade;

class DeleteAccountController extends ControllerTemplate
{
	private static string $tokenName = 'csrf_delete_account_token';
	private array $errors = [];
	private string $defaultProfileFilename = 'users/noimage.png';

	public function page(): void
	{
		$token = TokenFactory::generate(self::$tokenName);

		$this->render('auth/delete_account', [
			'token' => $token,
			'errors' => $this->errors,
		]);
	}

	public function process(): void
	{
		try {
			$this->trimValues();
			$this->returnToPageIfTokenInvalid(self::$tokenName);

			$this->successfulRequestValidationEvent();
		}
		catch (\Exception $e) {
			die($e->getMessage());
		}
	}

	private function trimValues(): void
	{
		foreach ($_POST as $key => $value)
			if (is_string($value))
				$_POST[$key] = trim($value);
	}

	private function returnToPageIfTokenInvalid(string $name): void
	{
		if (!TokenFacade::popTokenIfValidByNameAndUser(
			$name, AuthFacade::getUser())) {

			$this->page();
			die;
		}
	}

	private function successfulRequestValidationEvent(): void
	{
		$this->deleteProfilePhotoIfNotDefault();
		$this->deleteUser();
		Notification::addSuccess('Your account has been deleted successfully.');
		$this->redirect('home');
	}

	private function deleteProfilePhotoIfNotDefault(): void
	{
		$profile = AuthFacade::getUser()->profile;
		
		if ($profile === $this->defaultProfileFilename)
			return;

		$path = './' . substr(IMG_DIR, strcmp(BASE_URI, IMG_DIR));
		$filepath = $path . $profile;
		echo $filepath;
		unlink($filepath);
	}

	private function deleteUser(): void
	{
		$user = AuthFacade::getUser();
		$user->deleted = true;
		$table = new UserTable();
		$table->save($user);
		AuthFacade::logout();
	}

	private function redirect(string $route): void
	{
		$router = RouterFactory::createComplete();
		$router->redirectTo($route);
	}
}