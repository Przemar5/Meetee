class TemplateGenerator {
	constructor () {
		this.template = null
		this.html = false
	}

	reset () {
		this.template = null
	}

	generate (html) {
		this.template = html
	}

	isHtml () {
		this.html = true
	}
}