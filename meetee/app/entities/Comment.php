<?php

namespace Meetee\App\Entities;

use Meetee\App\Entities\Entity;
use Meetee\App\Entities\User;
use Meetee\App\Entities\Post;
use Meetee\App\Entities\Visitors\CommentVisitor;
use Meetee\Libs\Data_structures\Visitor\Station;
use Meetee\Libs\Data_structures\Collection;

class Comment extends Entity implements Station
{
	private string $table;
	private int $id;
	private string $title;
	private string $content;
	private User $author;
	private Post $post;
	private Collection $comments;

	public function accept(CommentVisitor $visitor): void;

	public function addComment(Comment $comment): void;

	public function removeComment(Comment $comment): void;

	public function setId(int $id): void;

	public function setTitle(string $title): void;

	public function setContent(string $content): void;
	
	public function setAuthor(User $user): void;

	public function setPost(Post $post): void;

	public function setComments(Collection $commants): void;

	public function getId(): ?int;

	public function getTitle(): string;

	public function getContent(): string;
	
	public function getAuthor(): User;

	public function getPost(): Post;

	public function getComments(): Collection;
}