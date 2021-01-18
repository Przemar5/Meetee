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