<nav>
	<?php if ($this->isGranted('VERIFIED')): ?>
		<a href="<?= $this->renderRouteUri('main_page'); ?>">Main</a>
		<a href="<?= $this->renderRouteUri('settings_index_page'); ?>">Settings</a>
		<a href="<?= $this->renderRouteUri('logout'); ?>">Logout</a>
	<?php else: ?>
		<a href="<?= $this->renderRouteUri('registration_page'); ?>">Register</a>
		<a href="<?= $this->renderRouteUri('login_page'); ?>">Login</a>
	<?php endif; ?>
</nav>