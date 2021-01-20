<?php

namespace Meetee\Libs\View\Factories;

use Meetee\Libs\View\BrowserView;
use Meetee\App\Emails\HtmlEmail;

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

	public static function createHtmlEmailView(): BrowserView
	{
		$view = new BrowserView();
		$view->setDirectory('templates');
		$view->setLayout('layouts/email');
		$view->setExtension('php');

		return $view;
	}
}