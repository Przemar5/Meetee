export default class Comment {
	constructor (template, data) {
		this.prepareTemplate(template, data)
	}

	prepareTemplate (template, data) {
		this.template = template.content.cloneNode(true)
		let modificationType = this.template.querySelector('.comment__modification--type')
		let modificationDate = this.template.querySelector('.comment__modification--date')
		let formEdit = this.template.querySelector('.comment__form--edit')
		let formDelete = this.template.querySelector('.comment__form--delete')
		let contentInput = this.template.querySelector('.comment__content')
		let errorSpan = this.template.querySelector('.error-msg')
		let contentView = this.template.querySelector('.comment__content--view')
		let btnSave = this.template.querySelector('.comment__button--save')
		let btnEdit = this.template.querySelector('.comment__button--edit')
		let btnDelete = this.template.querySelector('.comment__button--delete')
		let route

		try {
			modificationType.innerText = (data['created_at'] < data['updated_at'])
				? 'Updated at:' : 'Created at:'
			modificationDate.innerText = data['updated_at']
			modificationDate.setAttribute('datetime', data['updated_at'])
			
			route = RouteDispatcher.getRouteUri('comments_update_process', {'id': data['id']})
			formEdit.setAttribute('action', route)
			formEdit.addEventListener('submit', this.updateSubmitEvent)

			route = RouteDispatcher.getRouteUri('comments_delete_process', {'id': data['id']})
			formDelete.setAttribute('action', route)
			formDelete.addEventListener('submit', this.deleteSubmitEvent)

			contentInput.innerText = data['content']
			contentInput.addEventListener('keyup', this.inputKeyEvent)
			contentInput.addEventListener('keydown', this.inputKeyEvent)
			
			contentView.innerText = data['content']

			btnEdit.addEventListener('click', (e) => this.toggleFormEvent(e.target))

			this.prepareRatingForm(this.template, data['id'])
		} catch (e) {
			console.log(e)
		}
	}

	rateEvent = (e) => {
		e.preventDefault()
		let formData = new FormData(e.target)
		let uri = e.target.getAttribute('action')
		let request = new Request()

		request.post(uri, formData, (data) => {console.log(data)}, () => { console.log(0) })
	}

	toggleFormEvent = (form) => {
		let container = form.closest('.comment')
		container.querySelector('.toggable-form').classList.toggle('display-none')
		container.querySelector('.toggable-text').classList.toggle('display-none')
	}

	inputKeyEvent = (e) => {
		let value = e.target.value.trim()
		let form = e.target.closest('form')
		let errorMsgDiv = form.querySelector('.error-msg')
		let btnSubmit = form.querySelector('.comment__button--save')

		try {
			if (commentBodyValidator(value)) {
				errorMsgDiv.innerText = ''
				btnSubmit.removeAttribute('disabled')
			}
		} catch (e) {
			errorMsgDiv.innerText = e.message
			btnSubmit.setAttribute('disabled', true)
		}
	}

	updateSubmitEvent = (e) => {
		e.preventDefault()
		let formData = new FormData(e.target)
		let uri = e.target.getAttribute('action')
		let request = new Request()

		request.post(uri, formData, this.update(e.target), () => null)
	}

	update = (form) => (data) => {
		let comment = form.closest('.comment')
		let contentView = comment.querySelector('.comment__content--view')
		let modificationType = comment.querySelector('.comment__modification--type')
		let modificationDate = comment.querySelector('.comment__modification--date')

		contentView.innerText = data.content
		modificationType.innerText = 'Updated at:'
		modificationDate.setAttribute('datetime', data['updated_at'])
		modificationDate.innerText = data['updated_at']

		this.toggleForm(form)
	}

	deleteSubmitEvent = (e) => {
		e.preventDefault()
		let confirmation = confirm(
			'Are You sure You want to delte this comment? This action is irreversible.')

		if (confirmation) {
			let formData = new FormData(e.target)
			let uri = e.target.getAttribute('action')
			let request = new Request()

			request.post(uri, formData, this.delete(e.target), this.deleteComment(e.target))
		}
	}

	delete = (form) => (data) => {
		let comment = form.closest('.comment')
		this.form.parentElement.removeChild(comment)
		let commentsCount = this.commentsContainer.childElementCount
		if (commentsCount === 0) this.commentsContainer.appendChild(this.noCommentsMsg)
	}
}

<template id="commentTemplate">
	<div class="comment">
		<span class="comment__modification">
			<span class="comment__modification--type"></span> 
			<time datetime="" class="comment__modification--date"></time>
		</span>
		<button class="comment__button--edit form-toggler">Edit</button>
		<form class="comment__form comment__form--delete">
			<input type="hidden" name="<?= $token->name; ?>" value="<?= $token->value; ?>">
			<button type="submit" class="comment__button--delete">Delete</button>
		</form>
		<form method="POST" class="comment__form comment__form--edit toggable-form display-none">
			<input type="hidden" name="<?= $token->name; ?>" value="<?= $token->value; ?>">
			<textarea name="content" class="comment__content"></textarea>
			<small class="error-msg"></small>
			<button type="submit" class="comment__button--save">Save</button>
		</form>
		<span class="comment__content--view toggable-text"></span>
		<?php $this->include('ratings/form', ['token' => $token]); ?>

		<div class="comments"></div>
	</div>
</template>