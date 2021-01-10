<?php

require_once './vendor/autoload.php';

error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('register_globals', 0);

use Meetee\Libs\Http\Routing\Routers\Factories\RouterFactory;
use Meetee\Libs\Storage\Session;
use Meetee\Libs\Database\Factories\DatabaseFactory;
use Meetee\App\Entities\User;
use Meetee\App\Entities\Role;
use Meetee\Libs\Database\Tables\UserTable;
use Meetee\Libs\Database\Tables\RoleTable;
use Meetee\Libs\Security\AuthFacade;


spl_autoload_register(function($namespaceWithClass) {
	$parts = explode('\\', $namespaceWithClass);
	$class = array_pop($parts);
	$parts = array_map('strtolower', $parts);
	$subPath = implode(DIRECTORY_SEPARATOR, $parts);
	$path = $subPath . DIRECTORY_SEPARATOR . $class . '.php';

	if (is_readable($path))
		require_once $path;
});


Session::start('CtBst9tfZACCSxAWv1qvPFIvqBkN2eUy');

try {
	$user = AuthFacade::getUser();

	$router = RouterFactory::createComplete();
	$router->route();
}
catch (\Exception $e) {
	die($e);
}
