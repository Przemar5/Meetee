<?php

namespace Meetee\Libs\Files;

class File
{
	protected $file;

	public function __construct(string $path, ?string $flags = null)
	{
		$this->file = fopen($path, $flags);
	}

	public function __destruct()
	{
		fclose($this->file);
	}

	public function size(): int;// filesize()

	public function read(?int $start = 0, ?int $end = 0): string;

	public function write(string $content): void;

	public function insert(string $content, int $position = 0): void;

	public function append(string $content): void;

	public function delete(): void;
}