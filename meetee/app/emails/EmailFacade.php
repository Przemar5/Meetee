<?php

namespace Meetee\App\Emails;

use Meetee\App\Entities\User;
use Meetee\Libs\Http\Routing\RoutingFacade;
use Meetee\Libs\View\Factories\ViewFactory;
use Meetee\App\Entities\Factories\TokenFactory;
use Meetee\App\Emails\EmailAdapter;
use Meetee\Libs\Converters\Converter;

class EmailFacade
{
	public static function sendRegistrationConfirmEmail(
		string $tokenName,
		User $user
	): void
	{
		$view = ViewFactory::createHtmlEmailView();
		$email = new EmailAdapter($view);
		$email->subject = 'Registration confirmation';
		$email->receivers = [$user];
		static::completeEmailWithConfig();
		$email->parseTemplate('emails/registration_confirm_email', [
			'receiver' => $user,
			'token' => TokenFactory::generate($tokenName, $user),
			'route' => RoutingFacade::getLinkTo('registration_verify'),
		]);
		$email->send();
	}

	public static function sendForgotPasswordEmail(
		string $tokenName,
		User $user
	): void
	{
		$view = ViewFactory::createHtmlEmailView();
		$email = new EmailAdapter($view);
		static::completeEmailWithConfig($email);
		$email->subject = 'Forgotten password';
		$email->receivers = [$user];
		$email->parseTemplate('emails/reset_password_email', [
			'receiver' => $user,
			'token' => TokenFactory::generate($tokenName, $user),
			'route' => RoutingFacade::getLinkTo('forgot_password_verify'),
		]);
		$email->send();
	}

	private static function completeEmailWithConfig(
		EmailAdapter &$email
	)
	{
		$path = realpath('./config/email.json');
		static::throwExceptionsIfPathNotValid($path);
		$data = static::loadAndParseFile($path);
		
		static::completeEmailWithData($email, $data);
	}

	private static function throwExceptionsIfPathNotValid(string $path): void
	{
		if (!file_exists($path))
			throw new \Exception(sprintf("File '%s' doesn't exist", $path));
		
		if (!is_readable($path))
			throw new \Exception(sprintf("File '%s' isn't readable", $path));
	}

	private static function loadAndParseFile(string $path): array
	{
		$content = file_get_contents($path);

		return Converter::jsonToArray($content);
	}

	private static function completeEmailWithData(
		EmailAdapter &$email,
		array $data
	)
	{
		$email->host = $data['host'];
		$email->username = $data['username'];
		$email->password = $data['password'];
		$email->encryption = $data['encryption'];
		$email->from = $data['from'];
		$email->fromName = $data['from_name'];
		$email->replyTo = $data['reply_to'];
		$email->replyName = $data['reply_name'];
	}
}