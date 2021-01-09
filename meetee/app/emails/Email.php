<?php

namespace Meetee\App\Emails;

abstract class Email
{
	public string $subject;
	public string $from;
	public array $receivers;
	public string $content;

	abstract public function send(): void;
}