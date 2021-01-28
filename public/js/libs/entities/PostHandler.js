import Ajax from '../../libs/http/Ajax.js'
import Request from '../../libs/http/Request.js'
import RouteDispatcher from '../../libs/http/RouteDispatcher.js'

export default class PostHandler {
	constructor () {
		this.lastPostId = 99999999999999
		this.userId = /\d+$/.exec(window.location.href)
		this.limit = 2
		this.postsContainer
		this.postTemplate
		this.noPostsMsg
	}

	loadPosts () {
		let ajax = new Ajax()
		let route = RouteDispatcher.getRouteUri('posts_select_process')
		route = route + '?user-id=' + this.userId + '&limit=' + this.limit + '&max-id=' + this.lastPostId
		ajax.get(route, null, null)
			.then(this.callback)
	}

	callback = (data) => {
		data = JSON.parse(data)
		for (let i in data) {
			if (data[i] instanceof Function) continue
			let temp = this.prepareTemplate(data[i])
			this.postsContainer.appendChild(temp)
			this.lastPostId = data[i]['id']
		}

		if (data.length > 0) {
			let noPostsMsg = this.postsContainer.querySelector('p.no-result-msg')
			if (noPostsMsg) this.postsContainer.removeChild(noPostsMsg)
		}
	}

	prepareTemplate (data) {
		let temp = this.postTemplate.content.cloneNode(true)
		let modificationType = temp.querySelector('.post__modification--type')
		let modificationDate = temp.querySelector('.post__modification--date')
		let formEdit = temp.querySelector('.post__form--edit')
		let formDelete = temp.querySelector('.post__form--delete')
		let contentInput = temp.querySelector('.post__content')
		let errorSpan = temp.querySelector('.error-msg')
		let contentView = temp.querySelector('.post__content--view')
		let btnSave = temp.querySelector('.post__button--save')
		let btnEdit = temp.querySelector('.post__button--edit')
		let btnDelete = temp.querySelector('.post__button--delete')
		let route

		try {
			modificationType.innerText = (data['created_at'] < data['updated_at'])
				? 'Updated at:' : 'Created at:'
			modificationDate.innerText = data['updated_at']
			modificationDate.setAttribute('datetime', data['updated_at'])
			
			route = RouteDispatcher.getRouteUri('posts_update_process', {'id': data['id']})
			formEdit.setAttribute('action', route)

			route = RouteDispatcher.getRouteUri('posts_delete_process', {'id': data['id']})
			formDelete.setAttribute('action', route)

			contentInput.innerText = data['content']
			contentView.innerText = data['content']
			this.addContentInputEventListeners(contentInput)
			this.addEditBtnEventListener(btnEdit)

			formEdit.addEventListener('submit', this.updateSubmitEvent)
			formDelete.addEventListener('submit', this.deleteSubmitEvent)

			this.prepareRatingForm(temp, data['id'])
		} catch (e) {
			console.log(e.message)
		}

		return temp
	}

	prepareRatingForm (template, postId) {
		const forms = template.querySelectorAll('.ratings form')
		forms.forEach((f) => {
			let ratingId = f.querySelector('input[name="rating_id"]').value
			let route = RouteDispatcher.getRouteUri('ratings_rate_process', {
				'id': ratingId,
				'type': 'post',
				'resourceId': postId,
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

		request.post(uri, formData, ((d) => console.log(d)), ((d) => console.log(d)))
	}

	addContentInputEventListeners (contentInput) {
		contentInput.addEventListener('keyup', this.inputKeyEvent)
		contentInput.addEventListener('keydown', this.inputKeyEvent)
	}

	addEditBtnEventListener (btn) {
		btn.addEventListener('click', (e) => this.toggleFormAndPlainText(e.target))
	}

	toggleFormAndPlainText = (item) => {
		let container = item.closest('.post')
		container.querySelector('.toggable-form').classList.toggle('display-none')
		container.querySelector('.toggable-text').classList.toggle('display-none')
	}

	inputKeyEvent = (e) => {
		let value = e.target.value.trim()
		let form = e.target.closest('form')
		let errorMsgDiv = form.querySelector('.error-msg')
		let btnSubmit = form.querySelector('.post__button--save')

		try {
			if (postBodyValidator(value)) {
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

		request.post(uri, formData, this.updatePost(e.target), () => null)
	}

	updatePost = (form) => (data) => {
		let post = form.closest('.post')
		let contentView = post.querySelector('.post__content--view')
		let modificationType = post.querySelector('.post__modification--type')
		let modificationDate = post.querySelector('.post__modification--date')

		contentView.innerText = data.content
		modificationType.innerText = 'Updated at:'
		modificationDate.setAttribute('datetime', data['updated_at'])
		modificationDate.innerText = data['updated_at']

		this.toggleFormAndPlainText(form)
	}

	deleteSubmitEvent = (e) => {
		e.preventDefault()
		let confirmation = confirm(
			'Are You sure You want to delte this post? This action is irreversible.')

		if (confirmation) {
			let formData = new FormData(e.target)
			let uri = e.target.getAttribute('action')
			let request = new Request()

			request.post(uri, formData, this.deletePost(e.target), this.deletePost(e.target))
		}
	}

	deletePost = (form) => (data) => {
		let post = form.closest('.post')
		this.postsContainer.removeChild(post)
		console.log(this.noPostsMsg)
		let postsCount = this.postsContainer.childElementCount
		if (postsCount === 0) this.postsContainer.appendChild(this.noPostsMsg)
	}
}