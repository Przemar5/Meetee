<?php

namespace Meetee\App\Controllers;

use Meetee\Libs\View\ViewTemplate;
use Meetee\Libs\Http\Routing\Routers\Factories\RouterFactory;

abstract class ControllerTemplate
{
	protected ViewTemplate $view;

	public function __construct(ViewTemplate $view)
	{
		$this->view = $view;
	}

	public function render(string $path, ?array $args = []): void
	{
		$this->view->render($path, $args);
	}

	protected function trimPostedValues(): void
	{
		foreach ($_POST as $key => $value)
			if (is_string($value))
				$_POST[$key] = trim($value);
	}

	protected function redirect(
		string $route,
		?array $args = [], 
		?array $headers = []
	): void
	{
		$router = RouterFactory::createComplete();
		$router->redirectTo($route, $args, $headers);
	}
}