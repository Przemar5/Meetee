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
		$token = TokenFactory::generate('reset_password_email_token');
		$route = RoutingFacade::getRouteUriByName('forgot_password_process');
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
}