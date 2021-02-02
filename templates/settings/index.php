<?php $this->startSection('body'); ?>
Settings

<ol>
	<a href="<?php $this->renderRouteUri('settings_account_page'); ?>">Account</a>
	<a href="<?php $this->renderRouteUri('settings_privacy_page'); ?>">Privacy</a>
	<a href="">Appearance</a>
	<a href="<?php $this->renderRouteUri('reset_password_page'); ?>">Change password</a>
</ol>
<?php $this->endSection('body'); ?>