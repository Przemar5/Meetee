<?php

namespace Meetee\Libs\Database\Tables\Pivots;

use Meetee\Libs\Database\Tables\Pivots\Pivot;
use Meetee\Libs\Database\Tables\TableTemplate;
use Meetee\App\Entities\Comment;
use Meetee\App\Entities\Tag;

class CommentTagTable extends Pivot
{
	public function __construct()
	{
		parent::__construct('comment_tag');
	}

	public function findTagsForCommentId(int $id): ?array
	{
		$this->queryBuilder->reset();
		$this->queryBuilder->select(['tags.*']);
		$this->queryBuilder->in('tags');
		$this->queryBuilder->join([
			'comment_tag' => [
				'type' => 'INNER',
				'on' => 'comment_tag.tag_id = tags.id',
			],
		]);
		$this->queryBuilder->where(['comment_tag.comment_id' => $id]);

		$data = $this->database->findMany(
			$this->queryBuilder->getResult(),
			$this->queryBuilder->getBindings()
		);

		return $this->createManyRolesFromRawData($data);
	}

	private function createManyTagsFromRawData(array $data): array
	{
		$tags = [];

		foreach ($data as $tagData)
			$tags[] = $this->createTagFromRawData($tagData);

		return $tags;
	}

	private function createTagFromRawData(array $data): Role
	{
		$tag = new Tag();
		$tag->setId($data['id']);
		$tag->name = $data['name'];

		return $tag;
	}

	public function setTagsForComment(Comment $comment): void
	{
		$tags = $comment->tags;
		$tagIds = array_map(fn($t) => $t->getId(), $tags);
		$tagIds = array_filter($tagIds, fn($id) => !is_null($id));

		if (empty($comment->getId()))
			return;

		$tagId = $comment->getId();

		$this->removeTagsForComment($comment);
		$this->addTagsForComment($comment);
	}

	private function addTagsForComment(Comment $comment): void
	{
		$tags = $comment->tags;
		$data = array_map(fn($t) => [
			'comment_id' => $comment->getId(), 
			'tag_id' => $t->getId(),
		], $tags);

		$this->queryBuilder->in($this->name);
		$this->queryBuilder->insertMultiple($data);
		
		$this->database->sendQuery(
			$this->queryBuilder->getResult(),
			$this->queryBuilder->getAdditionalBindings()
		);
	}

	private function removeTagsForComment(Comment $comment): void
	{
		$id = $comment->getId();

		$this->queryBuilder->in($this->name);
		$this->queryBuilder->delete();
		$this->queryBuilder->where(['comment_id' => $id]);
		
		$this->database->sendQuery(
			$this->queryBuilder->getResult(),
			$this->queryBuilder->getBindings()
		);
	}
}