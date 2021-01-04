<?php

namespace Meetee\App\Chat;

use Meetee\App\Entities\Entity;
use Meetee\App\Chat\Client;
use Meetee\App\Chat\Conversation;

class Message extends Entity
{
	private string $table;
	private ?int $id;
	private string $content;
	private Client $author;
	private Conversation $conversation;

	public function setId(int $id): void;

	public function setContent(string $content): void;

	public function setAuthor(Client $author): void;

	public function setConversation(Conversation $conversation): void;

	public function getId(): ?int;

	public function getContent(): string;

	public function getAuthor(): Client;

	public function getConversation(): Conversation;
}