<?php

namespace Meetee\App\Controllers;

use Meetee\App\Controllers\ControllerTemplate;
use Meetee\App\Entities\Group;
use Meetee\App\Entities\Utils\TokenFacade;
use Meetee\App\Entities\Sorting\GroupCommonSort;
use Meetee\Libs\Database\Tables\GroupTable;
use Meetee\App\Entities\Factories\TokenFactory;
use Meetee\Libs\Security\AuthFacade;

class GroupController extends ControllerTemplate
{
	private static string $tokenName = 'csrf_token';
	private array $errors = [];

	public function pageIndex(): void
	{
		$user = AuthFacade::getUser();
		$groups = $this->selectGroups();
		$token = TokenFactory::generate(self::$tokenName, $user);

		$this->render('groups/index', [
			'groups' => $groups,
			'token' => $token,
		]);
	}

	public function selectGroups(array $conditions = []): ?array
	{
		$table = new GroupTable();
		$sorter = new GroupCommonSort();
		$groups = $table->findManyBy($conditions);

		return $sorter->sort($groups);
	}

	public function pageCreate(): void
	{
		$user = AuthFacade::getUser();
		$token = TokenFactory::generate(self::$tokenName, $user);

		$this->render('groups/create', [
			'user' => $user,
			'token' => $token,
		]);
	}

	public function pageShow($id): void
	{
		$user = AuthFacade::getUser();
		$group = $this->getGroupIfIdValid($id);
		$token = TokenFactory::generate(self::$tokenName, $user);

		$this->render('groups/show', [
			'group' => $group,
			'token' => $token,
		]);
	}

	private function getGroupIfIdValid($id): ?Group
	{
		if (!preg_match('/^[1-9][0-9]*$/', $id))
			return null;
		
		$table = new GroupTable();
		
		return $table->find((int) $id);
	}
}