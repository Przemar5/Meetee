<?php

namespace Meetee\App\Emails;

interface EmailView
{
	public function prepareTemplate(string $path, ?array $args = []): void;
}