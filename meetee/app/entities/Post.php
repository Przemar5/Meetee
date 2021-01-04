<?php

namespace Meetee\App\Entities;

use Meetee\App\Entities\Entity;
use Meetee\App\Entities\Comment;
use Meetee\App\Entities\User;
use Meetee\App\Entities\Traits\Timestamps;
use Meetee\App\Entities\Traits\SoftDelete;
use Meetee\Libs\Data_structures\Collection;

class Post extends Entity
{
	use Timestamps;
	use SoftDelete;

	private string $table;
	private int $id;
	private string $title;
	private string $content;
	private User $author;
	private Collection $comments;

	public function addComment(Comment $comment): void;

	public function removeComment(Comment $comment): void;

	public function setId(int $id): void;

	public function setTitle(string $title): void;

	public function setContent(string $content): void;
	
	public function setAuthor(User $user): void;

	public function setComments(Collection $comments): void;

	public function getId(): ?int;

	public function getTitle(): string;

	public function getContent(): string;
	
	public function getAuthor(): User;

	public function getComments(): Collection;
}