const handleFieldValueChange = (validator) => (e) => {
	let value = e.target.value.trim()
	let errorDiv = e.target.closest('label').querySelector('.error-msg')
	errorDiv.innerText = ''

	try {
		validator(value)
	} catch (e) {
		errorDiv.innerText = e.message
	}
}

const fieldNameAndValidatorHandler = (item) => {
	let element = document.querySelector('input[name="' + item[0] + '"]')
	
	element.addEventListener('keyup', 
		handleFieldValueChange(item[1]))
	element.addEventListener('keydown', 
		handleFieldValueChange(item[1]))
}

const dateFieldNameAndValidationHandler = (item) => {
	let element = document.querySelector('input[name="' + item[0] + '"]')
	
	element.addEventListener('keyup', (e) => {
		handleFieldValueChange(item[1])
	})
	element.addEventListener('keydown', (e) => {
		handleFieldValueChange(item[1])
	})
}