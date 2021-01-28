<?php

namespace Meetee\App\Controllers\Chat;

use Meetee\App\Controllers\ControllerTemplate;
use Meetee\Libs\Sockets\WebServerSocket;

class ChatController extends ControllerTemplate
{
	public function request(): void
	{
		try {
			dd($_GET);
		}
		catch (\Exception $e) {
			die($e->getMessage());
		}
	}
}