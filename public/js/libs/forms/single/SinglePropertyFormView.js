import FormView from '../FormView.js'

export default class SinglePropertyFormView extends FormView {
	generate (data) {
		let label = document.createElement('label')
		label.setAttribute('id', data['id'])
		label.innerText = 'Hello!'
			
		return label
	}
}