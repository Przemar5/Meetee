<?php

namespace Meetee\Libs\Http\Routing\Routers;

use Meetee\Libs\Http\Routing\Routers\RouterTemplate;
use Meetee\App\Entities\User;
use Meetee\Libs\Files\Logger;

class RouterGuardProxy extends RouterTemplate
{
	private RouterTemplate $router;
	private Logger $logger;

	public function __construct(RouterTemplate $router)
	{
		$this->route = $router;
	}

	public function route(): void
	{
		$user = User::current();

		if ($user->hasAccess($name)) {
			$this->router->route($name);
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