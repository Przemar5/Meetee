import Form from './Form.js'

export default class ToggableForm {
	uri
	validator

	constructor (element, toggler) {
		this.element = element
		this.#initToggler(toggler)
	}

	#initToggler (toggler) {
		this.toggler = toggler
		this.toggler.addEventListener('click', this.createForm)
	}

	setView (view) {
		this.view = view
		this.view = this.view.toHTMLElement()
	}

	prepareBasicViewWithInput (input) {
		input = this.#parseVariables(input)
		console.log(input)
		this.view = '<form method="POST">' + 
			input + 
			'<small class="error-msg"></small>' + 
			'<button type="submit">Submit</button>' +
			'</form>'
		this.view = this.view.toHTMLElement()
	}

	#parseVariables (input) {
		let regex = /(?:\${)(.*)(?:})/
		let found = regex.exec(input)
		if (!found) return input
		let result = eval(found[1])
		return input.replace(regex, result)
	}

	createForm = (e) => {
		let text = this.element.innerText.trim()
		this.element.innerHTML = ''
		this.form = new Form(this.element, this.view, text, this.validator, () => null)
		this.form.uri = this.uri
		// this.form.view.addEventListener('focusout', (e) => {
		// 	this.createText()
		// })
		this.form.afterSubmit = this.createText
		this.toggler.classList.add('display-none')
	}

	createText = () => {
		let value = this.form.acceptedValue.trim()
		this.element.innerHTML = value
		this.toggler.classList.remove('display-none')
	}
}