const getCookie = (name) => {
	const value = `; ${document.cookie}`
	const parts = value.split(`; ${name}=`)
	if (parts.length === 2) return parts.pop().split(';').shift()
}

setInterval(() => {
	let limit = parseInt(getCookie('user_last_activity_time'))
	let now = new Date().getTime() / 1000
	if (now > limit) console.log('bad')
}, 1000)