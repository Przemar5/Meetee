const namesAndValidators = [
	[['password', 'repeat_password'], 
		userPasswordValidator, 
		'Both passwords must be identical.'],
]
namesAndValidators.forEach(fieldHandlerDispatcher)