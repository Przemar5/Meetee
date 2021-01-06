<?php

namespace Meetee\Libs\Http\Routing\Routers;

use Meetee\Libs\Http\Routing\Routers\RouterTemplate;
use Meetee\App\Entities\User;

class RouterGuardProxy extends RouterTemplate
{
	private RouterTemplate $router;

	public function __construct(RouterTemplate $router)
	{
		$this->router = $router;
	}

	public function route(): void
	{
		$user = User::current();

		if (!is_null($user) && $user->hasAccess()) {
			$this->router->route();
		}
		else {
			$this->renderNoAccessErrorPage();
		}
	}

	public function setLogger(Logger $logger): void
	{
		$this->logger = $logger;
	}
}