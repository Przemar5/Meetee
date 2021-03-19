<?php

namespace Meetee\Libs\Database\Tables;

use Meetee\Libs\Database\Tables\TableTemplate;
use Meetee\App\Entities\Entity;
use Meetee\App\Entities\Comment;
use Meetee\App\Entities\User;

class CommentTable extends TableTemplate
{
	public function __construct()
	{
		parent::__construct('comments', Comment::class, true);
	}

	protected function fetchEntity($data): Comment
	{
		$table = new UserTable();
		$user = $table->find($data['user_id']);

		$comment = new Comment();
		$comment->setId($data['id']);
		$comment->content = $data['content'];
		$comment->onProfile = $data['on_profile'];
		$comment->authorId = $user->getId();
		$comment->parentId = $data['parent_id'];
		$comment->topicId = $data['topic_id'];
		$comment->groupId = $data['group_id'];
		$comment->setCreatedAt($data['created_at']);
		$comment->setUpdatedAt($data['updated_at']);
		$comment->deleted = $data['deleted'];

		return $comment;
	}

	protected function getEntityData(Entity $comment): array
	{
		$this->throwExceptionIfInvalidClass($comment, Comment::class);

		$data = [];
		$data['id'] = $comment->getId();
		$data['content'] = $comment->content;
		$data['user_id'] = $comment->authorId;
		$data['parent_id'] = $comment->parentId;
		$data['topic_id'] = $comment->topicId;
		$data['group_id'] = $comment->groupId;
		$data['on_profile'] = $comment->onProfile;
		$data['deleted'] = $comment->deleted;

		return $data;
	}

	public function findByAuthor(User $user): ?array
	{
		return $this->findManyBy(['user_id' => $user->getId()]);
	}

	public function debug(): void
	{
		$this->database->debug();
	}

	public function findLastFromByAuthorIdAndParentId(
		int $last, 
		int $limit, 
		int $authorId,
		?int $parentId
	): ?array
	{
		$this->queryBuilder->reset();
		$this->queryBuilder->in($this->name);
		$this->queryBuilder->select(['*']);
		
		if (is_null($parentId)) {
			$this->queryBuilder->where(
				$this->prepareConditionsRespectSoftDelete([
					'id' => ['<', $last], 
					'user_id' => $authorId,
					'parent_id' => null,
				]));
		}
		else {
			$this->queryBuilder->where(
				$this->prepareConditionsRespectSoftDelete([
					'id' => ['<', $last], 
					'user_id' => $authorId,
					'parent_id' => $parentId,
				]));
		}

		$this->queryBuilder->limit($limit);
		$this->queryBuilder->orderBy(['id']);
		$this->queryBuilder->orderDesc();
		$this->queryBuilder->setAdditionalBindings(['id' => $last]);

		return $this->database->findMany(
			$this->queryBuilder->getResult(),
			$this->queryBuilder->getBindings()
		);
	}

	public function findBaseCommentsRecursiveBy(array $conditions)
	{
		return $this->findManyRecursiveBy($conditions, null);
	}

	public function findManyRecursiveBy(array $conditions, int $parentId = null)
	{
		$this->queryBuilder->reset();
		$this->queryBuilder->withRecursive(['id', 'content', 'parent_id']);
		$this->queryBuilder->in($this->name);
		$this->queryBuilder->select(['*']);
	}

	public function findDataRecursive(array $conditions, array $clauses = [])
	{
		$this->queryBuilder->reset();
		$this->queryBuilder->select(['*']);
		$this->queryBuilder->in($this->name);
		$this->queryBuilder->orderDesc();
		$this->queryBuilder->orderBy(['id']);
		$this->queryBuilder->where(
			$this->prepareConditionsRespectSoftDelete($conditions));
		$this->appendOptionalParts($clauses);
		
		$subQuery = $this->queryBuilder->getResult();
		$bindings = $this->queryBuilder->getBindings();

		$query = "WITH RECURSIVE cte (id, content, parent_id) 
		AS (
			SELECT c1.*
			FROM comments c1
			JOIN ($subQuery) AS c2 
			ON c1.id = c2.id 
			UNION ALL
				SELECT c3.*
				FROM comments c3 
				INNER JOIN cte 
				ON c3.parent_id = cte.id
		) 
		SELECT * FROM cte";

		// echo($query);
		// dd($bindings);

		return $this->prepareRecursiveCommentsRawData(
			$this->database->findMany($query, $bindings));
	}

	public function prepareRecursiveCommentsRawData(array $comments)
	{
		$reversed = [];
		
		for ($i = 0; $i < count($comments); $i++) {
			if ($comments[$i]['parent_id'] === null)
				array_push($reversed, $this->prependSubcomment(
					$comments, $comments[$i]));
			else
				break;
		}

		return $reversed;
	}

	private function prependSubcomment(array $comments, array $parent) 
	{
		$parent['comments'] = [];

		for ($i = 0; $i < count($comments); $i++) {
			if ($comments[$i]['parent_id'] == $parent['id'])
				array_push($parent['comments'], 
					$this->prependSubcomment($comments, $comments[$i]));
		}

		return $parent;
	}

	public function selectWhereUserFriendsPosted(User $user): ?array
	{
		$conditions = [
			'user_id' => array_map(fn($u) => $u->getId(), $user->getFriends()),
		];

		$result = $this->findDataRecursive($conditions);

		// $q = 'WITH RECURSIVE cte (id, content, parent_id) AS ( SELECT c1.* FROM comments c1 JOIN (SELECT * FROM comments WHERE ((user_id IN (:b_0)) AND (deleted = FALSE)) ORDER BY id DESC) AS c2 ON c1.id = c2.id UNION ALL SELECT c3.* FROM comments c3 INNER JOIN cte ON c3.parent_id = cte.id ) SELECT * FROM cte';
		// $r = $this->database->findMany($q, [':b_0' => 9]);
		// dd($r);
		dd($result);
		dd($this->database->debug());
		return [];
	}
}