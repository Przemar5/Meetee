export default class Request {
	set route (route) {
		this.route = route
	}

	set data (data) {
		this.data = data
	}

	set succesResponse (success) {
		this.successResonse = successResonse
	}

	set errorResponse (error) {
		this.errorResponse = errorResponse
	}

	get (route, success, error) {
		fetch(route, {
			method: 'GET'
		}).then((response) => {
			if (response.ok) {
				return response.json()
			}
			return Promise.reject(response)
		})
		.then(success)
		.catch(error)
	}

	post (route, data, success, error) {
		fetch(route, {
			method: 'POST',
			body: data
		}).then((response) => {
			if (response.ok) {
				return response.json()
			}
			return Promise.reject(response)
		})
		.then(success)
		.catch(error)
	}
}