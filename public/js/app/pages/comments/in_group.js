import CommentHandler from '../../../libs/entities/CommentHandler.js'

const commentTemplate = document.getElementById('commentTemplate')
const commentsContainer = document.getElementById('commentsBase')
const commentSection = document.getElementById('commentSection')
const commentFormTemplate = document.getElementById('commentFormCreate')
const btnLoadComments = document.getElementById('loadComments')
const noCommentsMsg = commentsContainer.querySelector('.no-result-msg')

let commentHandler = new CommentHandler()
commentHandler.commentsContainer = commentsContainer
commentHandler.commentTemplate = commentTemplate
commentHandler.noCommentsMsg = noCommentsMsg
commentHandler.settings = {
  'max_id': 999999999,
  'amount': 3,
  'group_id': /\d+$/.exec(window.location.href)
}
console.log({
	'formContainer': commentSection, 
	'formTemplate': commentFormTemplate, 
	'commentContainer': commentsContainer, 
	'commentTemplate': commentTemplate
})
commentHandler.initFormCreate({
	'formContainer': commentSection, 
	'formTemplate': commentFormTemplate, 
	'commentContainer': commentsContainer, 
	'commentTemplate': commentTemplate
})
commentHandler.loadComments(commentsContainer, commentTemplate)

btnLoadComments.addEventListener('click', (e) => {
  commentHandler.loadComments(commentsContainer, commentTemplate)
})
// let disabled = false
// document.onscroll = (e) => {
// 	let html = document.documentElement
// 	let body = document.body
// 	let yPos = (window.pageYOffset || html.scrollTop) - (html.clientTop || 0)
// 	let height = Math.max(body.scrollHeight, body.offsetHeight, 
//     html.clientHeight, html.scrollHeight, html.offsetHeight)
	
// 	if (height - 200 < (yPos + window.innerHeight) && !disabled) {
// 		loadMoreBtn.click()
// 		loadMoreBtn.setAttribute('disabled', true)
// 		setTimeout(() => loadMoreBtn.removeAttribute('disabled', true), 100)

// 		disabled = true
// 		commentHandler.loadComments(commentsContainer, commentTemplate, data)
// 		setTimeout(() => disabled = false, 100)
// 	}
// }
