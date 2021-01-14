import Ajax from '../../../libs/http/Ajax.js'
import Interface from '../../../libs/global/Interface.js'
import Validator from '../../../libs/security/validation/Validator.js'
import TemplateBuilder from '../../../libs/view/TemplateBuilder.js'

import FormController from '../../../libs/forms/FormController.js'
import FormModel from '../../../libs/forms/FormModel.js'
import FormView from '../../../libs/forms/FormView.js'
import SinglePropertyFormView from '../../../libs/forms/single/SinglePropertyFormView.js'

// import Validation 

const labels = ['userName', 'userSecondName', 'userSurname', 
	'userLogin', 'userEmail', 'userBirth']

let formView = new SinglePropertyFormView()
console.log(formView.generate({id: 4}))

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