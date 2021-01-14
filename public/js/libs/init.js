let getUri = window.location
let baseUri = getUri.protocol + "//" + getUri.host + 
	getUri.pathname.split('/').slice(0, 3).join('/')


