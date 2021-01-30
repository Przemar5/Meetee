import Ajax from '../../libs/http/Ajax.js'
import Request from '../../libs/http/Request.js'
import RouteDispatcher from '../../libs/http/RouteDispatcher.js'

export default class CommentHandler {
	constructor () {
		this.lastCommentId = 99999999999999
		this.userId = /\d+$/.exec(window.location.href)
		this.limit = 20
		this.commentsContainer
		this.commentTemplate
		this.noCommentsMsg
	}

	loadComments (parent, template) {
		let ajax = new Ajax()
		let route = RouteDispatcher.getRouteUri('comments_select_process')
		route = route + '?user-id=' + this.userId + '&limit=' + this.limit + '&max-id=' + this.lastCommentId
		ajax.get(route, null, null)
			.then(this.callback(parent, template))
	}

	callback = (parent, template) => (data) => {
		data = JSON.parse(data)
		for (let i in data) {
			if (data[i] instanceof Function) continue
			let temp = this.prepareTemplate(template, data[i])
			parent.appendChild(temp)
			this.lastCommentId = data[i]['id']
		}

		if (data.length > 0) {
			let noCommentsMsg = parent.querySelector('p.no-result-msg')
			if (noCommentsMsg) parent.removeChild(noCommentsMsg)
		}
	}

	initFormCreate (data) {
		let parent = data['formContainer']
		let commentForm = data['formTemplate']
		let commentsContainer = data['commentContainer']
		let commentTemplate = data['commentTemplate']
		let form = commentForm.content.cloneNode(true)
		let textarea = form.querySelector('textarea[name="content"]')

		parent.prepend(form)
		form = parent.querySelector('form')

		textarea.addEventListener('keyup', this.validateNewPost)
		textarea.addEventListener('keydown', this.validateNewPost)

		form.addEventListener('submit', (e) => {
			e.preventDefault()
			let formData = new FormData(e.target)
			let request = new Request()
			let uri = e.target.getAttribute('action')
			let addNewestComment = this.addComment(commentsContainer, commentTemplate)
			 
			request.post(
				uri, 
				formData, 
				(data) => {
					form.querySelector('textarea').value = ''
					addNewestComment(data)
				}, 
				() => null
			)
		})
	}

	validateNewPost = (e) => {
		let value = e.target.value
		let submitBtn = e.target.closest('form').querySelector('button[type="submit"]')
		let errorMsgDiv = e.target.closest('form').querySelector('.error-msg')

		try {
			if (commentBodyValidator(value)) {
				errorMsgDiv.innerText = ''
				submitBtn.removeAttribute('disabled')
			}
		} catch (e) {
			errorMsgDiv.innerText = e.message
			submitBtn.setAttribute('disabled', true)
		}
	}

	addComment = (commentSection, commentTemplate) => (data) => {
		let comment = this.prepareTemplate(commentTemplate, data)
		commentSection.prepend(comment)
	}

	prepareTemplate (template, data) {
		let temp = template.content.cloneNode(true)
		let modificationType = temp.querySelector('.comment__modification--type')
		let modificationDate = temp.querySelector('.comment__modification--date')
		let formEdit = temp.querySelector('.comment__form--edit')
		let formDelete = temp.querySelector('.comment__form--delete')
		let contentInput = temp.querySelector('.comment__content')
		let errorSpan = temp.querySelector('.error-msg')
		let contentView = temp.querySelector('.comment__content--view')
		let btnSave = temp.querySelector('.comment__button--save')
		let btnEdit = temp.querySelector('.comment__button--edit')
		let btnDelete = temp.querySelector('.comment__button--delete')
		let route

		try {
			modificationType.innerText = (data['created_at'] < data['updated_at'])
				? 'Updated at:' : 'Created at:'
			modificationDate.innerText = data['updated_at']
			modificationDate.setAttribute('datetime', data['updated_at'])
			
			route = RouteDispatcher.getRouteUri('comments_update_process', {'id': data['id']})
			formEdit.setAttribute('action', route)

			route = RouteDispatcher.getRouteUri('comments_delete_process', {'id': data['id']})
			formDelete.setAttribute('action', route)

			contentInput.innerText = data['content']
			contentView.innerText = data['content']
			this.addContentInputEventListeners(contentInput)
			this.addEditBtnEventListener(btnEdit)

			formEdit.addEventListener('submit', this.updateSubmitEvent)
			formDelete.addEventListener('submit', this.deleteSubmitEvent)

			this.prepareRatingForm(temp, data['id'])
		} catch (e) {
			console.log(e)
		}

		return temp
	}

	prepareRatingForm (template, commentId) {
		const forms = template.querySelectorAll('.ratings form')
		forms.forEach((f) => {
			let ratingId = f.querySelector('input[name="rating_id"]').value
			let route = RouteDispatcher.getRouteUri('ratings_rate_process', {
				'id': ratingId,
				'commentId': commentId
			})
			f.setAttribute('action', route)
			f.addEventListener('submit', this.rateEvent)
		})
	}

	rateEvent = (e) => {
		e.preventDefault()
		let formData = new FormData(e.target)
		let uri = e.target.getAttribute('action')
		let request = new Request()

		request.post(uri, formData, () => {console.log(1)}, () => { console.log(0) })
	}

	addContentInputEventListeners (contentInput) {
		contentInput.addEventListener('keyup', this.inputKeyEvent)
		contentInput.addEventListener('keydown', this.inputKeyEvent)
	}

	addEditBtnEventListener (btn) {
		btn.addEventListener('click', (e) => this.toggleFormAndPlainText(e.target))
	}

	toggleFormAndPlainText = (item) => {
		let container = item.closest('.comment')
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

		request.post(uri, formData, this.updateComment(e.target), () => null)
	}

	updateComment = (form) => (data) => {
		let comment = form.closest('.comment')
		let contentView = comment.querySelector('.comment__content--view')
		let modificationType = comment.querySelector('.comment__modification--type')
		let modificationDate = comment.querySelector('.comment__modification--date')

		contentView.innerText = data.content
		modificationType.innerText = 'Updated at:'
		modificationDate.setAttribute('datetime', data['updated_at'])
		modificationDate.innerText = data['updated_at']

		this.toggleFormAndPlainText(form)
	}

	deleteSubmitEvent = (e) => {
		e.preventDefault()
		let confirmation = confirm(
			'Are You sure You want to delte this comment? This action is irreversible.')

		if (confirmation) {
			let formData = new FormData(e.target)
			let uri = e.target.getAttribute('action')
			let request = new Request()

			request.post(uri, formData, this.deleteComment(e.target), this.deleteComment(e.target))
		}
	}

	deleteComment = (form) => (data) => {
		let comment = form.closest('.comment')
		this.commentsContainer.removeChild(comment)
		let commentsCount = this.commentsContainer.childElementCount
		if (commentsCount === 0) this.commentsContainer.appendChild(this.nocommentsMsg)
	}
}