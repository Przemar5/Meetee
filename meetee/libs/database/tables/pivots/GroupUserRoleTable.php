<?php

namespace Meetee\Libs\Database\Tables\Pivots;

use Meetee\Libs\Database\Tables\Pivots\Pivot;
use Meetee\Libs\Database\Tables\TableTemplate;
use Meetee\App\Entities\Group;
use Meetee\App\Entities\User;
use Meetee\App\Entities\Role;

class GroupUserRoleTable extends Pivot
{
	public function __construct()
	{
		parent::__construct('group_user_role');
	}

	public function isUserInGroup(User $user, Group $group): bool
	{
		$roles = $this->getUserRolesInGroup($user, $group);

		return !empty($roles);
	}

	public function userHasRoleIdInGroup(
		User $user, 
		int $roleId, 
		Group $group
	): bool
	{
		$roles = $this->getUserRolesInGroup($user, $group);
		
		foreach ($roles as $role) {
			if ($role['id'] == $roleId)
				return true;
		}

		return false;
	}

	public function userHasRequestedForRoleIdInGroup(
		User $user, 
		int $roleId, 
		Group $group
	): bool
	{
		$requests = $this->getUserRoleRequestsInGroup($user, $group);

		foreach ($requests as $request) {
			if ($request['id'] == $roleId)
				return true;
		}

		return false;
	}

	public function getUserRolesInGroup(User $user, Group $group): ?array
	{
		return $this->getUserRequestInGroupWhereAcceptedHasValue(
			$user, $group, true);
	}

	public function getUserRoleRequestsInGroup(User $user, Group $group): ?array
	{
		return $this->getUserRequestInGroupWhereAcceptedHasValue(
			$user, $group, false);
	}

	public function getUserRequestInGroupWhereAcceptedHasValue(
		User $user, 
		Group $group,
		bool $accepted
	): ?array
	{
		$this->queryBuilder->reset();
		$this->queryBuilder->select(['group_roles.*']);
		$this->queryBuilder->in($this->name);
		$this->queryBuilder->join([
			'group_roles' => [
				'type' => 'INNER',
				'on' => 'group_user_role.role_id = group_roles.id',
			],
		]);
		$this->queryBuilder->where([
			'group_user_role.user_id' => $user->getId(),
			'group_user_role.group_id' => $group->getId(),
			'group_user_role.accepted' => $accepted,
		]);

		return $this->getManyResults();
	}

	public function getUsersOfGroup(Group $group): ?array
	{
		$this->queryBuilder->reset();
		$this->queryBuilder->select(['users.*']);
		$this->queryBuilder->in($this->name);
		$this->queryBuilder->join([
			'users' => [
				'type' => 'INNER',
				'on' => 'group_user_role.user_id = users.id',
			],
		]);
		$this->queryBuilder->where([
			'group_user_role.group_id' => $group->getId(),
			'group_user_role.accepted' => true,
		]);

		return $this->getManyResults();
	}

	public function getUsersOfGroupByRoleId(Group $group, int $roleId): ?array
	{
		$this->queryBuilder->reset();
		$this->queryBuilder->select(['users.*']);
		$this->queryBuilder->in($this->name);
		$this->queryBuilder->join([
			'users' => [
				'type' => 'INNER',
				'on' => 'group_user_role.user_id = users.id',
			],
		]);
		$this->queryBuilder->where([
			'group_user_role.group_id' => $group->getId(),
			'group_user_role.role_id' => $roleId,
			'group_user_role.accepted' => true,
		]);

		return $this->getManyResults();
	}

	public function deleteUserRequestFromGroupWhereRoleId(
		User $user, 
		Group $group, 
		int $roleId
	): void
	{
		$this->deleteUserRequestFromGroupWhereRoleId(
			$user, $group, false, $roleId);
	}

	public function deleteUserFromGroupWhereHisRoleId(
		User $user, 
		Group $group, 
		int $roleId
	): void
	{
		$this->deleteUserRequestFromGroupWhereRoleId(
			$user, $group, true, $roleId);
	}

	public function deleteUserFromGroupWhereAcceptedHasValueAndRoleId(
		User $user, 
		Group $group, 
		bool $accepted,
		int $roleId
	): void
	{
		$this->queryBuilder->reset();
		$this->queryBuilder->delete();
		$this->queryBuilder->in($this->name);
		$this->queryBuilder->where([
			'user_id' => $user->getId(),
			'group_id' => $group->getId(),
			'role_id' => $roleId,
			'accepted' => $accepted,
		]);

		$this->sendQuery();
	}

	public function acceptUserInGroupOnRoleId(
		User $user, 
		Group $group, 
		int $roleId
	): void
	{
		$this->queryBuilder->reset();
		$this->queryBuilder->in($this->name);
		$this->queryBuilder->update([
			'accepted' => true,
		]);
		$this->queryBuilder->where([
			'user_id' => $user->getId(),
			'group_id' => $group->getId(),
			'role_id' => $roleId,
		]);

		$this->sendQuery();
	}

	public function rejectUserInGroupOnRoleId(
		User $user, 
		Group $group, 
		int $roleId
	): void
	{
		$this->queryBuilder->reset();
		$this->queryBuilder->in($this->name);
		$this->queryBuilder->delete();
		$this->queryBuilder->where([
			'user_id' => $user->getId(),
			'group_id' => $group->getId(),
			'role_id' => $roleId,
		]);

		$this->sendQuery();
	}

	public function makeUserGroupRequestForRoleId(
		User $user,
		Group $group,
		int $roleId
	): void
	{
		$this->queryBuilder->reset();
		$this->queryBuilder->in($this->name);
		$this->queryBuilder->insert([
			'user_id' => $user->getId(),
			'group_id' => $group->getId(),
			'role_id' => $roleId,
			'accepted' => false,
		]);

		$this->sendQuery();
	}

	public function giveUserRoleIdForGroup(
		User $user,
		int $roleId,
		Group $group,
	): void
	{
		$this->queryBuilder->reset();
		$this->queryBuilder->in($this->name);
		$this->queryBuilder->insert([
			'user_id' => $user->getId(),
			'group_id' => $group->getId(),
			'role_id' => $roleId,
			'accepted' => true,
		]);

		$this->sendQuery();
	}

	public function giveUserMultipleRoleIdsForGroup(
		User $user,
		array $roleIds,
		Group $group
	): void
	{
		$insertions = [];

		foreach ($roleIds as $roleId) {
			$insertions[] = [
				'user_id' => $user->getId(),
				'group_id' => $group->getId(),
				'role_id' => $roleId,
				'accepted' => true,
			];
		}

		$this->queryBuilder->reset();
		$this->queryBuilder->in($this->name);
		$this->queryBuilder->insertMultiple($insertions);

		$this->sendQuery();
	}

	public function hasUserOneOfMultipleRoleIdsForGroup(
		User $user,
		array $roleIds,
		Group $group
	): bool
	{
		$conditions = [
			'AND',
			'user_id' => $user->getId(),
			'group_id' => $group->getId(),
			'accepted' => true,
			[
				'OR',
			],
		];

		foreach ($roleIds as $roleId) {
			$conditions[1][] = [
				'role_id' => $roleId,
			];
		}

		$this->queryBuilder->reset();
		$this->queryBuilder->in($this->name);
		$this->queryBuilder->select(['*']);
		$this->queryBuilder->where($conditions);

		return !empty($this->getManyResults());
	}
}