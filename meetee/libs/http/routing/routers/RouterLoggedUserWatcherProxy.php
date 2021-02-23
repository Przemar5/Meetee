<?php

namespace Meetee\Libs\Http\Routing\Routers;

use Meetee\Libs\Http\Routing\Routers\RouterTemplate;
use Meetee\Libs\Security\AuthFacade;
use Meetee\Libs\Storage\Cookie;
use Meetee\Libs\Storage\Session;
use Meetee\Libs\Database\Tables\UserTable;

class RouterLoggedUserWatcherProxy extends RouterTemplate
{
	private RouterTemplate $router;

	public function __construct(RouterTemplate $router)
	{
		$this->router = $router;
	}

	public function route(): void
	{
		if ($user = AuthFacade::getLoggedUser()) {
			Session::set('user_last_activity_time', time());
			setcookie('user_last_activity_time', time() + SESSION_LIFETIME, 
				time() + SESSION_LIFETIME);
			$user->setSessionExpirySecondsFromNow(SESSION_LIFETIME);
			$table = new UserTable();
			$table->save($user);
		}

		$this->router->route();
	}
}