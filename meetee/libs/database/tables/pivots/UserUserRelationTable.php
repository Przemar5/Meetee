<?php

namespace Meetee\Libs\Database\Tables\Pivots;

use Meetee\Libs\Database\Tables\Pivots\Pivot;
use Meetee\Libs\Database\Tables\TableTemplate;
use Meetee\App\Entities\User;
use Meetee\App\Entities\Relation;

class UserUserRelationTable extends Pivot
{
	public function __construct()
	{
		parent::__construct('user_user_relation');
	}

	public function getRelationsOfUsers(
		User $first,
		User $second
	): array
	{
		$this->queryBuilder->reset();
		$this->queryBuilder->in($this->name);
		$this->queryBuilder->select(['relations.*']);
		$this->queryBuilder->join([
			'relations' => [
				'type' => 'INNER',
				'on' => 'user_user_relation.relation_id = relations.id',
			],
		]);
		$this->queryBuilder->where([
			'AND',
			[
				'OR',
				[
					'user_user_relation.sender_id' => $first->getId(),
					'user_user_relation.receiver_id' => $second->getId(),
				],
				[
					'user_user_relation.sender_id' => $second->getId(),
					'user_user_relation.receiver_id' => $first->getId(),
				],
			],
			[
				'user_user_relation.accepted' => true,
			],
		]);

		return $this->getManyResults();
	}

	public function requestForRelation(
		User $first, 
		User $second, 
		int $relationId
	): void
	{
		if ($this->areInRelation($first, $second, $relationId) || 
			$this->haveNotAcceptedRelation($first, $second, $relationId))
			return;

		$this->queryBuilder->reset();
		$this->queryBuilder->in($this->name);
		$this->queryBuilder->insert([
			'sender_id' => $first->getId(),
			'receiver_id' => $second->getId(),
			'relation_id' => $relationId,
			'accepted' => false,
		]);

		$this->sendQuery();
	}

	public function discardRelation(
		User $first,
		User $second,
		int $relationId
	): void
	{
		$this->queryBuilder->reset();
		$this->queryBuilder->in($this->name);
		$this->queryBuilder->delete();
		$this->queryBuilder->where([
			'sender_id' => $first->getId(),
			'receiver_id' => $second->getId(),
			'relation_id' => $relationId
		]);

		$this->sendQuery();
	}

	public function areInRelation(
		User $first, 
		User $second, 
		int $relationId
	): bool
	{
		$this->queryBuilder->reset();
		$this->queryBuilder->in($this->name);
		$this->queryBuilder->select([$this->name . '.*']);
		$this->queryBuilder->join([
			'relations' => [
				'type' => 'INNER',
				'on' => 'user_user_relation.relation_id = relations.id',
			],
		]);
		$this->queryBuilder->where([
			'AND',
			[
				'OR',
				[
					'user_user_relation.sender_id' => $first->getId(),
					'user_user_relation.receiver_id' => $second->getId(),
				],
				[
					'user_user_relation.sender_id' => $second->getId(),
					'user_user_relation.receiver_id' => $first->getId(),
				],
			],
			'user_user_relation.accepted' => true,
			'relations.id' => $relationId,
		]);

		return !empty($this->getOneResult());
	}

	public function cancelRequestedRelation(
		User $sender, 
		User $receiver, 
		int $relationId
	): void
	{
		$this->queryBuilder->reset();
		$this->queryBuilder->in($this->name);
		$this->queryBuilder->delete();
		$this->queryBuilder->where([
			'sender_id' => $sender->getId(),
			'receiver_id' => $receiver->getId(),
			'relation_id' => $relationId,
			'accepted' => false,
		]);

		$this->sendQuery();
	}

	public function haveNotAcceptedRelation(
		User $first, 
		User $second, 
		int $relationId
	): bool
	{
		return !empty($this->getNotAcceptedRelation(
			$first, $second, $relationId));
	}

	public function getNotAcceptedRelation(
		User $first, 
		User $second, 
		int $relationId
	)
	{
		$this->queryBuilder->reset();
		$this->queryBuilder->in($this->name);
		$this->queryBuilder->select([$this->name . '.*']);
		$this->queryBuilder->join([
			'relations' => [
				'type' => 'INNER',
				'on' => 'user_user_relation.relation_id = relations.id',
			],
		]);
		$this->queryBuilder->where([
			'AND',
			[
				'OR',
				[
					'user_user_relation.sender_id' => $first->getId(),
					'user_user_relation.receiver_id' => $second->getId(),
				],
				[
					'user_user_relation.sender_id' => $second->getId(),
					'user_user_relation.receiver_id' => $first->getId(),
				],
			],
			'user_user_relation.accepted' => false,
			'relations.id' => $relationId,
		]);

		return $this->getOneResult();
	}

	public function getNotAcceptedRequestsForUser(User $user)
	{
		$this->queryBuilder->reset();
		$this->queryBuilder->in($this->name);
		$this->queryBuilder->select([
			'user_user_relation.*', 'relations.name as relation_name', 
			'users.login', 'users.profile']);
		$this->queryBuilder->where([
			'AND',
			'accepted' => false,
			'receiver_id' => $user->getId(),
		]);
		$this->queryBuilder->join([
			'relations' => [
				'type' => 'INNER',
				'on' => 'user_user_relation.relation_id = relations.id',
			],
			'users' => [
				'type' => 'INNER',
				'on' => 'user_user_relation.sender_id = users.id',
			],
		]);

		return $this->getManyResults();
	}

	public function getRelationIdByName(string $name): ?int
	{
		$this->queryBuilder->reset();
		$this->queryBuilder->in('relations');
		$this->queryBuilder->select(['id']);
		$this->queryBuilder->where(['name' => $name]);

		$result = $this->getOneResult();

		return ($result) ? $result['id'] : null;
	}

	public function cancelRelation(
		User $first, 
		User $second, 
		int $relationId
	): void
	{
		$this->queryBuilder->reset();
		$this->queryBuilder->in($this->name);
		$this->queryBuilder->delete();
		$this->queryBuilder->where([
			'sender_id' => $first->getId(),
			'receiver_id' => $second->getId(),
			'relation_id' => $relationId,
		]);

		$this->sendQuery();
	}

	public function acceptRelationRequestIfRequested(
		User $sender, 
		User $receiver, 
		int $relationId
	): void
	{
		$this->queryBuilder->reset();
		$this->queryBuilder->in($this->name);
		$this->queryBuilder->update(['accepted' => true]);
		$this->queryBuilder->where([
			'sender_id' => $sender->getId(),
			'receiver_id' => $receiver->getId(),
			'relation_id' => $relationId,
			'accepted' => false,
		]);

		$this->sendQuery();
	}
}