<?php

namespace Meetee\App\Controllers\Rest;

use Meetee\App\Controllers\Rest\RestController;
use Meetee\Libs\Database\Tables\UserTable;
use Meetee\Libs\Security\Validators\Compound\Users\NewUserLoginValidator;

class UserController extends RestController
{
	public function loginExists(string $login): void
	{
		$validator = new NewUserLoginValidator();

		if (!$validator->run($login))
			die(json_encode(['error' => $validator->errorMsg]));

		echo json_encode(['success' => true]);
	}
}