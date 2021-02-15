<?php

namespace Meetee\App\Entities;

use Meetee\App\Entities\Entity;
use Meetee\App\Entities\User;
use Meetee\App\Entities\Traits\Timestamps;
use Meetee\App\Entities\Traits\SoftDelete;
use Meetee\Libs\Data_structures\Collection;

class Comment extends Entity
{
	use Timestamps;
	use SoftDelete;

	public string $content;
	public bool $onProfile;
	public int $authorId;
	public ?int $parentId = null;
	public ?int $topicId = null;
	public ?int $groupId = null;
	public Collection $comments;
	public array $tags = [];

	public function addComment(Comment $comment): void
	{
		$this->comments[] = $comment;
	}

	public function removeComment(Comment $comment): void
	{
		// if (in_array($comment, $this->comments))
		// 	$this->comments
	}

	public function setComments(Collection $comments): void
	{
		$this->comments = $comments;
	}

	public function getComments(): Collection
	{
		return $this->comments;
	}
}