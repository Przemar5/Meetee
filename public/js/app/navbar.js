import Request from '../libs/http/Request.js'

const navbarRequestsBtn = document.getElementById('navbarRequestsBtn')
const requestsDropdown = document.querySelector('.navbar__relation-requests')
const csrfToken = document.getElementById('generalCsrfToken')
const formsAcceptRelation = requestsDropdown.querySelectorAll('.form-relation--accept')
const formsDiscardRelation = requestsDropdown.querySelectorAll('.form-relation--discard')

let requestsToggled = false

navbarRequestsBtn.addEventListener('click', (e) => {
  if (requestsToggled) requestsDropdown.classList.add('display-none')
  else requestsDropdown.classList.remove('display-none')
  requestsToggled = !requestsToggled
})

formsAcceptRelation.forEach((form) => {
  form.addEventListener('submit', (e) => {
    e.preventDefault()
    let request = new Request()
    let formData = new FormData(e.target)
    let route = e.target.getAttribute('action')

    request.post(route, formData, (data) => {
      alert(data.message)
      e.target.closest('.navbar__relation-request').remove()
      if (!requestsDropdown.querySelector('.navbar__relation-request'))
        requestsDropdown.innerHTML = '<p>No results found.</p>'
    })
  })
})

formsDiscardRelation.forEach((form) => {
  form.addEventListener('submit', (e) => {
    e.preventDefault()
    let request = new Request()
    let formData = new FormData(e.target)
    let route = e.target.getAttribute('action')

    request.post(route, formData, (data) => {
      alert(data.message)
      e.target.closest('.navbar__relation-request').remove()
      if (!requestsDropdown.querySelector('.navbar__relation-request'))
        requestsDropdown.innerHTML = '<p>No results found.</p>'
    })
  })
})