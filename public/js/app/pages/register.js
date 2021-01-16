const namesAndValidators = [
	['login', userLoginValidator],
	['email', userEmailValidator],
	['name', userNameValidator],
	['surname', userSurnameValidator],
	['birth', userBirthValidator],
	[['password', 'repeat_password'], 
		userPasswordValidator, 
		'Both passwords must be identical.'],
]
namesAndValidators.forEach(fieldHandlerDispatcher)