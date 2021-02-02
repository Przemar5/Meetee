<?php

namespace Meetee\App\Controllers\Chat;

use Meetee\App\Controllers\ControllerTemplate;

class ConversationController extends ControllerTemplate
{
	public function create(): void
	{
		try {
			dd($_GET);
		}
		catch (\Exception $e) {
			die($e->getMessage());
		}
	}
}