export default class Ajax {
	constructor () {
		this.xhr = new XMLHttpRequest()
	}

	get (uri, data, headers) {
		return this.request('GET', uri, data, headers)
	}

	post (uri, data, headers) {
		return this.request('POST', uri, data, headers)
	}

	request (method, uri, data, headers) {
		let callback = (resolve, reject) => {
			this.xhr.open(method, uri, true)
			this.xhr.setRequestHeader('Content-Type', 'application/json')
			this.xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest')
	    this.xhr.onload = () => {
	    	if (this.xhr.status >= 200 && this.xhr.status < 300) {
	        resolve(this.xhr.responseText);
	      } else {
	      	reject({
	      		status: this.xhr.status,
	      		statucText: this.xhr.statusText
	      	})
	      }
	    }
	    this.xhr.onerror = () => reject(this.xhr.statusText)
	    this.xhr.send(data)
	  }

		return new Promise(callback)
	}
}