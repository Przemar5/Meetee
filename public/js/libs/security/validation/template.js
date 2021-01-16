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
		return repeatedFieldValidatorHandler(item)
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

const repeatedFieldValidatorHandler = ([[name, second], validator, msg]) => {
	const passField = document
		.querySelector('input[name="' + name + '"]');
	const repeatPassField = document
		.querySelector('input[name="' + second + '"]');
	let errorDiv = passField.closest('label').querySelector('.error-msg')

	const handler = (evt) => {
		try {
			if (validator(passField.value.trim())) {
				if (repeatPassField.value.trim() !== passField.value.trim()) {
					errorDiv.innerText = msg
				} else {
					errorDiv.innerText = ''
				}
			}
		} catch (err) {
			errorDiv.innerText = err
		}
	}

	passField.addEventListener('keyup', handler)
	passField.addEventListener('keydown', handler)
	repeatPassField.addEventListener('keyup', handler)
	repeatPassField.addEventListener('keydown', handler)
}

	// [equals(secondField.value), 'Both passwords must be identical.']