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