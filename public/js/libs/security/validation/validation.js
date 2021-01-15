// Base
const first = (array) => array[0]

const butFirst = (array) => array.slice(1)

const last = (array) => array[array.length - 1]

const butLast = (array) => array.slice(0, array.length - 1)

const typeOf = (value) => typeof value

const isType = (type) => (value) => typeof value === type

const notType = (type) => (value) => !isType(type)(value)

const isInstance = (of) => (value) => value instanceof of

const notInstance = (of) => (value) => !isInstance(of)(type)

const equals = (a) => (b) => a === b

const notEquals = (a) => (b) => a !== b

const both = (a, b) => a && b

const any = (a, b) => a || b

const allTrue = (array) => array.reduce(both)

const allValid = (func) => (array) => array.map(func).reduce(both)

const validForAll = (funcs) => (value) => {
	if (funcs.length === 0) return (value)
	else if (funcs.length === 1) return funcs[0](value)
	
	return funcs[0](value) && validForAll(butFirst(funcs))(value)
}

// Specific

const isDefined = (value) => notType(undefined)(value)

const notNull = (value) => notType(null)(value)

const notEmpty = (value) => value !== '' && value !== []

const isString = (value) => isType('string')(value)

const isNumber = (value) => isType('number')(value)

const isObject = (value) => isType('object')(value)

const isArray = (value) => isInstance(Array)(value)

const isFunction = (value) => isInstance(Function)(value)

const sameType = (a) => (b) => getType(a) === getType(b)

const allAreType = (type) => (array) => isArray(array) && isString(type) && 
	allValid(isType(type))(array)

const allAreInstance = (instance) => (array) => isArray(array) && 
	allValid(isInstance(instance))(array)

const allValidate = (func) => (array) => array.map(e => func(e)).reduce(both(a, b))

const bothValidate = (func) => (first) => (second) => func(first) && func(second)

const bothAreType = (type) => bothValidate(isType(type))

const bothAreInstance = (instance) => bothValidate(isInstance(type))

const bothNumbers = (a) => (b) => bothAreType('number')(a)(b)

const bothStrings = (a) => (b) => bothAreType('string')(a)(b)

const bothArrays = (a) => (b) => bothAreInstance(Array)(a)(b)

const less = (a) => (b) => b < a

const more = (a) => (b) => b > a

const notLess = (a) => (b) => b >= a

const notMore = (a) => (b) => b <= a

const bothAreTypeAnd = (type) => (func) => (a) => (b) => {
	return bothAreType(type)(a)(b) && func(a)(b)
}

const bothStringsAnd = (func) => (a) => (b) => bothAreTypeAnd('string')(func)(a)(b)

const bothNumbersAnd = (func) => (a) => (b) => bothAreTypeAnd('number')(func)(a)(b)

const numberEquals = (number) => (value) => bothNumbersAnd(equals)(number)(value)

const isGreater = (number) => (value) => bothNumbersAnd(more)(number)(value)

const isLess = (number) => (value) => bothNumbersAnd(less)(number)(value)

const isNotGreater = (number) => (value) => bothNumbersAnd(notMore)(number)(value)

const isNotLess = (number) => (value) => bothNumbersAnd(notLess)(number)(value)

const isBetween = (min, max) => (value) => allValid(isNumber)([min, max, value]) && 
	isNotLess(min)(value) && isNotGreater(max)(value)

const isShorter = (number) => (value) => isString(value) && 
	isNumber(number) && less(number)(value.length)

const isLonger = (number) => (value) => isString(value) && 
	isNumber(number) && more(number)(value.length)

const isNotShorter = (number) => (value) => isString(value) && 
	isNumber(number) && notLess(number)(value.length)

const isNotLonger = (number) => (value) => isString(value) && 
	isNumber(number) && notMore(number)(value.length)

const lengthBetween = (min, max) => (value) => isString(value) && 
	isNumber(number) && between(min, max)(value.length)

const exactLength = (number) => (value) => isString(value) && 
	isNumber(number) && equals(number)(value.length)

const matches = (pattern) => (value) => isString(value) && 
	notNull(value.match(pattern))

// Specific validators

const validOrThrow = (validator) => (msg) => (a) => {
	if (validator(a)) return true
	throw new Error(msg)
}