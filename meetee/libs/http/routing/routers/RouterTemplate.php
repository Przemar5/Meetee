<?php

namespace Meetee\Libs\Http\Routing\Routers;

use Meetee\Libs\Http\Data\Structures\RouteListFactory;

class RouterTemplate
{
	public function route(string $name): void
	{
		//
	}

	public function redirect(string $route, ?array $headers = []): void
	{
		if (!headers_sent()) {
			$headers['Location'] = $route;
			$this->sendHeaders($headers);
		}
		else {
			$this->renderRedirectionTags($route);
		}
	}

	protected function sendHeaders(array $headers): void
	{
		foreach ($headers as $header => $value)
			header(sprintf("%s: %s", $header, $value));
	}

	protected function renderRedirectionTags(string $route): void
	{
		echo sprintf('<script>window.location.href = %s</script>', $route);
		echo sprintf('<meta http-equiv="refresh" content="0; URL=\'%s\'"/>', 
				$route);
	}

	protected function renderNoAccessErrorPage(): void
	{
		//
	}

	protected function renderNotFoundErrorPage(): void
	{
		//
	}
}