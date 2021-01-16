import Router from './Router.js'

export default class RouteDispatcher {
	static getRoute (name) {
		return this.routes[name]
	}

	static getRouteUri (name) {
		return Router.baseUri() + this.routes[name].uri
	}

	static getRouteMethod (name) {
		return this.routes[name].method
	}

	static routes = {
		"test": {
			"uri": "/test",
			"method": "GET"
		},
		"home": {
			"uri": "/",
			"method": "GET"
		},
		"registration_page": {
			"uri": "/register",
			"method": "GET"
		},
		"registration_process": {
			"uri": "/register",
			"method": "POST"
		},
		"registration_verify": {
			"uri": "/register/verify",
			"method": "POST"
		},
		"registration_resend_page": {
			"uri": "/register/resend",
			"method": "GET"
		},
		"registration_resend_process": {
			"uri": "/register/resend",
			"method": "POST"
		},
		"login_page": {
			"uri": "/login",
			"method": "GET"
		},
		"login_process": {
			"uri": "/login",
			"method": "POST"
		},
		"logout": {
			"uri": "/logout",
			"method": "GET"
		},
		"forgot_password_page": {
			"uri": "/forgot-password",
			"method": "GET"
		},
		"forgot_password_process": {
			"uri": "/forgot-password",
			"method": "POST"
		},
		"forgot_password_verify": {
			"uri": "/forgot-password/verify",
			"method": "POST"
		},
		"reset_password_page": {
			"uri": "/reset-password",
			"method": "GET"
		},
		"reset_password_process": {
			"uri": "/reset-password",
			"method": "POST"
		},
		"delete_account_page": {
			"uri": "/delete-account",
			"method": "GET"
		},
		"delete_account_process": {
			"uri": "/delete-account",
			"method": "POST"
		},
		"main_page": {
			"uri": "/main",
			"method": "GET"
		},
		"settings_index_page": {
			"uri": "/settings",
			"method": "GET"
		},
		"settings_account_page": {
			"uri": "/account",
			"method": "GET"
		},
		"settings_account_process": {
			"uri": "/account",
			"method": "POST"
		}
	}
}