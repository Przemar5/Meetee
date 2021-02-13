const profileImageContainer = document.getElementById('profileImageContainer')
const profileImageDisplay = document.getElementById('profileImage')
const profileImageInput = profileImageContainer.querySelector('input[name="profile"]')

const namesAndValidators = [
	['login', userLoginValidator, true],
	['email', userEmailValidator, true],
	['name', userNameValidator, true],
	['second_name', userSecondNameValidator, false],
	['surname', userSurnameValidator, true],
	['birth', userBirthValidator, true],
	['city', cityNameValidator, false],
	['zip', zipCodeValidator, false],
	[['password', 'repeat_password'], 
		userPasswordValidator, 
		'Both passwords must be identical.'],
]
namesAndValidators.forEach(fieldHandlerDispatcher)

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