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
use Meetee\Libs\Database\Factories\DatabaseFactory;
use Meetee\App\Entities\Factories\RoleFactory;
use Meetee\Libs\Database\Tables\UserTable;
use Meetee\Libs\Database\Tables\RoleTable;
use Meetee\Libs\Database\Tables\TokenTable;


spl_autoload_register(function($namespaceWithClass) {
	$parts = explode('\\', $namespaceWithClass);
	$class = array_pop($parts);
	$parts = array_map('strtolower', $parts);
	$subPath = implode(DIRECTORY_SEPARATOR, $parts);
	$path = $subPath . DIRECTORY_SEPARATOR . $class . '.php';

	if (is_readable($path))
		require_once $path;
});

define('BASE_URI', 'http://localhost/projects/Meetee/');
define('JS_DIR', BASE_URI . 'public/js/');
define('CSS_DIR', BASE_URI . 'public/css/');
define('VIEW_DIR', BASE_URI . 'templates/');

Session::start('CtBst9tfZACCSxAWv1qvPFIvqBkN2eUy');


// echo "<pre>";

try {
	$router = RouterFactory::createComplete();
	$router->route();
}
catch (\Exception $e) {
	die($e);
}




// $view = new \Meetee\Libs\View\BrowserView();

// ob_start();
// require_once 'index2.php';
// $v = ob_get_clean();





// // Get token
// // $token = TokenFactory::popIfRequestValid($name);
// // dd($token);

// // Generate and request



// // $opt = [
// // 	'http' => [
// // 		'method' => 'POST',

// // 	],
// // ];



// echo $response;

// dd($token);

// $token = new Token();
// $token->name = 'Token';
// $token->value = 'dctfvghjbceuiofw';
// $token->userId = 0;
// $token->setExpiry('+3 hours');
// $table->save($token);
// $token->setId($table->lastinsertId());
// dd($token);

// $token = $table->pop(6);
// dd($token);
// dd($token);
// $table = new UserTable();
// $user = $table->find(5);
// $user->
// $user = new User();
// $user->setId(5);
// $user->login = 'Alanio';
// $user->email = 'panolo@mail.com';
// $user->name = 'Alan';
// $user->surname = 'Goody';
// $user->setBirth(new \DateTime());
// $user->password = 'password';
// $user->addRole(RoleFactory::createUserRole());
// $user->addRole(RoleFactory::createAdminRole());
// $table->save($user);
// dd($user);
// $table->delete();
// $table->save($user);
// $user->setId($table->lastInsertId());

// $user->save();
// $user = $table->find(4);
// dd($user);
// $user->setId($table->lastInsertId());
// dd($token);
