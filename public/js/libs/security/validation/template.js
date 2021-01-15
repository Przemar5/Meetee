const basicFieldChangeHandler = (validator) => (e) => {
	let value = e.target.value.trim()
	let errorDiv = e.target.closest('label').querySelector('.error-msg')
	errorDiv.innerText = ''

	try {
		validator(value)
	} catch (e) {
		errorDiv.innerText = e.message
	}
}

const fieldHandlerDispatcher = (item) => {
	if (item[0] instanceof Array) {
		return multipleFieldsValidatorHandler(item)
	} else {
		return fieldValidatorHandler(item)
	}
}

const fieldValidatorHandler = ([name, validator]) => {
	let element = document.querySelector('input[name="' + name + '"]')

	element.addEventListener('keyup', basicFieldChangeHandler(validator))
	element.addEventListener('keydown', basicFieldChangeHandler(validator))
}

const multipleFieldsValidatorHandler = (array, func) => {
	let elements = array.map(([name, validator]) => {
		let item = document.querySelector('input[name="' + name + '"]')

		item.addEventListener('keyup', basicFieldChangeHandler(validator))
		item.addEventListener('keydown', basicFieldChangeHandler(validator))
	})

	func(array)
}


	// [equals(secondField.value), 'Both passwords must be identical.']