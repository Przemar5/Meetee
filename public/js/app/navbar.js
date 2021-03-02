import Request from '../libs/http/Request.js'
import RouteDispatcher from '../libs/http/RouteDispatcher.js'

const navbarRequestsBtn = document.getElementById('navbarRequestsBtn') || null

navbarRequestsBtn.addEventListener('click', (e) => {
  let request = new Request()
  let route = RouteDispatcher.getRouteUri('users_relation_requests_page')
  let method = RouteDispatcher.getRouteMethod('users_relation_requests_page')

  request.get(route, (data) => {
    createRequestsDropdown(data)
  }, console.log)
})

const createRequestsDropdown = (data) => {
  let result = '<div class="navbar__dropdown">'
  for (let i in data) {
    if (data[i] instanceof Function) continue
    let profileRoute = RouteDispatcher.getRouteUri(
      'profiles_show_page', {'id': data[i]['sender_id']})
    result += '<div class="navbar__dropdown-option">' +
        '<a href="' + profileRoute + '">' + 
          data[i]['login'] +
        '</a>' + 
        ' wants to be your friend!' + 
        '<button class="navbar__dropdown-option-button--accept-friend">' + 
          'Accept' + 
        '</button>' + 
        '<button class="navbar__dropdown-option-button--reject-friend">' + 
          'Reject' + 
        '</button>' + 
      '</div>'
  }
  result += '</div>'
  console.log('works')
  navbarRequestsBtn.after(result.toHTMLElement())
}