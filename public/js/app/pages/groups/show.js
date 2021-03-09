import Request from '../../../libs/http/Request.js'

const groupJoinForm = document.querySelector('.group-panel__form--join')
const groupCancelJoinRequestForm = document.querySelector('.group-panel__form--cancel-request')
const groupUnjoinForm = document.querySelector('.group-panel__form--unjoin')

groupJoinForm.addEventListener('submit', (e) => {
  e.preventDefault()
  let formData = new FormData(e.target)
  let route = e.target.getAttribute('action')
  let request = new Request()

  request.post(route, formData, (d) => {
    alert(d.message)
    e.target.classList.add('display-none')
    groupCancelJoinRequestForm.classList.remove('display-none')
  }, (d) => console.log(d))
})

groupCancelJoinRequestForm.addEventListener('submit', (e) => {
  e.preventDefault()
  let formData = new FormData(e.target)
  let route = e.target.getAttribute('action')
  let request = new Request()

  request.post(route, formData, (d) => {
    alert(d.message)
    e.target.classList.add('display-none')
    groupJoinForm.classList.remove('display-none')
  }, (d) => console.log(d))
})

groupUnjoinForm.addEventListener('submit', (e) => {
  e.preventDefault()
  let formData = new FormData(e.target)
  let route = e.target.getAttribute('action')
  let request = new Request()

  request.post(route, formData, (d) => {
    alert(d.message)
    e.target.classList.add('display-none')
    groupJoinForm.classList.remove('display-none')
  }, (d) => console.log(d))
})