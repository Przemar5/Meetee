import Request from '../../libs/http/Request.js'

export default class Form {
	uri
	successResponse = (data) => console.log(data)
	errorRespones = (data) => console.warn(data)

	constructor (parent, view, value, validator, afterSubmit) {
		this.parent = parent
		this.view = view
		this.validator = validator
		this.afterSubmit = afterSubmit
		this.acceptedValue = value
		this.#prepareInput(value)
		this.#prepareErrorDiv()
		this.#prepareSubmitButton()
		this.#appendToken()
		this.view.addEventListener('submit', this.handleSubmitEvent)
		this.parent.appendChild(this.view)
	}

	#prepareInput (value) {
		this.input = this.view.querySelector('input')
		this.input.value = value.trim()
		this.input.addEventListener('keyup', this.handleKeyEvent)
		this.input.addEventListener('keydown', this.handleKeyEvent)
	}
	
	#prepareErrorDiv () {
		this.errorMsgDiv = this.view.querySelector('.error-msg')
	}

	#prepareSubmitButton () {
		this.submitBtn = this.view.querySelector('button')
	}
	
	#appendToken () {
		let clone = csrfToken.content.cloneNode(true)
		this.view.appendChild(clone)
	}

	handleKeyEvent = (e) => {
		let value = this.input.value.trim()
		try {
			if (this.validator(value)) {
				this.submitBtn.removeAttribute('disabled')
				this.view.addEventListener('submit', this.handleSubmitEvent)
				this.errorMsgDiv.innerText = ''
			}
		} catch (e) {
			this.errorMsgDiv.innerText = e.message
			this.submitBtn.setAttribute('disabled', '')
		}
	}

	handleSubmitEvent = (e) => {
		e.preventDefault()
		let formData = new FormData(e.target)
		this.send(formData)
	}

	send = (data) => {
		let request = new Request()
		request.post(this.uri, data, this.success, this.error)
		this.acceptedValue = this.input.value.trim()
		this.afterSubmit()
	}
}