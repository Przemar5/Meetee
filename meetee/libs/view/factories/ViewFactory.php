<?php

namespace Meetee\Libs\View\Factories;

use Meetee\Libs\View\BrowserView;

class ViewFactory
{
	public static function createDefaultBrowserView(): BrowserView
	{
		$view = new BrowserView();
		$view->setDirectory('templates');
		$view->setLayout('layouts/default');
		$view->setExtension('php');

		return $view;
	}
}