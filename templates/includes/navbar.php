<nav>
	<?php if ($this->isGranted('VERIFIED')): ?>
		<a href="<?= route('login_page'); ?>"></a>
		<div>
			<a href="<?= route('main_page'); ?>">Main</a>
			<a href="<?= route('profiles_show_page', ['id' => user()->getId()]); ?>">Profile</a>
			<a href="<?= route('settings_index_page'); ?>">Settings</a>
			<a href="<?= route('logout'); ?>">Logout</a>
		</div>
	<?php else: ?>
		<a href="<?= route('registration_page'); ?>">Register</a>
		<a href="<?= route('login_page'); ?>">Login</a>
	<?php endif; ?>
</nav>