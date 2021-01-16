import Router from '../../libs/http/Router.js'
import RouteDispatcher from '../../libs/http/RouteDispatcher.js'
import Ajax from '../../libs/http/Ajax.js'

const labels = ['userName', 'userSecondName', 'userSurname', 
	'userLogin', 'userEmail', 'userBirth']
const tokenInput = document.getElementById('csrf_token')
const token = {
	name: tokenInput.getAttribute('name'),
	value: tokenInput.getAttribute('value')
}

const templates = {
	name: '<input type="text" name="name" value="$val">',
	second_name: '<input type="text" name="second_name" value="$val">',
	surname: '<input type="text" name="surname" value="$val">',
	email: '<input type="email" name="email" value="$val">',
	birth: '<input type="date" name="birth" value="$val">',
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
			createText(container, value)
		}
		else {
			createForm(container, name)
		}
		container.classList.toggle('is-form')
	})
})

const createForm = (container, name) => {
	let content = container.innerHTML.trim()

	container.innerHTML = templates[name].replace('$val', content)
}

const createText = (container, value) => {
	container.innerHTML = value
}

const submit = (name, value) => {
	let route = RouteDispatcher.getRouteUri('settings_account_process')
	let ajax = new Ajax()
	let data = prepareToSend(name, value)

	// console.log(data)

	// console.log(JSON.serialize(data))
	// route = RouteDispatcher.routes['settings_account_process'].uri
	// route
	data = encodeQueryData(data)
	ajax.post(route, data)
		.then((msg) => console.log(msg))
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

// let 

// let formView = new SinglePropertyFormView()
// console.log(formView.generate({id: 4}))

// let nameForm = new FormView()
// nameForm
// let template = new TemplateBuilder()
// template.reset()
// template.generate()

// let template = document.createElement('div')
// template.append('a')


// const getAreas = (labels) => {
// 	let result = []
// 	for (let i in labels) result[labels[i]] = document.getElementById(labels[i])
// 	return result
// }

// const inputAreas = getAreas(labels)

// Object.values(inputAreas).forEach(label => label
// 	.querySelector('button')
// 	.addEventListener('click', (e) => {
// 		console.log(e.target.closest('label'))
// 	})
// )


// console.log(inputAreas)