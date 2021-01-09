<?php

namespace Meetee\Libs\View;

use Meetee\Libs\View\ViewTemplate;

class BrowserView extends ViewTemplate
{
	public function render(string $path, ?array $args = []): void
	{
		$file = $this->getTemplatePathIfValid($path);
		$layout = $this->getTemplatePathIfValid($this->layout);
		extract($args);

		require_once $file;
		require_once $layout;
	}
}