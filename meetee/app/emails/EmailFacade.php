<?php

namespace Meetee\App\Emails;

use Meetee\App\Entities\User;
use Meetee\Libs\Http\Routing\RoutingFacade;
use Meetee\Libs\View\Factories\ViewFactory;
use Meetee\App\Entities\Factories\TokenFactory;
use Meetee\App\Emails\Controllers\EmailController;

class EmailFacade
{
	public static function sendResetPasswordEmail(User $user): void
	{
		$token = TokenFactory::generateResetPasswordEmailToken($user);
		$route = RoutingFacade::getLinkTo('forgot_password_process');
		$email = ViewFactory::createHtmlEmailView();
		$template = $email->getRendered('emails/reset_password_email', [
			'token' => $token,
			'receiver' => $user,
			'route' => $route,
		]);
		$data = [
			'receivers' => [$user],
			'subject' => 'Forgotten password',
			'template' => $template,
		];
		$controller = new EmailController();
		$controller->send($data);
	}

	public static function sendRegistrationConfirmEmail(User $user): void
	{
		$token = TokenFactory::generate('registration_confirm_email_token', $user);
		$route = RoutingFacade::getLinkTo('registration_confirm_process');
		$email = ViewFactory::createHtmlEmailView();
		$template = $email->getRendered('emails/registration_confirm_email', [
			'token' => $token,
			'receiver' => $user,
			'route' => $route,
		]);
		$data = [
			'receivers' => [$user],
			'subject' => 'Registration confirmation',
			'template' => $template,
		];
		$controller = new EmailController();
		$controller->send($data);
	}
}