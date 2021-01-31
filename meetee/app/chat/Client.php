<?php

namespace Meetee\App\Chat;

use Meetee\App\Entities\User;
use Meetee\App\Chat\Conversation;

class Client extends User implements \SplObserver
{
	public function update(Conversation $conversation): void;
}