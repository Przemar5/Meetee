let userNameValidator = validForAll([
	[isDefined, 'Name is required.'],
	[notNull, 'Name cannot be empty.'],
	[isString, ''],
	[isNotShorter(2), 'Name must be 2 characters minimum.'],
	[isNotLonger(40), 'Name must be equal or shorter than 40 characters long.'],
	[matches(/^[\w^_\d]+$/u), 'Name may contain only alpha characters.']
].map((v) => validOrThrow(v[0])(v[1])))

let userSurnameValidator = validForAll([
	[isDefined, 'Surname is required.'],
	[notNull, 'Surname isrequired'],
	[isString, ''],
	[isNotShorter(2), 'Surname must be 2 characters minimum.'],
	[isNotLonger(70), 'Surname must be equal or shorter than 70 characters.'],
	[matches(/^[\w^_\-\d]+$/u), 'Surname may contain only alpha characters.']
].map((v) => validOrThrow(v[0])(v[1])))

// Regex not working
let userPasswordValidator = validForAll([
	[isDefined, 'Password is required.'],
	[notNull, 'Password isrequired'],
	[isString, ''],
	[isNotShorter(8), 'Password must be at least 8 characters long.'],
	[isNotLonger(60), 'Password must be equal or shorter than 60 characters.'],
	[matches(/^(?:(?=.*?\p{N})(?=.*?[\p{S}\p{P} ])(?=.*?\p{Lu})(?=.*?\p{Ll}))[^\p{C}]{8,60}$/u), 
		'Password must contain a lowercase and uppercase letter, ' + 
		'a number and special character.']
].map((v) => validOrThrow(v[0])(v[1])))

