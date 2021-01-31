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

	private ?int $id = null;
	public string $content;
	public int $authorId;
	public Collection $comments;

	public function addComment(Comment $comment): void
	{
		$this->comments[] = $comment;
	}

	public function removeComment(Comment $comment): void
	{
		// if (in_array($comment, $this->comments))
		// 	$this->comments
	}

	public function setId(int $id): void
	{
		if (isset($this->id) || is_null($this->id))
			$this->id = $id;
	}

	public function setComments(Collection $comments): void
	{
		$this->comments = $comments;
	}

	public function getId(): ?int
	{
		return $this->id;
	}

	public function getComments(): Collection
	{
		return $this->comments;
	}
}