<?php

namespace Meetee\App\Controllers\Auth;

use Meetee\App\Controllers\ControllerTemplate;
use Meetee\App\Entities\Utils\TokenFacade;
use Meetee\App\Entities\Factories\TokenFactory;
use Meetee\App\Entities\Factories\UserFactory;
use Meetee\App\Forms\RegistrationForm;
use Meetee\App\Entities\User;
use Meetee\Libs\Database\Tables\UserTable;
use Meetee\Libs\Database\Tables\CountryTable;
use Meetee\Libs\Http\Routing\Routers\Factories\RouterFactory;
use Meetee\App\Emails\EmailFacade;
use Meetee\Libs\View\Utils\Notification;
use Meetee\Libs\Security\AuthFacade;
use Meetee\Libs\Files\Uploaders\ImageUploader;

class RegistrationController extends ControllerTemplate
{
	private static string $tokenName = 'csrf_registration_token';
	private array $errors = [];
	private ?string $profilePhotoFilename = null;
	private string $defaultPhotoFilename = 'users/noimage.png';

	public function page(): void
	{
		$token = TokenFactory::generate(self::$tokenName);

		$this->render('auth/register', [
			'token' => $token,
			'countries' => $this->getCountries(),
			'errors' => $this->errors,
		]);
	}

	private function getCountries(): array
	{
		$table = new CountryTable();

		return $table->getAllRaw();
	}

	public function process(): void
	{
		try {
			$this->trimPostValues();
			$this->returnToPageIfTokenInvalid(self::$tokenName);
			$this->returnToPageWithErrorsIfFormDataInvalid();

			$this->successfulRegisterRequestValidationEvent();
		}
		catch (\Exception $e) {
			die($e->getMessage());
		}
	}

	private function returnToPageIfTokenInvalid(string $name): void
	{
		if (!TokenFacade::popTokenIfValidByName($name)) {
			$this->page();
			die;
		}
	}

	private function returnToPageWithErrorsIfFormDataInvalid(): void
	{
		$form = new RegistrationForm();
		$uploader = new ImageUploader();

		if ((!empty($_FILES['profile']['name']) && 
			!$uploader->validateAndUploadImage('profile', 'users/')) || 
			!$form->validate()) {
			$this->errors = $form->getErrors();
			$this->page();
			die;
		} 

		$this->profilePhotoFilename = $uploader->getProfilePhotoFilename();
	}

	private function trimPostValues(): void
	{
		foreach ($_POST as $key => $value)
			if (is_string($value))
				$_POST[$key] = trim($value);
	}

	private function successfulRegisterRequestValidationEvent(): void
	{
		$user = UserFactory::createAndSaveUserFromPostRequest(
			$this->profilePhotoFilename ?? $this->defaultPhotoFilename);
		EmailFacade::sendRegistrationConfirmEmail(
			'registration_confirm_email_token', $user);
		Notification::addSuccess('Check Your mailbox for an activation email!');
		$this->redirect('login_page');
	}

	public function verify(): void
	{
		try {
			$token = TokenFacade::popTokenIfValidByNameAndUser(
				'registration_confirm_email_token');

			if (!$token)
				$this->redirect('registration_page');

			$table = new UserTable();

			if (!$user = $table->find($token->userId))
				$this->redirect('registration_page');

			$this->successfulVerificationRequestValidationEvent($user);
		}
		catch (\Exception $e) {
			die($e->getMessage());
		}
	}

	private function successfulVerificationRequestValidationEvent(
		User $user
	): void
	{
		AuthFacade::verifyUser($user);
		Notification::addSuccess(
			'Your account was activated. Now You can login!');
		$this->redirect('login_page');
	}
}