const userNameValidator = validForAll([
	[isDefined, 'Name is required.'],
	[notNull, 'Name cannot be empty.'],
	[isString, ''],
	[isNotShorter(2), 'Name must be 2 characters minimum.'],
	[isNotLonger(40), 'Name must be equal or shorter than 40 characters long.'],
	[matches(/^[\w^_\d]+$/u), 'Name may contain only alpha characters.']
].map((v) => validOrThrow(v[0])(v[1])))

const userSurnameValidator = validForAll([
	[isDefined, 'Surname is required.'],
	[notNull, 'Surname isrequired'],
	[isString, ''],
	[isNotShorter(2), 'Surname must be 2 characters minimum.'],
	[isNotLonger(70), 'Surname must be equal or shorter than 70 characters.'],
	[matches(/^[\w^_\-\d]+$/u), 'Surname may contain only alpha characters.']
].map((v) => validOrThrow(v[0])(v[1])))

const userBirthValidator = validForAll([
	[isDefined, 'Birth date is required.'],
	[notNull, 'Birth date is required.'],
	[isString, ''],
	[exactLength(10), ''],
	[matches(/^([0-9]{4})-(0[1-9]|1[0-2])-(0[1-9]|(1|2)[0-9]|(3[0-1]))$/), 
		'Invalid format. Please try again.'],
	[notMore(DateAdapter.getStringFromInterval('-5 years')),
		'You are too young.'],
	[notLess(DateAdapter.getStringFromInterval('-100 years')),
		'You are too old.']
].map((v) => validOrThrow(v[0])(v[1])))

// Regex not working
const userPasswordValidator = validForAll([
	[isDefined, 'Password is required.'],
	[notNull, 'Password isrequired'],
	[isString, ''],
	[isNotShorter(8), 'Password must be at least 8 characters long.'],
	[isNotLonger(60), 'Password must be equal or shorter than 60 characters.'],
	[matches(/^(?:(?=.*?\p{N})(?=.*?[\p{S}\p{P} ])(?=.*?\p{Lu})(?=.*?\p{Ll}))[^\p{C}]{8,60}$/u), 
		'Password must contain a lowercase and uppercase letter, ' + 
		'a number and special character.']
].map((v) => validOrThrow(v[0])(v[1])))

const nullValidator = (v) => true