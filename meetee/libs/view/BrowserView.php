<?php

namespace Meetee\Libs\View;

use Meetee\Libs\View\ViewTemplate;
use Meetee\Libs\Http\Routing\RoutingFacade;
use Meetee\Libs\View\Utils\Notification;

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
			printf('<small>%s</small>', $this->args['errors'][$name]);
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
		return addslashe($value);
	}

	private function success(): void
	{
		$msg = Notification::popSuccess();

		if ($msg)
			printf('<small>%s</small>', $msg);
	}
}