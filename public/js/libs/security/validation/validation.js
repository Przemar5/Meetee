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

const isDefined = notType(undefined)

const notNull = notType(null)

const notEmpty = (value) => value !== '' && value !== []

const isString = isType('string')

const isNumber = isType('number')

const isObject = isType('object')

const isArray = isInstance(Array)

const isFunction = isInstance(Function)

const sameType = (first) => (second) => getType(first) === getType(second)

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

const bothAreTypeAnd = (type) => (func) => (a) => (b) => bothAreType(type)(a)(b) && func(a)(b)

const bothStringsAnd = (func) => (a) => (b) => bothAreTypeAnd('string')(func)(a)(b)

const bothNumbersAnd = (func) => (a) => (b) => bothAreTypeAnd('number')(func)(a)(b)

const numberEquals = (number) => (value) => bothNumbersAnd(equals)(number)(value)

const isGreater = (number) => (value) => bothNumbersAnd(more)(number)(value)

const isLess = (number) => (value) => bothNumbersAnd(less)(number)(value)

const isNotGreater = (number) => (value) => bothNumbersAnd(notMore)(number)(value)

const isNotLess = (number) => (value) => bothNumbersAnd(notLess)(number)(value)

const isShorter = (number) => (value) => bothStringsAnd(less)(number)(value)

const isLonger = (number) => (value) => bothStringsAnd(more)(number)(value)

const isNotShorter = (number) => (value) => bothStringsAnd(notLess)(number)(value)

const isNotLonger = (number) => (value) => bothStringsAnd(notMore)(number)(value)
