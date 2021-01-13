<?php

namespace Meetee\App\Chat;

use Meetee\App\Entities\Entity;
use Meetee\App\Chat\Client;
use Meetee\Libs\Data_structures\Collection;

class Conversation extends Entity implements \SplSubject
{
	private string $table;
	private ?int $id;
	private Collection $users;

	public function sendMessage(): void;

	public function attach(Client $client): void;

	public function detach(Client $client): void;

	public function notify(): void;

	public function setId(int $id): void;

	public function setUsers(Collection $users): void;

	public function getId(): ?int;

	public function getUsers(): Collection;
}