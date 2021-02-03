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
		$comment->authorId = $user->getId();
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
		$this->queryBuilder->withRecursive('cte');
		$this->queryBuilder->in($this->name);
		$this->queryBuilder->select(['*']);

		/*
WITH RECURSIVE cte (id, content, parent_id) 
AS (
	SELECT c1.id, c1.content, c1.parent_id 
	FROM comments c1
	JOIN (
		SELECT id 
		FROM comments 
		WHERE parent_id is null 
		ORDER BY id DESC 
		LIMIT 1
	) AS c2 
	ON c1.id = c2.id 
	UNION ALL
		SELECT c3.id, c3.content, c3.parent_id 
		FROM comments c3 
		INNER JOIN cte 
		ON c3.parent_id = cte.id
) 
SELECT * FROM cte;


WITH RECURSIVE cte (id, content, parent_id) 
AS (
	SELECT c1.id, c1.content, c1.parent_id 
	FROM comments c1
	WHERE c1.id IN (
		SELECT id 
		FROM comments 
		WHERE parent_id is null 
		ORDER BY id DESC 
		LIMIT 1
	)
	UNION ALL
		SELECT c3.id, c3.content, c3.parent_id 
		FROM comments c3 
		INNER JOIN cte 
		ON c3.parent_id = cte.id
) 
SELECT * FROM cte;
		*/
	}
}