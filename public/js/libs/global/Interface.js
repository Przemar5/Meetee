export default class Interface {
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