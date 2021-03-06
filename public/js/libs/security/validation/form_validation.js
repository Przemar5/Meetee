const userNameValidator = validForAll([
	[isDefined, 'Name is required.'],
	[notNull, 'Name is required.'],
	[notEmpty, 'Name is required.'],
	[isString, ''],
	[isNotShorter(2), 'Name must be 2 characters minimum.'],
	[isNotLonger(40), 'Name must be equal or shorter than 40 characters long.'],
	[matches(/^[\w^_\d]+$/u), 'Name may contain only letters.']
].map((v) => validOrThrow(v[0])(v[1])))

const userSecondNameValidator = validForAll([
	[isString, ''],
	[isNotShorter(2), 'Second name must be 2 characters minimum.'],
	[isNotLonger(40), 'Second name must be equal or shorter than 40 characters long.'],
	[matches(/^[\w^_\d]+$/u), 'Second name may contain only letters.']
].map((v) => validOrThrow(v[0])(v[1])))

const userSurnameValidator = validForAll([
	[isDefined, 'Surname is required.'],
	[notNull, 'Surname isrequired.'],
	[notEmpty, 'Surname isrequired.'],
	[isString, ''],
	[isNotShorter(2), 'Surname must be 2 characters minimum.'],
	[isNotLonger(70), 'Surname must be equal or shorter than 70 characters.'],
	[matches(/^[\w^_\-\d]+$/u), 'Surname may contain only letters and dashes.']
].map((v) => validOrThrow(v[0])(v[1])))

const userBirthValidator = validForAll([
	[isDefined, 'Birth date is required.'],
	[notNull, 'Birth date is required.'],
	[notEmpty, 'Birth date is required.'],
	[isString, ''],
	[exactLength(10), ''],
	[matches(/^([0-9]{4})-(0[1-9]|1[0-2])-(0[1-9]|(1|2)[0-9]|(3[0-1]))$/), 
		'Invalid format. Please try again.'],
	[notMore(DateAdapter.getStringFromInterval('-5 years')),
		'You are too young.'],
	[notLess(DateAdapter.getStringFromInterval('-100 years')),
		'You are too old.']
].map((v) => validOrThrow(v[0])(v[1])))

const cityNameValidator = validForAll([
	[isString, ''],
	[isNotShorter(2), 'City name must be longer or equal 2 characters.'],
	[isNotLonger(180), 'City name must be equal or shorter than 180 characters long.'],
	[matches(/^[\w\- \(\)]+$/u), 
		'City name may contain only letters, dashes, spaces and parentheses.']
].map((v) => validOrThrow(v[0])(v[1])))

const zipCodeValidator = validForAll([
	[isString, ''],
	[matches(/^\d{2}-?\d{3}$/u), 'Zip code has inproper format.']
].map((v) => validOrThrow(v[0])(v[1])))

// Regex not working
const userPasswordValidator = validForAll([
	[isDefined, 'Password is required.'],
	[notNull, 'Password isrequired.'],
	[notEmpty, 'Password isrequired.'],
	[isString, ''],
	[isNotShorter(8), 'Password must be at least 8 characters long.'],
	[isNotLonger(60), 'Password must be equal or shorter than 60 characters.'],
	[matches(/^(?:(?=.*?\p{N})(?=.*?[\p{S}\p{P} ])(?=.*?\p{Lu})(?=.*?\p{Ll}))[^\p{C}]{8,60}$/u), 
		'Password must contain a lowercase and uppercase letter, ' + 
		'a number and special character.']
].map((v) => validOrThrow(v[0])(v[1])))

const nullValidator = (v) => true

const userEmailValidator = validForAll([
	[isDefined, 'Email is required.'],
	[notNull, 'Email is required.'],
	[notEmpty, 'Email is required.'],
	[isString, ''],
	[isNotShorter(8), 'Email must be 8 characters minimum.'],
	[isNotLonger(60), 'Email must be equal or shorter than 60 characters long.'],
	[matches(/^(([^<>()[\]\.,;:\s@"]+(\.[^<>()[\]\.,;:\s@"]+)*)|(".+"))@(([^<>()[\]\.,;:\s@"]+\.)+[^<>()[\]\.,;:\s@"]{2,})$/u), 
		'Email has incorrect format.']
].map((v) => validOrThrow(v[0])(v[1])))

const userLoginValidator = validForAll([
	[isDefined, 'Login is required.'],
	[notNull, 'Login is required.'],
	[notEmpty, 'Login is required.'],
	[isString, ''],
	[isNotShorter(3), 'Login must be 3 characters minimum.'],
	[isNotLonger(60), 'Email must be equal or shorter than 60 characters long.'],
	[matches(/^[\w\d\-]+$/u), 
		'Login may contain only alphanumeric characters and hyphens.']
].map((v) => validOrThrow(v[0])(v[1])))

const commentBodyValidator = validForAll([
	[isDefined, 'Comment body is required.'],
	[notNull, 'Comment body is required.'],
	[notEmpty, 'Comment body is required.'],
	[isString, ''],
	[isNotShorter(1), 'Comment body must be longer or equal 1 character.'],
	[isNotLonger(65535), 'Comment body is too long.'],
	[matches(/^[\w\d\s\-#\$@!\^&\*()\+={}[\];:"\|,<.>\/\?]+$/u), 
		'Comment body contains inproper characters.']
].map((v) => validOrThrow(v[0])(v[1])))

const validatorFactory = (name) => {
	switch (name) {
		case 'name': return userNameValidator
		case 'second_name': return userNameValidator
		case 'surname': return userSurnameValidator
		case 'birth': return userBirthValidator
		case 'city': return cityNameValidator
		case 'zip': return zipCodeValidator
		case 'post_body': return postBodyValidator
	}
}