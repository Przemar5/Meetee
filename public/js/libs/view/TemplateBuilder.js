export default class TemplateBuilder {
	constructor () {
		this.template = null
	}

	reset () {
		this.template = null
	}

	generate (html) {
		this.template = html
	}

	insert (data) {
		this.data = data
	}

	build () {
		//
	}
}