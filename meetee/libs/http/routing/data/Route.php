<?php

namespace Meetee\Libs\Http\Routing\Data;

class Route
{
	private string $pattern;
	private string $method;
	private string $className;
	private ?string $name;

	public function __construct(
		string $pattern,
		string $method,
		string $className,
		?string $name
	)
	{
		$this->pattern = $pattern;
		$this->method = $method;
		$this->className = $className;
		$this->name = $name;
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
}