import Request from '../../../libs/http/Request.js'
import RouteDispatcher from '../../../libs/http/RouteDispatcher.js'

const togglers = document.querySelectorAll('.form-toggler')
const profileImageContainer = document.querySelector('.updatable-photo-container')
const forms = document.querySelectorAll('.attribute-form')
const inputs = document.querySelectorAll('.form-input')

const profileImageForm = profileImageContainer.querySelector('form')
const profileImageInput = profileImageContainer.querySelector('input[name="profile"]')
const profileImageDisplay = profileImageContainer.querySelector('img')

profileImageInput.addEventListener('change', (e) => {
  let file = e.target.files[0]
  let errorMsgDiv = profileImageContainer.querySelector('.error-msg')

  if (file) {
    let reader = new FileReader()
    reader.readAsDataURL(file)

    reader.onload = (evt) => {
      profileImageDisplay.setAttribute('src', evt.target.result)
      errorMsgDiv.innerHTML = ''
    }

    reader.onerror = (evt) => {
      errorMsgDiv.innerHTML = 'Error reading file'
    }
  }
})

togglers.forEach((t) => {
	t.addEventListener('click', (e) => {
		let container = e.target.closest('.toggable-container')
		container.querySelector('.toggable-form').classList.toggle('display-none')
		container.querySelector('.toggable-text').classList.toggle('display-none')
	})
})

forms.forEach((f) => {
	f.addEventListener('submit', (e) => {
		e.preventDefault()
		let container = e.target.closest('.toggable-container')
		let defaultView = container.querySelector('.toggable-text')
		let attributeDiv = container.querySelector('.attribute')
		let formData = new FormData(e.target)
		let request = new Request()
		let route = RouteDispatcher.getRouteUri('settings_account_process')

		request.post(route, formData, (data) => attributeDiv.innerText = data.toArray()[0][1])

		e.target.classList.toggle('display-none')
		defaultView.classList.toggle('display-none')
	})
})

inputs.forEach((i) => {
	i.addEventListener('keyup', (e) => {
		let form = e.target.closest('form')
		let submitBtn = form.querySelector('button')
		let errorMsgDiv = form.querySelector('.error-msg')
		let value = e.target.value.trim()
		let required = e.target.hasAttribute('required')
		let name = e.target.getAttribute('name')
		let validator = validatorFactory(name)

		try {
			if (required || (!required && value !== '')) {
				if (validator(value)) {
					errorMsgDiv.innerText = ''
					submitBtn.removeAttribute('disabled')
				}
			}
		}
		catch (e) {
			errorMsgDiv.innerText = e.message
			submitBtn.setAttribute('disabled', '')
		}
	})
})