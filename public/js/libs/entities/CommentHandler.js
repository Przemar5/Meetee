import Ajax from '../../libs/http/Ajax.js'
import Request from '../../libs/http/Request.js'
import RouteDispatcher from '../../libs/http/RouteDispatcher.js'

export default class CommentHandler {
	constructor () {
		this.lastCommentId = 99999999999
		this.userId = /\d+$/.exec(window.location.href)
		this.limit = 3
		this.commentsContainer
		this.commentTemplate
		this.noCommentsMsg
	}

	loadComments (parent, template, data) {
		let ajax = new Ajax()
		let route = RouteDispatcher.getRouteUri('comments_select_process')
		route += '?' + Object.keys(data)
			.map((k) => k + '=' + data[k].toString())
			.join('&')
		
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
		let btnComment = temp.querySelector('.comment__button--comment')
		let btnShowSubs = temp.querySelector('.comment__button--show-subs')
		let parentIdInput = temp.querySelector('input[name="parent_id"]')
		let topicIdInput = temp.querySelector('input[name="topic_id"]')
		let groupIdInput = temp.querySelector('input[name="group_id"]')
		let formSubcomment = temp.querySelector('.comment__form--comment')
		let subcommentsContainer = temp.querySelector('.comment__subcomments')
		let route

		try {
			modificationType.innerText = (data['created_at'] < data['updated_at'])
				? 'Updated at:' : 'Created at:'
			modificationDate.innerText = data['updated_at']
			modificationDate.setAttribute('datetime', data['updated_at'])
			
			route = RouteDispatcher.getRouteUri(
				'comments_update_process', {'id': data['id']})
			formEdit.setAttribute('action', route)

			route = RouteDispatcher.getRouteUri(
				'comments_delete_process', {'id': data['id']})
			formDelete.setAttribute('action', route)

			contentInput.innerText = data['content']
			contentView.innerText = data['content']
			this.addContentInputEventListeners(contentInput)
			this.addEditBtnEventListener(btnEdit)

			formEdit.addEventListener('submit', this.updateSubmitEvent)
			formDelete.addEventListener('submit', this.deleteSubmitEvent)
			formSubcomment.addEventListener('submit', this.subcommentSubmitEvent)
			
			this.addSubcommentInputEvents(formSubcomment)
			btnComment.addEventListener('click', this.toggleFormComment)

			parentIdInput.setAttribute('value', data['id'])
			topicIdInput.setAttribute('value', data['topic_id'])
			groupIdInput.setAttribute('value', data['group_id'])

			this.prepareRatingForm(temp, data['id'])

			subcommentsContainer.classList.add('display-none')
			btnShowSubs.addEventListener('click', (e) => {
				e.target.classList.add('display-none')
				e.target.closest('.comment')
					.querySelector('.comment__subcomments')
					.classList.remove('display-none')
			})

			if (data['comments'].length > 0) {
				for (let i in data['comments']) {
					if (data['comments'][i] instanceof Function) continue
					let subcomment = this.prepareTemplate(template, data['comments'][i])
					subcommentsContainer.appendChild(subcomment)
				}
			}

		} catch (e) {
			console.log(e)
		}

		return temp
	}

	addSubcommentInputEvents (form) {
		let textarea = form.querySelector('[name="content"]')

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

	toggleFormComment = (e) => {
		e.preventDefault()
		let subcommentForm = e.target
			.closest('.comment__commenting')
			.querySelector('.comment__form--comment')

		subcommentForm.classList.toggle('display-none')
	}

	subcommentSubmitEvent = (e) => {
		e.preventDefault()
		let formData = new FormData(e.target)
		let route = e.target.getAttribute('action')
		let request = new Request()
		let subcommentContainer = e.target.closest('.comment').querySelector('.comment__subcomments')

		let callback = (container) => (data) => {
			let comment = this.prepareTemplate(this.commentTemplate, data)
			container.prepend(comment)
			e.target.querySelector('textarea').value = ''
		}

		request.post(route, formData, callback(subcommentContainer), callback)
	}

	prepareRatingForm (template, commentId) {
		const ratingsArea = template.querySelector('.ratings')
		const forms = ratingsArea.querySelectorAll('form')
		
		forms.forEach((f) => {
			let ratingId = f.querySelector('input[name="rating_id"]').value
			let route = RouteDispatcher.getRouteUri('ratings_rate_process', {
				'id': ratingId,
				'commentId': commentId
			})
			f.setAttribute('action', route)
			f.addEventListener('submit', this.rateEvent)
		})
		this.getCommentRatings(ratingsArea, commentId)
	}

	getCommentRatings (ratingsArea, commentId) {
		let uri = RouteDispatcher.getRouteUri('ratings_get_process', {'commentId': commentId})
		let request = new Request()

		let callback = (data) => {
			let rates = {}

			for (let i = 1; i < 8; i++)
				rates[i+''] = {'count': 0, 'users': []}

			for (let i in data) {
				if (data[i] instanceof Function) continue
				let rateId = data[i]['rate_id'] + ''
				rates[rateId].count++
				rates[rateId].users.push(data[i]['user_id'])
			}

			// Other actions
		}

		request.get(uri, null, callback, callback)
	}

	rateEvent = (e) => {
		e.preventDefault()
		let formData = new FormData(e.target)
		let uri = e.target.getAttribute('action')
		let request = new Request()

		request.post(uri, formData, (data) => {console.log(data)}, () => { console.log(0) })
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
			'Are You sure You want to delete this comment? This action is irreversible.')

		if (confirmation) {
			let formData = new FormData(e.target)
			let uri = e.target.getAttribute('action')
			let request = new Request()

			request.post(uri, formData, this.deleteComment(e.target), this.deleteComment(e.target))
		}
	}

	deleteComment = (form) => (data) => {
		let comment = form.closest('.comment')
		comment.parentElement.removeChild(comment)
		let commentsCount = this.commentsContainer.childElementCount
		if (commentsCount === 0) this.commentsContainer.appendChild(this.noCommentsMsg)
	}
}