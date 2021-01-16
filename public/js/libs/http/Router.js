export default class Router {
	static baseUri () {
		return window.location.protocol + "//" + window.location.host + 
			window.location.pathname.split('/').slice(0, 4).join('/')
	}

	static pathInfo () {
		let diff = (diffMe, diffBy) => diffMe
			.split(diffBy)
			.join('')
			.replace(/(^\/)|(\/$)/, '')

		return diff(window.location.href, this.baseUri)
	}
}