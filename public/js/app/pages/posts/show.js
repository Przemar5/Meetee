import PostHandler from '../../../libs/entities/PostHandler.js'

const postTemplate = document.getElementById('postTemplate')
const postsContainer = document.getElementById('posts')
const noPostsMsg = postsContainer.querySelector('.no-result-msg')

let postHandler = new PostHandler()
postHandler.postsContainer = postsContainer
postHandler.postTemplate = postTemplate
postHandler.noPostsMsg = noPostsMsg
postHandler.loadPosts()