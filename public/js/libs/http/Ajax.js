// let Ajax = (function () {
// 	let xhr = new XMLHttpRequest()

// 	this.fun = async function () { return 1 };

// 	this.get = async function (uri) {
// 		// xhr.open('GET', uri)
// 		// xhr.onload = await function () { return xhr.responseText }
// 		// xhr.send().then(console.log(xhr.responseText))

// 		return await fun()
// 		// return uri
// 	}

// 	this.post = function (uri) {
// 		this.xhr.open('POST', uri)
// 		this.xhr.onload = await function () { return xhr.responseText }
// 		return this.xhr.send()
// 	}
// })

var getUri = window.location;
var baseUri = getUri.protocol + "//" + getUri.host + 
	getUri.pathname.split('/').slice(0, 3).join('/');

// let ajax = new Ajax()
// console.log(ajax.get('http://localhost/projects'))

function myAsyncFunction(url) {
  return new Promise((resolve, reject) => {
    const xhr = new XMLHttpRequest()
    xhr.open("GET", url)
    xhr.onload = () => resolve(xhr.responseText)
    xhr.onerror = () => reject(xhr.statusText)
    xhr.send()
  })
}

myAsyncFunction(baseUri).then(console.log(this))

// const promise1 = new Promise((resolve, reject) => {
//   setTimeout(() => {
//     resolve('foo')
//   }, 300);
// })

// promise1.then((value) => {
//   return value
// })

// console.log(promise1);


// const myfunction = async function(x) {
//   return x ** 2;
// }


// const start = async function() {
//   const result = await myfunction(2);
//   // return result
//   console.log(result);
// }

// // Call start
// start();






// ;(async function () {
// 	let response = await 
// })


// xhr = new XMLHttpRequest()

// xhr.open('GET', 'http://localhost/projects')
// xhr.onload = function() {
//     if (xhr.status === 200) {
//         console.log(xhr.responseText)
//     }
// }

// xhr.send()



// function myFunction() {
//   var elements = document.getElementsByClassName("formVal");
//   var formData = new FormData();

//   for (var i = 0; i < elements.length; i++) {
//     formData.append(elements[i].name, elements[i].value);
//   }
//   var xmlHttp = new XMLHttpRequest();
//     xmlHttp.onreadystatechange = function()
//     {
//       if(xmlHttp.readyState == 4 && xmlHttp.status == 200)
//       {
//         alert(xmlHttp.responseText);
//       }
//     }
//     xmlHttp.open("post", "server.php"); 
//     xmlHttp.send(formData); 
// }