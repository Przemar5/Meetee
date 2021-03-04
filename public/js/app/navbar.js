import Request from '../libs/http/Request.js'
import RouteDispatcher from '../libs/http/RouteDispatcher.js'

const navbarRequestsBtn = document.getElementById('navbarRequestsBtn') || null

navbarRequestsBtn.addEventListener('click', (e) => {
  let request = new Request()
  let route = RouteDispatcher.getRouteUri('relations_queue_page')
  let method = RouteDispatcher.getRouteMethod('users_relation_requests_page')

  // console.log(RouteDispatcher)

  request.get(route, (data) => {
    createRequestsDropdown(data)
  }, console.log)
})

const createRequestsDropdown = (data) => {
  let result = document.createElement('div')
  result.classList.add('navbar__dropdown')

  for (let i in data) {
    if (data[i] instanceof Function) continue
    let profileRoute = RouteDispatcher.getRouteUri(
      'profiles_show_page', {'id': data[i]['sender_id']})
    let routeAccept = RouteDispatcher.getRouteUri(
      'relations_accept_process', {
        'userId': data[i]['sender_id'],
        'relationId': data[i]['relation_id']
      })
    let routeDiscard = RouteDispatcher.getRouteUri(
      'relations_discard_process', {
        'userId': data[i]['sender_id'],
        'relationId': data[i]['relation_id']
      })

    let element = 
      '<div class="navbar__dropdown-option">' +
        '<a href="' + profileRoute + '">' + 
          data[i]['login'] +
        '</a>' + 
        ' wants to be your friend!' + 
        '<form action="' + routeAccept + '" method="POST">' + 
          '<input type="hidden" name="" value="">' + 
          '<button type="submit">Accept</button>' + 
        '</form>'
        '<form action="' + routeDiscard + '" method="POST">' + 
          '<input type="hidden" name="" value="">' + 
          '<button type="submit">Discard</button>' + 
        '</form>'
      '</div>'
    element = element.toHTMLElement()
    let formAccept = element.querySelector(
      '.navbar__dropdown-option-button--accept-friend')

    btnAccept.addEventListener('click', (e) => {
      let request = new Request()
      let route = RouteDispatcher.getRouteUri('relations_accept_process', {
        'userId': data[i]['sender_id'],
        'relationId': data[i]['relation_id']
      })

      request.post(route, {}, (e) => {
        console.log('ok')
      })
    })
    result.append(element)
    // console.log(result)
  }
  navbarRequestsBtn.after(result)
}