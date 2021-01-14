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


class Interface {
	'use strict'

	constructor (name, methods = [], properties = []) {
		this.name = name
		this.methods = []
		this.properties = []

		for (let i in methods) {
			if (typeof methods[i] !== 'string') {
				throw new Error('Method names must be strings.')
			}
			this.methods.push(methods[i])
		}

		for (let i in properties) {
			if (typeof properties[i] !== 'string') {
				throw new Error('Property names must be strings.')
			}
			this.properties.push(properties[i])
		}
	}

	isImplementedBy (obj) {
		let currentMember

		if (obj) {
			for (let i in this.methods) {
				currentMember = this.methods[i]
				if (!obj[currentMember] || typeof obj[currentMember] !== 'function') {
					throw new Error("Object doesn't implement interface '" + 
						this.constructor.name + "'. Method '" + currentMember + "' not found.")
				}
			}

			for (let i in this.properties) {
				currentMember = this.properties[i]
				if (!obj[currentMember] || typeof obj[currentMember] === 'function') {
					throw new Error("Object doesn't implement interface '" + 
						this.constructor.name + "'. Property '" + currentMember + "' not found.")
				}
			}

		} else {
			throw new Error('No object to check.')
		}
	}
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