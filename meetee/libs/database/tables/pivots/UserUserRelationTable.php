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
		string $relationName
	): void
	{
		$relationId = $this->getRelationIdByName($relationName);

		if ($this->areInRelation($first, $second, $relationName) || 
			$this->haveNotAcceptedRelation($first, $second, $relationName))
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

	public function areInRelation(
		User $first, 
		User $second, 
		string $relationName
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
			'relations.name' => $relationName,
		]);

		return !empty($this->getOneResult());
	}

	public function haveNotAcceptedRelation(
		User $first, 
		User $second, 
		string $relationName
	): bool
	{
		return !empty($this->getNotAcceptedRelation(
			$first, $second, $relationName));
	}

	public function getNotAcceptedRelation(
		User $first, 
		User $second, 
		string $relationName
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
			'relations.name' => $relationName,
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
		string $relationName
	): void
	{
		$relationId = $this->getRelationIdByName('FRIEND');

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

	public function acceptRelationIfHadBeenRequested(
		User $first, 
		User $second, 
		string $relationName
	): void
	{
		$request = $this->getNotAcceptedRelation($first, $second, $relationName);

		if (is_array($request) && $request['receiver_id'] == $first->getId())
			$this->acceptRelationRequest($first, $second, $relationName);
	}

	public function acceptRelationRequest(
		User $first, 
		User $second, 
		string $relationName
	): void
	{
		$relationId = $this->getRelationIdByName($relationName);

		$this->queryBuilder->reset();
		$this->queryBuilder->in($this->name);
		$this->queryBuilder->update([
			'accepted' => true,
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
			'user_user_relation.relation_id' => $relationId,
		]);

		$this->sendQuery();
	}

	public function removeRateForCommentByUser(
		Comment $comment,
		User $user
	): void
	{
		$this->queryBuilder->reset();
		$this->queryBuilder->in($this->name);
		$this->queryBuilder->delete();
		$this->queryBuilder->where([
			'comment_id' => $comment->getId(),
			'user_id' => $user->getId(),
		]);

		$this->sendQuery();
	}

	public function addRateForCommentByUser(
		Rate $rate,
		Comment $comment,
		User $user
	): void
	{
		$data = [
			'rate_id' => $rate->getId(),
			'comment_id' => $comment->getId(),
			'user_id' => $user->getId(),
		];

		$this->queryBuilder->reset();
		$this->queryBuilder->in($this->name);
		$this->queryBuilder->insert($data);

		$this->database->sendQuery(
			$this->queryBuilder->getResult(),
			$this->queryBuilder->getBindings()
		);
	}
}