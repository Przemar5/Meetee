<?php

namespace Meetee\Libs\View;

use Meetee\Libs\View\ViewTemplate;
use Meetee\Libs\Http\Routing\RoutingFacade;

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

	private function renderError(string $name): void
	{
		if (isset($this->args['errors'][$name]))
			printf('<small>%s</small>', $this->args['errors'][$name]);
	}

	private function renderRouteUri(string $route): void
	{
		echo RoutingFacade::getLinkTo($route) ?? '';
	}
}