<?php $this->startSection('head'); ?>
<script type="module" src="./public/js/app/pages/settings/account.js"></script>
<?php $this->endSection('head'); ?>


<?php $this->startSection('body'); ?>



Account

<label id="userName" class="toggable-form">
	Name: <?= $user->name ?? ''; ?>
	<button>Change</button>
</label>

<label id="userSecondName" class="toggable-form">
	Second name: <?= $user->name ?? ''; ?>
	<button>Change</button>
</label>

<label id="userSurname" class="toggable-form">
	Surname: <?= $user->surname ?? ''; ?>
	<button>Change</button>
</label>

<label id="userLogin" class="toggable-form">
	Login: <?= $user->login ?? ''; ?>
	<button>Change</button>
</label>

<label id="userEmail" class="toggable-form">
	Email: <?= $user->email ?? ''; ?>
	<button>Change</button>
</label>

<label id="userBirth" class="toggable-form">
	Birth: <?= $user->getBirth()->format('M j, Y') ?? ''; ?>
	<button>Change</button>
</label>

<a href="<?php $this->renderRouteUri('settings_index_page'); ?>">Return</a>

<?php $this->endSection('body'); ?>