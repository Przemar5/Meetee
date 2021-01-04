<?php

namespace Meetee\Libs\View;

interface View
{
	public function render(string $path, ?array $args = []): void;
}