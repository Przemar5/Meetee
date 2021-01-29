import CommentHandler from '../../../libs/entities/CommentHandler.js'

const commentTemplate = document.getElementById('commentTemplate')
const commentsContainer = document.getElementById('commentsBase')
const noCommentsMsg = commentsContainer.querySelector('.no-result-msg')

let commentHandler = new CommentHandler()
commentHandler.commentsContainer = commentsContainer
commentHandler.commentTemplate = commentTemplate
commentHandler.noCommentsMsg = noCommentsMsg
commentHandler.loadComments()