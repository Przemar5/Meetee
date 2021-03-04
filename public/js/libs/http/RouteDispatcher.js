import Router from './Router.js'

export default class RouteDispatcher {
  static getRoute (name) {
    return this.routes[name]
  }

  static getRouteUri (name, args = []) {
    let pathInfo = this.routes[name]
    if (!pathInfo) return null
    let route = Router.baseUri() + pathInfo.uri
    let matches

    if (matches = route.match(/(?:\()([^\)]+)(?:\))/gu)) {
      for (let i in matches) {
        if (typeof matches[i] !== 'string') continue
        let argName = matches[i].substring(1, matches[i].length - 1)
        if (args[argName] === undefined || args[argName] === null) {
          throw new Error("Argument '" + i + "' not found in RouteDispatcher.getRouteUri")
        }
        route = route.replace(new RegExp('\\(' + argName + '\\)', 'ug'), args[argName])
      }
    }

    return route
  }

  static getRouteMethod (name) {
    let pathInfo = this.routes[name]
    if (!pathInfo) return null
    return pathInfo.method
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
      "uri": "/settings/account",
      "method": "GET"
    },
    "settings_account_process": {
      "uri": "/settings/account",
      "method": "POST"
    },
    "rest_countries_all": {
      "uri": "/rest/countries",
      "method": "GET"
    },
    "comments_select_process": {
      "uri": "/comments/select",
      "method": "GET"
    },
    "comments_create_process": {
      "uri": "/comments/(id)/create",
      "method": "POST"
    },
    "comments_update_process": {
      "uri": "/comments/(id)/update",
      "method": "POST"
    },
    "comments_delete_process": {
      "uri": "/comments/(id)/delete",
      "method": "POST"
    },
    "ratings_rate_process": {
      "uri": "/ratings/r/(id)/c/(commentId)/rate",
      "method": "POST"
    },
    "ratings_get_process": {
      "uri": "/ratings/c/(commentId)",
      "method": "GET"
    },
    "profiles_show_page": {
      "uri": "/profiles/(id)",
      "method": "GET"
    },
    "relations_request_process": {
      "uri": "/relations/(userId)/(relationId)/request",
      "method": "POST"
    },
    "relations_accept_process": {
      "uri": "/relations/(userId)/(relationId)/accept",
      "method": "POST"
    },
    "relations_discard_process": {
      "uri": "/relations/(userId)/(relationId)/discard",
      "method": "POST"
    },
    "relations_queue_page": {
      "uri": "/relations/to-accept",
      "method": "GET"
    }
  }
}