<?php $this->startSection('head'); ?>

<?php $this->endSection('head'); ?>


<?php $this->startSection('body'); ?>

Account

<label>
	Name: <?= $user->name ?? ''; ?>
	<button>Change</button>
</label>

<label>
	Second name: <?= $user->name ?? ''; ?>
	<button>Change</button>
</label>

<label>
	Surname: <?= $user->surname ?? ''; ?>
	<button>Change</button>
</label>

<label>
	Login: <?= $user->login ?? ''; ?>
	<button>Change</button>
</label>

<label>
	Email: <?= $user->email ?? ''; ?>
	<button>Change</button>
</label>

<label>
	Birth: <?= $user->birth ?? ''; ?>
	<button>Change</button>
</label>

<a href="<?php $this->renderRouteUri('settings_index_page'); ?>">Return</a>

<?php $this->endSection('body'); ?>