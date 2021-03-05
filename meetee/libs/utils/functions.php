<?php

use Meetee\Libs\Security\AuthFacade;
use Meetee\Libs\Http\Routing\Data\RouteFactory;
use Meetee\App\Entities\User;
use Meetee\App\Entities\Token;
use Meetee\App\Entities\Factories\TokenFactory;
use Meetee\Libs\Database\Tables\RelationTable;
use Meetee\Libs\Database\Tables\Pivots\UserUserRelationTable;

function dd($data) {
	echo "<pre>";
	var_dump($data);
	die;
}

function route(string $name, ?array $args = []): ?string
{
	$route = RouteFactory::getRouteByName($name);

	if ($route)
		return BASE_URI . ltrim($route->getPreparedUri($args), '/');
	else
		return null;
}

function method(string $name): ?string
{
	$route = RouteFactory::getRouteByName($name);

	return $route->getMethod();
}

function user(): ?User
{
	return AuthFacade::getLoggedUser();
}

function token(string $name): ?Token
{
	return TokenFactory::generate($name, user());
}

function idForRelation(string $relationName): ?int
{
	$table = new RelationTable();
	$relation = $table->findOneBy(['name' => $relationName]);

	return ($relation) ? $relation->getId() : null;
}

function relationRequestSend(User $sender, User $receiver, int $relationId): bool
{
	$table = new UserUserRelationTable();
	
	return !empty(
		$table->findOneBy([
			'sender_id' => $sender->getId(), 
			'receiver_id' => $receiver->getId(), 
			'relation_id' => $relationId,
			'accepted' => false,
		]));
}

function areInRelation(User $first, User $second, int $relationId): bool
{
	$table = new UserUserRelationTable();
	
	return $table->areInRelation($first, $second, $relationId);
}

function getRelationRequests(User $user): array
{
	$table = new UserUserRelationTable();
	
	return $table->getNotAcceptedRequestsForUser($user);
}