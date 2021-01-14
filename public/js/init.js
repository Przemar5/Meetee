import Ajax from './libs/http/Ajax.js'
import Interface from './libs/global/Interface.js'
import Validator from './libs/security/validation/Validator.js'
import TemplateBuilder from './libs/view/TemplateBuilder.js'

let getUri = window.location
let baseUri = getUri.protocol + "//" + getUri.host + 
	getUri.pathname.split('/').slice(0, 3).join('/')

const diff = (diffMe, diffBy) => diffMe
	.split(diffBy)
	.join('')
	.replace(/(^\/)|(\/$)/, '')

let requestUri = diff(window.location.href, baseUri)
let path = './app/pages/' + requestUri + '.js'