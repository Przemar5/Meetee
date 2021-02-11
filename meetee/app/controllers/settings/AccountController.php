<?php

namespace Meetee\App\Controllers\Settings;

use Meetee\App\Controllers\ControllerTemplate;
use Meetee\App\Entities\User;
use Meetee\App\Entities\Country;
use Meetee\App\Entities\Factories\TokenFactory;
use Meetee\App\Entities\Utils\TokenFacade;
use Meetee\Libs\Database\Tables\UserTable;
use Meetee\Libs\Database\Tables\TokenTable;
use Meetee\Libs\Database\Tables\CountryTable;
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
			'countries' => $this->getCountries(),
			'token' => $token,
		]);
	}

	private function getCountries(): array
	{
		$table = new CountryTable();

		return $table->getAllRaw();
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
			'name' => ['name', false], 
			'second_name' => ['secondName', true], 
			'surname' => ['surname', false], 
			'birth' => ['birth', false],
			'country' => ['country', true], 
			'city' => ['city', true], 
			'zip' => ['zipCode', true],
		];

		foreach ($accepts as $key => [$attr, $nullable]) {
			if (isset($request[$key])) {
				$this->updateAttr($user, $attr, $request[$key], $nullable);
				echo json_encode([$key => $this->getUserAttr($user, $attr)]);
				die;
			}
		}
	}

	private function updateAttr(User $user, string $attr, $value, bool $nullable): void
	{
		$value = trim($value);
		$validator = CompoundValidatorFactory::createUserValidator($attr);

		if ($attr === 'country')
			$value = (int) $value;

		if ((!$nullable && $validator->run($value)) || 
			($nullable && $value !== '' && $validator->run($value)))
			return;

		if ($attr === 'country')
			$value = $this->initializeCountryEntity($value);

		$this->setUserAttr($user, $attr, $value);

		$table = new UserTable();
		$table->save($user);

		return;
	}

	private function initializeCountryEntity(int $id): ?Country
	{
		$table = new CountryTable();
		
		return $table->find($id);
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
			? (string) $user->{'get'.$attr}()
			: (string) $user->{$attr};
	}
}