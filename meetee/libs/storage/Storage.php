<?php

namespace Meetee\Libs\Storage;

interface Storage
{
	public static function get(string $name): mixed;

	public static function set(string $name, mixed $value): void;

	public static function isset(string $name): bool;

	public static function unset(string $name): void;
}