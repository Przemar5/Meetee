// 'use strict'

// let hasOrError = (obj, prop, msg) => has(obj, prop) || throwError(msg)

// let has = (obj, prop) => this[prop] && this[prop] instanceof Function

// let throwError = (msg) => { throw new Error(msg) }

// Object.prototype.implementsMethod = function (method) {
// 	return (this[method] && this[method] instanceof Function) || false
// }

// Object.prototype.implementsProperty = function (prop) {
// 	return (this[prop] && !(this[prop] instanceof Function)) || false
// }

// Object.prototype.implMeth = function (method) {
// 	let msg = 'Method ' + method + ' is missing in ' + obj.constructor.name
// 	return (this.implementsMethod(method) || throwError(msg))
// }

// Object.prototype.implProp = function (prop) {
// 	let msg = 'Property ' + prop + ' is missing in ' + obj.constructor.name
// 	return (this.implementsProperty(prop) || throwError(msg))
// }

Object.prototype.forEachWithKey = function (func) {
	for (let i in this) {
		if (this[i] !== forEachWithKey) func(this[i], i)
	}
}

Object.prototype.mapWithKeysToArray = function (func) {
	let result = []
	for (let i in this) {
		if (this[i] !== forEachWithKey && this[i] !== undefined) {
			result.push(func(this[i], i))
		}
	}
	return result
}

Object.prototype.toArray = function () {
	let result = []
	for (let i in this) result.push([i, this[i]])
	return result
}

String.prototype.toHTMLElement = function () {
	let wrapper = document.createElement('div')
	wrapper.setAttribute('id', 'TEMP_WRAPPER')
	wrapper.innerHTML = this
	return wrapper.querySelector('#TEMP_WRAPPER > *')
}

HTMLElement.prototype.toString = function () {
	let wrapper = document.createElement('div')
	wrapper.setAttribute('id', 'TEMP_WRAPPER')
	wrapper.appendChild(this)
	return wrapper.innerHTML
}


// let IHireable = new Interface('IHireale', ['writeCode'], ['name'])

// class Developer {
// 	constructor (name) {
// 		this.name = name
// 	}

// 	writeCode () {
// 		console.log("I'm writing code.")
// 	}
// }

// class SoftwareHouse {
// 	constructor () {
// 		this.employees = []
// 	}

// 	hire (dev) {
// 		IHireable.isImplementedBy(dev)
// 		this.employees.push(dev)
// 	}
// }

// let sh = new SoftwareHouse()
// let dev = new Developer('Peter')
// sh.hire(dev)

// let C = (function () {
// 	this.a = 1
// 	let b = 2

// 	this.c = () => 1
// 	let d = () => 1
// })

// let obj = new C()
// console.log('Method a: ' + obj.implementsMethod('a'))
// console.log('Property a: ' + obj.implementsProperty('a'))
// console.log('Method b: ' + obj.implementsMethod('b'))
// console.log('Property b: ' + obj.implementsProperty('b'))
// console.log('Method c: ' + obj.implementsMethod('c'))
// console.log('Property c: ' + obj.implementsProperty('c'))
// console.log('Method d: ' + obj.implementsMethod('d'))
// console.log('Property d: ' + obj.implementsProperty('d'))
// console.log('Method a: ' + obj.implMeth('a'))
// console.log('Property a: ' + obj.implProp('a'))
// console.log('Method b:	' + obj.implMeth('b'))
// console.log('Property b: ' + obj.implProp('b'))
// console.log('Method c:	' + obj.implMeth('c'))
// console.log('Property c: ' + obj.implProp('c'))
// console.log('Method d:	' + obj.implMeth('d'))
// console.log('Property d: ' + obj.implProp('d'))