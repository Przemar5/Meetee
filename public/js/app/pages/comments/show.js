import CommentHandler from '../../../libs/entities/CommentHandler.js'

const commentTemplate = document.getElementById('commentTemplate')
const commentsContainer = document.getElementById('commentsBase')
const commentSection = document.getElementById('commentSection')
const commentFormTemplate = document.getElementById('commentFormCreate')
const noCommentsMsg = commentsContainer.querySelector('.no-result-msg')

let commentHandler = new CommentHandler()
commentHandler.commentsContainer = commentsContainer
commentHandler.commentTemplate = commentTemplate
commentHandler.noCommentsMsg = noCommentsMsg
commentHandler.loadComments(commentsContainer, commentTemplate)
commentHandler.initFormCreate({
	'formContainer': commentSection, 
	'formTemplate': commentFormTemplate, 
	'commentContainer': commentsContainer, 
	'commentTemplate': commentTemplate
})

let disabled = false
document.addEventListener('scroll', (e) => {
	let html = document.documentElement
	let body = document.body
	let yPos = (window.pageYOffset || html.scrollTop) - (html.clientTop || 0)
	let height = Math.max(body.scrollHeight, body.offsetHeight, 
    html.clientHeight, html.scrollHeight, html.offsetHeight)
	
	if (height - 200 < (yPos + window.innerHeight) && !disabled) {
		// loadMoreBtn.click()
		// loadMoreBtn.setAttribute('disabled', true)
		// setTimeout(() => loadMoreBtn.removeAttribute('disabled', true), 100)

		disabled = true
		commentHandler.loadComments(commentsContainer, commentTemplate)
		setTimeout(() => disabled = false, 100)
	}
})