<?php

require_once './vendor/autoload.php';
require_once './meetee/libs/utils/functions.php';

error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('register_globals', 0);

use Meetee\Libs\Http\Routing\Routers\Factories\RouterFactory;
use Meetee\Libs\Storage\Session;
use Meetee\App\Entities\User;
use Meetee\App\Entities\Role;
use Meetee\App\Entities\Token;
use Meetee\App\Entities\Comment;
use Meetee\Libs\Database\Factories\DatabaseFactory;
use Meetee\App\Entities\Factories\RoleFactory;
use Meetee\Libs\Database\Tables\UserTable;
use Meetee\Libs\Database\Tables\RoleTable;
use Meetee\Libs\Database\Tables\TokenTable;
use Meetee\Libs\Database\Tables\CommentTable;
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

define('SESSION_LIFETIME', (int) ini_get('session.gc_maxlifetime'));
define('BASE_URI', 'http://localhost/projects/Meetee/');
define('JS_DIR', BASE_URI . 'public/js/');
define('CSS_DIR', BASE_URI . 'public/css/');
define('IMG_DIR', BASE_URI . 'public/images/');
define('VIEW_DIR', BASE_URI . 'templates/');

Session::start('CtBst9tfZACCSxAWv1qvPFIvqBkN2eUy');


try {
	$router = RouterFactory::createComplete();
	$router->route();
}
catch (\Exception $e) {
	die($e);
}