class Ajax {
	constructor () {
		this.xhr = new XMLHttpRequest()
	}

	get (uri) {
		return this.request('GET', uri)
	}

	post (uri) {
		return this.request('POST', uri)
	}

	request (method, uri) {
		let callback = (resolve, reject) => {
			this.xhr.open(method, uri)
	    this.xhr.onload = () => resolve(this.xhr.responseText)
	    this.xhr.onerror = () => reject(this.xhr.statusText)
	    this.xhr.send()
	  }

		return new Promise(callback)
	}
}