export default class CommentTree {
	constructor (base) {
		this.base = base
		this.lastCommentId = 99999999999999
		this.userId = /\d+$/.exec(window.location.href)
		this.limit = 3
	}

	loadComments (parent, template, parentCommentId) {
		let ajax = new Ajax()
		let route = RouteDispatcher.getRouteUri('comments_select_process')
		route = route + '?user-id=' + this.userId + '&limit=' + this.limit + '&max-id=' + this.lastCommentId
		if (parentCommentId != null) route += '&parent=' + parentCommentId
		
		ajax.get(route, null, null)
			.then(this.callback(parent, template))
	}

	callback = (parent, template) => (data) => {
		data = JSON.parse(data)
		for (let i in data) {
			if (data[i] instanceof Function) continue
			let temp = this.prepareTemplate(template, data[i])
			parent.appendChild(temp)
			this.lastCommentId = data[i]['id']
		}

		if (data.length > 0) {
			let noCommentsMsg = parent.querySelector('p.no-result-msg')
			if (noCommentsMsg) parent.removeChild(noCommentsMsg)
		}
	}
}