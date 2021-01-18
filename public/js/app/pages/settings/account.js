import Router from '../../../libs/http/Router.js'
import RouteDispatcher from '../../../libs/http/RouteDispatcher.js'
import ToggableForm from '../../../libs/forms/ToggableForm.js'

const csrfToken = document.getElementById('csrfToken')
const errorMsgClass = 'error-msg'
const templates = {
	'name': '<input type="text" name="name">', 
	// 'second_name': ['<input type="text" name="second_name">', userNameValidator], 
	'surname': '<input type="text" name="surname">', 
	'birth': '<input type="date" name="birth">'
}

templates.mapWithKeysToArray((input, id) => {
	let element = document.getElementById(id)
	if (element) {
		let container = element.querySelector('.toggable-form-container')
		let toggler = element.querySelector('.form-toggler')
		let toggableForm = new ToggableForm(container, toggler)
		toggableForm.uri =  RouteDispatcher.getRouteUri('settings_account_process')
		toggableForm.validator = validatorFactory(id)
		toggableForm.prepareBasicViewWithInput(input)
	}
})