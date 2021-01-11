<?php

namespace Meetee\App\Emails;

interface Sendable
{
	public function send(array $data): void;
}