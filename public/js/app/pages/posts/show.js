import Ajax from '../../../libs/http/Ajax.js'
import RouteDispatcher from '../../../libs/http/RouteDispatcher.js'

const postTemplate = document.getElementById('postTemplate')
const postsContainer = document.getElementById('posts')

class PostHandler {
	constructor () {
		this.lastPostId = 99999999999
		this.userId = /\d+$/.exec(window.location.href)
		this.limit = 2
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
			postsContainer.appendChild(temp)
			this.lastPostId = data[i]['id']
		}
	}

	prepareTemplate (data) {
		let temp = postTemplate.content.cloneNode(true)
		let modificationType = temp.querySelector('.post__modification--type')
		let modificationDate = temp.querySelector('.post__modification--date')
		let form = temp.querySelector('.post__form')
		let contentInput = temp.querySelector('.post__content')
		let errorSpan = temp.querySelector('.error-msg')
		let contentView = temp.querySelector('.post__content--view')
		let btnEdit = temp.querySelector('.post__button--edit')
		let btnDelete = temp.querySelector('.post__button--delete')
		let route

		try {
			route = RouteDispatcher.getRouteUri('posts_update_process', {'id': data['id']})
			modificationType.innerText = (data['created_at'] < data['updated_at'])
				? 'Updated at:' : 'Created at:'
			modificationDate.innerText = data['updated_at']
			modificationDate.setAttribute('datetime', data['updated_at'])
			form.setAttribute('action', route)
			contentInput.innerText = data['content']
			contentView.innerText = data['content']
			this.addContentInputEventListeners(contentInput)
			this.addEditBtnEventListener(btnEdit)
		} catch (e) {
			console.log(e.message)
		}

		return temp
	}

	addContentInputEventListeners (contentInput) {
		contentInput.addEventListener('keyup', this.inputKeyEvent)
		contentInput.addEventListener('keydown', this.inputKeyEvent)
	}

	addEditBtnEventListener (btn) {
		btn.addEventListener('click', (e) => {
			let container = e.target.closest('.post')
			container.querySelector('.toggable-form').classList.toggle('display-none')
			container.querySelector('.toggable-text').classList.toggle('display-none')
		})
	}

	inputKeyEvent = (e) => {
		let value = e.target.value.trim()
		let form = e.target.closest('form')
		let errorMsgDiv = form.find('.post__error-msg')

		try {
			if (PostBodyValidator(value)) {
				errorMsgDiv.innerText = ''
				form.removeAttribute('disabled')
			}
		} catch (e) {
			errorMsgDiv.innerText = e.message
			form.setAttribute('disabled', true)
		}
	}
}

let postHandler = new PostHandler()
postHandler.loadPosts()

// let route = RouteDispatcher.getRouteUri('posts_update_process', {'id': 1})

// console.log(route)