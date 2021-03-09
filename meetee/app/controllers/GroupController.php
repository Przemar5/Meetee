<?php

namespace Meetee\App\Controllers;

use Meetee\App\Controllers\ControllerTemplate;
use Meetee\App\Entities\Group;
use Meetee\App\Entities\User;
use Meetee\App\Entities\Utils\TokenFacade;
use Meetee\App\Entities\Sorting\GroupCommonSort;
use Meetee\Libs\Database\Tables\GroupTable;
use Meetee\Libs\Database\Tables\UserTable;
use Meetee\Libs\Database\Tables\Pivots\GroupUserRoleTable;
use Meetee\App\Entities\Factories\TokenFactory;
use Meetee\Libs\Security\AuthFacade;
use Meetee\Libs\Security\Validators\Compound\Groups\GroupIdValidator;
use Meetee\Libs\Security\Validators\Compound\Users\UserIdValidator;
use Meetee\Libs\Security\Validators\Compound\GroupRoles\GroupRoleIdValidator;

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

	public function request($groupId, $userId, $roleId): void
	{
		try {
			$this->dieIfTokenInvalid(self::$tokenName);
			$this->trimPostedValues();
			$this->dieIfGroupIdIvalid($groupId);
			$this->dieIfUserIdIvalid($userId);
			$this->dieIfGroupRoleIdIvalid($roleId);

			$this->makeRequestAndPrintResponse(
				(int) $groupId, (int) $userId, (int) $roleId);
		}
		catch (Exception $e) {
			die($e->getMessage());
		}
	}

	private function dieIfTokenInvalid(string $name): void
	{
		$user = AuthFacade::getUser();

		if (!TokenFacade::getTokenFromPostRequestIfValidByNameAndUser(
			$name, $user)) {
			die;
		}
	}

	private function dieIfGroupIdIvalid($id): void
	{
		$this->dieIfValueNotNumericAndInvalid(
			$id, new GroupIdValidator());
	}

	private function dieIfUserIdIvalid($id): void
	{
		$this->dieIfValueNotNumericAndInvalid(
			$id, new UserIdValidator());
	}

	private function dieIfGroupRoleIdIvalid($id): void
	{
		$this->dieIfValueNotNumericAndInvalid(
			$id, new GroupRoleIdValidator());
	}

	private function dieIfValueNotNumericAndInvalid($value, $validator): void
	{
		if (!preg_match('/^[1-9][0-9]*$/', $value) ||
			!$validator->run((int) $value))
			die;
	}

	private function makeRequestAndPrintResponse(
		int $groupId, 
		int $userId, 
		int $roleId
	): void
	{
		$table = new GroupUserRoleTable();
		$user = $this->getUserById($userId);
		$group = $this->getGroupById($groupId);

		$table->makeUserGroupRequestForRoleId($user, $group, $roleId);
		$this->printJsonResponseAndDie(
			'You have requested for that. Wait until' . 
			' your request will be approved.');
	}

	private function getUserById(int $id): ?User
	{
		$table = new UserTable();

		return $table->find($id);
	}

	private function getGroupById(int $id): ?Group
	{
		$table = new GroupTable();

		return $table->find($id);
	}

	private function printJsonResponseAndDie(string $message): void
	{
		die(json_encode(['message' => $message]));
	}

	public function discard($groupId, $userId, $roleId): void
	{
		try {
			$this->dieIfTokenInvalid(self::$tokenName);
			$this->trimPostedValues();
			$this->dieIfGroupIdIvalid($groupId);
			$this->dieIfUserIdIvalid($userId);
			$this->dieIfGroupRoleIdIvalid($roleId);

			$this->discardUserRoleFromGroupAndPrintResponse(
				(int) $groupId, (int) $userId, (int) $roleId);
		}
		catch (Exception $e) {
			die($e->getMessage());
		}
	}

	private function discardUserRoleFromGroupAndPrintResponse(
		int $groupId, 
		int $userId, 
		int $roleId
	): void
	{
		$table = new GroupUserRoleTable();
		$user = $this->getUserById($userId);
		$group = $this->getGroupById($groupId);

		$table->rejectUserInGroupOnRoleId($user, $group, $roleId);
		$this->printJsonResponseAndDie('Everything done right.');
	}

	public function acceptRequest($groupId, $userId, $roleId): void
	{
		try {
			$this->dieIfTokenInvalid(self::$tokenName);
			$this->trimPostedValues();
			$this->dieIfGroupIdIvalid($groupId);
			$this->dieIfUserIdIvalid($userId);
			$this->dieIfGroupRoleIdIvalid($roleId);
			$this->dieIfLoggedUserHasNotPermissonInGroupToAcceptRole(
				(int) $groupId, (int) $roleId);

			$this->acceptUserRoleInGroupAndPrintResponse(
				(int) $groupId, (int) $userId, (int) $roleId);
		}
		catch (Exception $e) {
			die($e->getMessage());
		}
	}

	private function dieIfLoggedUserHasNotPermissonInGroupToAcceptRole(
		int $groupId,
		int $roleId
	): void
	{
		$table = new GroupTable();
		$user = AuthFacade::getLoggedUser();
		$group = $table->find($groupId);

		if (!$user->hasRole('ADMIN') && !$user->hasRole('OWNER') && 
			!$user->hasRoleInGroup('ADMIN', $group) &&
			!$user->hasRoleInGroup('CREATOR', $group))
			die;

		if ($roleId == 3 && !$user->hasRoleInGroup('CREATOR', $group))
			die;
	}

	private function acceptUserRoleInGroupAndPrintResponse(
		int $groupId, 
		int $userId, 
		int $roleId
	): void
	{
		$table = new GroupUserRoleTable();
		$user = $this->getUserById($userId);
		$group = $this->getGroupById($groupId);
		
		$table->acceptUserInGroupOnRoleId($user, $group, $roleId);
		$this->printJsonResponseAndDie('Request has been accepted.');
	}
}