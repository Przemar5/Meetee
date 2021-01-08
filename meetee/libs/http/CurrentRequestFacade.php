<?php

namespace Meetee\Libs\Http;

use Meetee\Libs\Http\Routing\Data\RouteListFactory;
use Meetee\Libs\Security\Validators\RequestMethodValidator;
use Meetee\Libs\Security\Validators\UriValidator;
use Meetee\Libs\Security\Sanitizers\UriSanitizer;

class CurrentRequestFacade
{
	public static function getFullUri(): string
	{
		return sprintf("%s://%s%s", 
			$_SERVER['REQUEST_SCHEME'],
			$_SERVER['HTTP_HOST'],
			$_SERVER['REQUEST_URI'] ?? '/'
		);
	}

	public static function getRequestMethodIfValidOrThrowException(): string
	{
		static::throwExceptionIfRequestMethodInvalid();

		return $_SERVER['REQUEST_METHOD'];
	}

	public static function throwExceptionIfRequestMethodInvalid(): void
	{
		if (!static::isRequestMethodValid())
			throw new \Exception(sprintf("Request method '%s' is not supported.", 
				$_SERVER['REQUEST_METHOD']));
	}

	private static function isRequestMethodValid(): bool
	{
		$validator = new RequestMethodValidator();

		return $validator->run($_SERVER['REQUEST_METHOD']);
	}

	public static function getSanitizedUriIfValidOrThrowException(): string
	{
		$uri = (isset($_SERVER['PATH_INFO'])) 
			? static::getSanitizedCurrentUri() : '/';
		static::throwExceptionIfUriInvalid();

		return $uri;
	}

	private static function throwExceptionIfUriInvalid(): void
	{
		if (!static::isUriValid())
			throw new \Exception(sprintf("Uri '%s' is not valid.", 
				$_SERVER['PATH_INFO'] ?? '/'));
	}

	private static function isUriValid(): bool
	{
		$validator = new UriValidator();

		return $validator->run(static::getFullUri());
	}

	private static function getSanitizedCurrentUri(): string
	{
		return UriSanitizer::run($_SERVER['PATH_INFO']);
	}
}