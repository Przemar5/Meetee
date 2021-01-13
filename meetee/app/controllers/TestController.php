<?php

namespace Meetee\App\Controllers;

use Meetee\App\Controllers\ControllerTemplate;
use Meetee\App\Entities\Factories\TokenFactory;
use Meetee\App\Entities\Factories\UserFactory;
use Meetee\App\Entities\Utils\TokenFacade;
use Meetee\App\Forms\RegistrationForm;
use Meetee\App\Entities\User;
use Meetee\Libs\Database\Tables\UserTable;
use Meetee\Libs\Http\Routing\Routers\Factories\RouterFactory;
use Meetee\App\Emails\EmailFacade;
use Meetee\Libs\View\Utils\Notification;
use Meetee\Libs\Security\Validators\Compound\Users\UserEmailValidator;

class TestController extends ControllerTemplate
{
	public function page(): void
	{
		$name = 'csrf_registration_token';
		$url = 'http://localhost/files/projects/SocialNetwork/register';
		$token = TokenFactory::generate($name);

		if ($_SERVER['REQUEST_METHOD'] === 'GET') {
			// $token = TokenFactory::
			// dd($token);
			$ch = curl_init($url);
			curl_setopt($ch, CURLOPT_POST, 1);
			curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query([
				$name => $token->value,
				'login' => 'Przemek',
				'email' => '1234567890localhost@gmail.com',
				'name' => 'Przemek',
				'surname' => 'XXXX',
				'birth' => '1990-10-10',
				'password' => 'Password1!',
				'repeat_password' => 'Password1!',
			]));
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			$response = curl_exec($ch);
			curl_close($ch);

			$database = \Meetee\Libs\Database\Factories\DatabaseAbstractFactory::createDatabase();

			$token = TokenFactory::popIfRequestValid('csrf_registration_token');

			echo 'count(*): ' . 
				$database->findOne('select count(*) from tokens')['count(*)'] . "<br>";
			dd($database->findOne('select * from users order by id desc limit 1'));
		}
	}
}

