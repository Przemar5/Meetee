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

	public function findLastFromByAuthorIdAndParentId(
		int $last, 
		int $limit, 
		int $authorId,
		?int $parentId
	): ?array
	{
		die;
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
		$this->queryBuilder->where($conditions);
		$this->queryBuilder->orderDesc();
		$this->queryBuilder->orderBy(['id']);
		$this->queryBuilder->where($conditions);
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

		return $this->prepareRecursiveCommentsRawData(
			$this->database->findMany($query, $bindings));
	}

	public function prepareRecursiveCommentsRawData(array $comments)
	{
		$i;
		for ($i = 0; $i < count($comments); $i++) {
			if ($comments[$i]['parent_id'] === null)
				$comments[$i] = $this->appendSubcomment($comments, $comments[$i]);
			else
				break;
		}

		return array_slice($comments, 0, $i);
	}

	private function appendSubcomment(array $comments, array $parent) 
	{
		$parent['comments'] = [];

		for ($i = 0; $i < count($comments); $i++) {
			if ($comments[$i]['parent_id'] == $parent['id'])
				$parent['comments'][] = 
					$this->appendSubcomment($comments, $comments[$i]);
		}

		return $parent;
	}
}