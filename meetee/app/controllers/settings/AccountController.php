<?php

namespace Meetee\App\Controllers\Settings;

use Meetee\App\Controllers\ControllerTemplate;
use Meetee\App\Entities\User;
use Meetee\App\Entities\Factories\TokenFactory;
use Meetee\App\Entities\Utils\TokenFacade;
use Meetee\Libs\Database\Tables\UserTable;
use Meetee\Libs\Database\Tables\TokenTable;
use Meetee\Libs\View\Utils\Notification;
use Meetee\Libs\Security\AuthFacade;
use Meetee\Libs\Http\CurrentRequestFacade;
use Meetee\Libs\Security\Validators\Compound\Forms\TokenValidator;
use Meetee\Libs\Security\Validators\Factories\CompoundValidatorFactory;

class AccountController extends ControllerTemplate
{
	private static string $tokenName = 'settings_account_csrf_token';

	public function page(): void
	{
		$user = AuthFacade::getUser();
		$token = TokenFactory::generate(self::$tokenName, $user);

		$this->render('settings/account', [
			'user' => $user,
			'token' => $token,
		]);
	}

	public function process(): void
	{
		$user = AuthFacade::getUser();

		if (!TokenFacade::isPostRequestTokenValidForUser(self::$tokenName, $user))
			die;

		$this->dispatchRequestHandling($_POST, $user);
	}

	private function dispatchRequestHandling(
		array $request, 
		User $user
	): void
	{
		$accepts = [
			'name' => 'name', 
			'second_name' => 'secondName', 
			'surname' => 'surname', 
			'birth' => 'birth',
			'country' => 'country', 
			'city' => 'city', 
			'zip' => 'zipCode'
		];

		foreach ($accepts as $key => $attr) {
			if (isset($request[$key])) {
				$this->updateAttr($user, $attr, $request[$key]);
				echo json_encode([$key => $this->getUserAttr($user, $attr)]);
				die;
			}
		}
	}

	private function updateAttr(User $user, string $attr, $value): void
	{
		$value = trim($value);
		$validator = CompoundValidatorFactory::createUserValidator($attr);

		if (!$validator->run($value)) {
			return;
		}

		$this->setUserAttr($user, $attr, $value);

		$table = new UserTable();
		$table->save($user);

		return;
	}

	private function setUserAttr(User &$user, string $attr, $value): void
	{
		if (method_exists($user, 'set'.$attr))
			$user->{'set'.$attr}($value);
		else
			$user->{$attr} = $value;
	}

	private function getUserAttr(User $user, string $attr)
	{
		return (method_exists($user, 'get'.$attr))
			? $user->{'get'.$attr}()
			: $user->{$attr};
	}
}