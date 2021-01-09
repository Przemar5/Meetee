<?php

namespace Meetee\App\Controllers;

use Meetee\Libs\View\ViewTemplate;

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
}