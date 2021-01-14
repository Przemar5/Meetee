let Ajax = (function () {
	let xhr = new XMLHttpRequest()

	this.get = (uri) => request('GET', uri)

	this.post = (uri) => request('POST', uri)

	let request = (method) => (uri) => {
		return new Promise((resolve, reject) => {
			xhr.open(method, uri)
	    xhr.onload = () => resolve(xhr.responseText)
	    xhr.onerror = () => reject(xhr.statusText)
	    return xhr.send()
	  })
	}
})



var getUri = window.location;
var baseUri = getUri.protocol + "//" + getUri.host + 
	getUri.pathname.split('/').slice(0, 3).join('/');


ajax = new Ajax()
console.log(ajax.get(baseUri).then(r => console.log(r)))
