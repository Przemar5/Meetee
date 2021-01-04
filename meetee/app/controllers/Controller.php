<?php

namespace Meetee\App\Controllers;

use Meetee\Libs\View\View;

abstract class Controller
{
	protected View $view;

	public function __construct(View $view)
	{
		$this->view = $view;
	}

	public function render(string $path, ?array $args = []): void
	{
		$this->view->render($path, $args);
	}
}