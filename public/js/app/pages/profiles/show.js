import Request from '../../../libs/http/Request.js'

const friendRequestForm = document.querySelector('.friend-process-form--request')
const friendCancelRequestForm = document.querySelector('.friend-process-form--cancel-request')
const friendDiscardForm = document.querySelector('.friend-process-form--discard')

friendRequestForm.addEventListener('submit', (e) => {
  e.preventDefault()
  let formData = new FormData(e.target)
  let route = e.target.getAttribute('action')
  let request = new Request()

  request.post(route, formData, (d) => {
    alert(d.message)
    e.target.classList.add('display-none')
    friendCancelRequestForm.classList.remove('display-none')
  }, (d) => console.log(d))
})

friendCancelRequestForm.addEventListener('submit', (e) => {
  e.preventDefault()
  let formData = new FormData(e.target)
  let route = e.target.getAttribute('action')
  let request = new Request()

  request.post(route, formData, (d) => {
    alert(d.message)
    e.target.classList.add('display-none')
    friendRequestForm.classList.remove('display-none')
  }, (d) => console.log(d))
})

friendDiscardForm.addEventListener('submit', (e) => {
  e.preventDefault()
  let formData = new FormData(e.target)
  let route = e.target.getAttribute('action')
  let request = new Request()

  request.post(route, formData, (d) => {
    alert(d.message)
    e.target.classList.add('display-none')
    friendRequestForm.classList.remove('display-none')
  }, (d) => console.log(d))
})