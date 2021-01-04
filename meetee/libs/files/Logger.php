<?php

namespace Meetee\Libs\Files;

class Logger
{
	protected $file;

	public function __construct(string $path)
	{
		$this->throwExceptionWhenFileMissing($path);
		$this->throwExceptionWhenFileNotWritable($path);

		$this->file = fopen($path, 'a');
	}

	public function __destruct()
	{
		fclose($this->file);
	}

	public function append(string $content): void
	{
		fwrite($this->file, $content);
	}

	protected function throwExceptionWhenFileMissing(string $path): void
	{
		if (!file_exists($path))
			throw new \Exception(sprintf("File '%s' is missing.", $path));
	}

	protected function throwExceptionWhenFileNotWritable(string $path): void
	{
		if (!is_writable($path))
			throw new \Exception(sprintf("File '%s' is not writable.", $path));
	}
}