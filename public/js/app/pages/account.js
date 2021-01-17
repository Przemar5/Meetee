import Router from '../../libs/http/Router.js'
import RouteDispatcher from '../../libs/http/RouteDispatcher.js'
import Ajax from '../../libs/http/Ajax.js'

const token = document.getElementById('token')
const errorMsgClass = 'error-msg'
const labels = ['userName', 'userSecondName', 'userSurname', 
	'userLogin', 'userEmail', 'userBirth']
const tokenInput = document.getElementById('csrf_token')
const token = {
	name: tokenInput.getAttribute('name'),
	value: tokenInput.getAttribute('value')
}

const templates = {
	name: '<input type="text" name="name" value="$val"><small class="error-msg"></small>',
	second_name: '<input type="text" name="second_name" value="$val"><small class="error-msg"></small>',
	surname: '<input type="text" name="surname" value="$val"><small class="error-msg"></small>',
	email: '<input type="email" name="email" value="$val"><small class="error-msg"></small>',
	birth: '<input type="date" name="birth" value="$val"><small class="error-msg"></small>'
}

const changeBtns = document.querySelectorAll('.form-toggler')

changeBtns.forEach((btn) => {
	btn.addEventListener('click', (e) => {
		let label = e.target.closest('label')
		let container = label.querySelector('.toggable-form-container')
		let name = label.getAttribute('id')

		if (container.classList.contains('is-form')) {
			let value = container.querySelector('input').value
			submit(name, value)
				.then((response) => {
					let data = JSON.parse(response)
					createText(container, data[name])
				})
		}
		else {
			createForm(container, name)
		}
		container.classList.toggle('is-form')
	})
})

Object.prototype.forEachKey = function (func) {
	for (let i in this) func(this[i], i)
}

const createElement = (json) => {
	if (!'tag'in json) return
	let element = document.createElement(json['tag'])
	
	for (let attr in json) {
		if (attr === 'events') {
			json[attr].forEachWithKey(attachEvents(element))
		}
		else if (isObject(json[attr]) && !isArray(json[attr])) {
			return
		}
		else if (attr === 'children') {
			json[attr].forEach((c) => element.appendChild(createElement(c)))
		}
		else {
			element.setAttribute(attr, json[attr])
		}
	}
	return element
}

const createErrorDiv = () => {
	let item = document.createElement('small')
	item.classList.add(errorMsgClass)
	return item
}




const attachEvents = (element) => (event, closure) => {
	element.addEventListener(event, closure)
}

let submitEventHandler = (e) => {
	let formData = new FormData()
	console.log(formData)
	console.log(1)
}

let keyEventHandler = (e) => {
	let value = e.target.value
	try {
		if (this.validator(value)) {
			this.submitBtn.removeAttribute('disabled')
			this.form.addEventListener('submit', submitEventHandler)
		}
	} catch (e) {
		this.errorMsgDiv.innerText = e.message
		this.submitBtn.setAttribute('disabled', '')
	}
}



class FormView {
	constructor () {

	}

	#prepareForm () {
		this.form = '<form method="POST">' + 
			'<input type="text" name="name">' + 
			'<small class="error-msg"></small>' + 
			'<button type="submit">Submit</button>' +
			'</form>'
	}

	#prepareInput () {
		this.input = this.template.querySelector('input')
		this.input.addEventListener('keyup', keyEventHandler)
		this.input.addEventListener('keydown', keyEventHandler)
	}
	
	#prepareErrorDiv () {
		this.errorMsgDiv = this.template.querySelector('.error-msg')
	}

	#prepareSubmitButton () {
		this.submitBtn = this.template.querySelector('button')
	}
	
	this.form.appendChild(token)

}

const createForm = (container, name) => {
	let content = container.innerHTML.trim()
	container.innerHTML = templates[name].replace('$val', content)
	container.appendChild(createErrorDiv())
	let input = container.querySelector('input')
	let validator = validatorFactory(name)

	input.addEventListener('keyup', (e) => {
		fieldHandlerDispatcher(['name', userNameValidator])
	})
}

const createText = (container, value) => {
	if (isDefined(value) && notNull(value)) container.innerHTML = value
}

const submit = (name, value) => {
	let route = RouteDispatcher.getRouteUri('settings_account_process')
	let ajax = new Ajax()
	let data = prepareToSend(name, value)
	data = encodeQueryData(data)
	return ajax.post(route, data)
}

const prepareToSend = (name, value) => {
	let data = []
	data[name] = value
	data[token.name] = token.value
	return data
}

const encodeQueryData = (data) => {
   const ret = [];
   for (let d in data)
     ret.push(encodeURIComponent(d) + '=' + encodeURIComponent(data[d]));
   return ret.join('&');
}