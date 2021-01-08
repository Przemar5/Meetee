<?php

require_once './vendor/autoload.php';

error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('register_globals', 0);

use Meetee\Libs\Http\Routing\Routers\Factories\RouterFactory;
use Meetee\Libs\Storage\Session;
use Meetee\Libs\Database\Factories\DatabaseFactory;
use Meetee\App\Entities\User;


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

// $router = RouterFactory::createComplete();
// $router->route();

$user = new Meetee\App\Entities\User();
$user->setUsername('test');
$user->setPassword('test');
$user->save();

// $user = User::current();
// var_dump($user);

// $database = DatabaseFactory::create();
