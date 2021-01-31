<?php

namespace Meetee\Libs\View;

use Meetee\Libs\View\ViewTemplate;
use Meetee\Libs\Http\Routing\RoutingFacade;
use Meetee\Libs\View\Utils\Notification;
use Meetee\Libs\Security\AuthFacade;

class BrowserView extends ViewTemplate
{
	private array $args = [];

	public function render(string $path, ?array $args = []): void
	{
		$file = $this->getTemplatePathIfValid($path);
		$layout = $this->getTemplatePathIfValid($this->layout);
		$this->args = $args;
		extract($args);

		require_once $file;
		require_once $layout;
	}

	private function error(string $name): void
	{
		if (isset($this->args['errors']) && isset($this->args['errors'][$name]))
			printf('%s', $this->args['errors'][$name]);
	}

	private function allErrors(): void
	{
		if (isset($this->args['errors']))
			foreach($this->args['errors'] as $name => $message)
				$this->error($name);
	}

	private function renderRouteUri(string $route): void
	{
		echo RoutingFacade::getLinkTo($route) ?? '';
	}

	private function escape(string $value): string
	{
		return addslashes($value);
	}

	private function success(): void
	{
		$msg = Notification::popSuccess();

		if ($msg)
			printf('%s', $msg);
	}

	private function isGranted(string $role): bool
	{
		return AuthFacade::isGranted($role);
	}

	private function include(string $path, ?array $args = []): void
	{
		$file = $this->getTemplatePathIfValid($path);
		extract($args);

		require $file;
	}

	private function includeRaw(string $path): void
	{
		$file = $this->getTemplatePathIfValid($path);
		
		printf('%s', file_get_contents($file));
	}
}