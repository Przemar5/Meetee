<?php

namespace Meetee\Libs\Http\Routing\Data;

class Route
{
	private string $pattern;
	private string $method;
	private string $className;
	private ?string $name;
	private array $access;

	public function __construct(
		string $pattern,
		string $method,
		string $className,
		?string $name,
		?array $access = []
	)
	{
		$this->pattern = $pattern;
		$this->method = $method;
		$this->className = $className;
		$this->name = $name;
		$this->access = $access;
	}

	public function matchByUriAndMethod(string $uri, string $method): bool
	{
		$pattern = $this->getUriPatternRegex();

		return preg_match($pattern, $uri) && $this->method === $method;
	}

	protected function getUriPatternRegex(): string
	{
		$uri = preg_replace(['/(\{[^\:]*\:)/', '/(\})/'], '', $this->pattern);

		return sprintf("/^%s$/", addcslashes($uri, '/'));
	}

	protected function getUriPatternRegexPartByName(string $name): ?string
	{
		$found = [];
		preg_match(sprintf('/\{%s\:([^\}]*)\}/', $name), $this->pattern, $found);

		return $found[0] ?? null;
	}

	protected function getPatternWithInsertedArgs(?array $args = []): string
	{
		$parts = array_keys($args);
		$values = array_values($args);
		$toReplace = array_map(fn($p) => '/(\{'.$p.'\:\([^\)]*\)\})/', $parts);

		return preg_replace($toReplace, $values, $this->pattern);
	}

	public function getArgsForUri(string $uri): array
	{
		$pattern = $this->preparePattern();
		$args = [];
		preg_match($pattern, $uri, $args);

		return array_slice($args, 1);
	}

	public function getPreparedUri(?array $args = []): string
	{
		$extracted = [];
		preg_match_all('/(?:\{)(\w+)(?:\:[^\}]*\})/', $this->pattern, $extracted);
		[$patterns, $keys] = $extracted;

		foreach ($keys as $key)
			if (!isset($args[$key]))
				throw new \Exception(
					sprintf("route URI argument '%s' is missing.", $key));

		$values = array_map(fn($k) => $args[$k], $keys);

		return str_replace($patterns, $values, $this->pattern);
	}

	public function accepts(string $role): bool
	{
		return in_array($role, $this->access);
	}

	public function getPattern(): string
	{
		return $this->pattern;
	}
	
	public function getMethod(): string
	{
		return $this->method;
	}
	
	public function getClassName(): string
	{
		return $this->className;
	}
	
	public function getName(): ?string
	{
		return $this->name;
	}
	
	public function getAccess(): array
	{
		return $this->access;
	}
}