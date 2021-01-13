<?php

namespace Meetee\App\Emails;

use Meetee\App\Entities\User;
use Meetee\Libs\Http\Routing\RoutingFacade;
use Meetee\Libs\View\Factories\ViewFactory;
use Meetee\App\Entities\Factories\TokenFactory;
use Meetee\App\Emails\EmailAdapter;

class EmailFacade
{
	public static function sendResetPasswordEmail(
		string $tokenName,
		User $user
	): void
	{
		$view = ViewFactory::createHtmlEmailView();
		$email = new EmailAdapter($view);
		$email->subject = 'Forgotten password';
		$email->receivers = [$user];
		$email->parseTemplate('emails/reset_password_email', [
			'receiver' => $user,
			'token' => TokenFactory::generate($tokenName, $user),
			'route' => RoutingFacade::getLinkTo('forgot_password_process'),
		]);
		$email->send();
	}

	public static function sendRegistrationConfirmEmail(
		string $tokenName,
		User $user
	): void
	{
		$view = ViewFactory::createHtmlEmailView();
		$email = new EmailAdapter($view);
		$email->subject = 'Registration confirmation';
		$email->receivers = [$user];
		$email->parseTemplate('emails/registration_confirm_email', [
			'receiver' => $user,
			'token' => TokenFactory::generate($tokenName, $user),
			'route' => RoutingFacade::getLinkTo('registration_verify'),
		]);
		$email->send();
	}
}