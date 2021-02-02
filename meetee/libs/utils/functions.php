<?php

use Meetee\Libs\Security\AuthFacade;
use Meetee\Libs\Http\Routing\Data\RouteFactory;
use Meetee\App\Entities\User;
use Meetee\App\Entities\Token;
use Meetee\App\Entities\Factories\TokenFactory;

function dd($data) {
	echo "<pre>";
	var_dump($data);
	die;
}

function route(string $name, ?array $args = []): ?string
{
	$route = RouteFactory::getRouteByName($name);

	return BASE_URI . ltrim($route->getPreparedUri($args), '/');
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