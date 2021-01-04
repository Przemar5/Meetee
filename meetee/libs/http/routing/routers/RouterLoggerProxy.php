<?php

namespace Meetee\Libs\Http\Routing\Routers;

use Meetee\Libs\Http\Routing\Routers\RouterTemplate;
use Meetee\Libs\Files\Factories\LoggerFactory;
use Meetee\Libs\Http\RequestDataCollector;
use Meetee\Libs\Files\Logger;

class RouterLoggerProxy extends RouterTemplate
{
	private RouterTemplate $router;
	private Logger $logger;

	public function __construct(RouterTemplate $router)
	{
		$this->route = $router;
		$this->logger = LoggerFactory::createRequestLogger();
	}

	public function route(string $name): void
	{
		$this->logRequestData();
		$this->router->route($name);
	}

	private function logRequestData(): void
	{
		$content = RequestDataCollector::getForLogging();
		$this->logger->append($content);
	}

	public function setLogger(Logger $logger): void
	{
		$this->logger = $logger;
	}
}