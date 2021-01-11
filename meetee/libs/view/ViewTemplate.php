<?php

namespace Meetee\Libs\View;

abstract class ViewTemplate
{
	protected string $layout;
	protected string $directory;
	protected string $extension;
	protected array $sections;
	protected ?string $currentSection = null;

	abstract public function render(string $path, ?array $args = []): void;

	public function getRendered(string $path, ?array $args = []): string
	{
		ob_start();
		$this->render($path, $args);
		return ob_get_clean();
	}

	protected function startSection(string $name): void
	{
		$this->currentSection = $name;
		ob_start();
	}

	protected function endSection(): void
	{
		if (is_null($this->currentSection))
			throw new \Exception("Section didn't started.");

		$this->sections[$this->currentSection] = ob_get_clean();
		$this->currentSection = null;
	}

	protected function section(string $name): string
	{
		if (!isset($this->sections[$name]))
			return '';

		if (!is_string($this->sections[$name]))
			throw new \Exception(sprintf("Section '%s' must be string.", $name));

		return $this->sections[$name];
	}

	protected function getTemplatePathIfValid(string $path): string
	{
		$this->throwExceptionIfDirectoryMissing();
		$this->throwExceptionIfExtensionMissing();

		$path = $this->getTemplatePath($path);
		
		$this->throwExceptionIfFileMissing($path);
		$this->throwExceptionIfFileNotReadable($path);

		return $path;
	}

	protected function getTemplatePath(string $path): string
	{
		$path = sprintf("%s/%s.%s", $this->directory, $path, $this->extension);

		return str_replace('/', DIRECTORY_SEPARATOR, $path);
	}

	protected function throwExceptionIfDirectoryMissing(): void
	{
		if (!isset($this->directory))
			throw new \Exception(
				sprintf("File '%s' base diretory must be specified.", 
					$this->directory));
	}

	protected function throwExceptionIfExtensionMissing(): void
	{
		if (!isset($this->extension))
			throw new \Exception(
				sprintf("File '%s' base diretory must be specified.", 
					$this->directory));
	}

	protected function throwExceptionIfFileMissing(string $path): void
	{
		if (!file_exists($this->directory))
			throw new Exception(
				sprintf("File '%s' is missing.", $this->directory));
	}

	protected function throwExceptionIfFileNotReadable(string $path): void
	{
		if (!is_readable($this->directory))
			throw new Exception(
				sprintf("File '%s' is not readable.", $this->directory));
	}

	public function setLayout(string $layout): void
	{
		$this->layout = $layout;
	}

	public function setDirectory(string $directory): void
	{
		$this->directory = $directory;
	}

	public function setExtension(string $extension): void
	{
		$this->extension = $extension;
	}
}