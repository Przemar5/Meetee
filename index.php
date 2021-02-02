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


$where = [
	'OR' => [
		'AND' => [
			'a' => 'something',
			'b' => null,
			'c' => false,
			'd' => true,
		],
		'AND' => [
			'e' => ['=', 1],
			'f' => ['<', 1],
			'g' => ['<=', 1],
		],
	],
];
$bindings = [];

function parse($data) {
	$result = '';
	foreach ($data as $key => $value) {
		if (is_string($value)) {
			$bindings = ["$key" => $value];
			return "$key = :$key";
		}
		elseif (is_bool($value)) {
			return ($value) ? "$key = TRUE" : "$key = FALSE";
		}
		elseif (is_integer($value)) {
			$bindings = ["$key" => $value];
			return "$key = :$key";
		}
		elseif (is_array($value)) {
			if (is_string($value[0]) && is_integer($value[1]) && 
				in_array($value[0], ['=', '<', '>', '<=', '>='])) {
				return ":$key " . $value[0] . " :$key";
			}
			else {
				$results = array_map($)
				return 
			}
			// $
			// return ""
		}
	}
	return $result;
}

echo parse($where);
die;





try {
	$router = RouterFactory::createComplete();
	$router->route();
}
catch (\Exception $e) {
	die($e);
}