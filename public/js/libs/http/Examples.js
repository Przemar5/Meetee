
let xhr = new XMLHttpRequest();
xhr.open('GET', 'myservice/username?id=some-unique-id');
xhr.onload = () => {
    if (xhr.status === 200) {
        alert('User\'s name is ' + xhr.responseText);
    }
    else {
        alert('Request failed.  Returned status of ' + xhr.status);
    }
};
xhr.send();


function param(object) {
    var encodedString = '';
    for (var prop in object) {
        if (object.hasOwnProperty(prop)) {
            if (encodedString.length > 0) {
                encodedString += '&';
            }
            encodedString += encodeURI(prop + '=' + object[prop]);
        }
    }
    return encodedString;
}


var xhr = new XMLHttpRequest();
xhr.open('PUT', 'myservice/user/1234');
xhr.setRequestHeader('Content-Type', 'application/json');
xhr.onload = function() {
    if (xhr.status === 200) {
        var userInfo = JSON.parse(xhr.responseText);
    }
};
xhr.send(JSON.stringify({
    name: 'John Smith',
    age: 34
}));


var formData = new FormData(),
    file = document.getElementById('test-input').files[0],
    xhr = new XMLHttpRequest();

formData.append('file', file);
xhr.open('POST', 'myserver/uploads');
xhr.send(formData);


var file = document.getElementById('test-input').files[0],
    xhr = new XMLHttpRequest();

xhr.open('POST', 'myserver/uploads');
xhr.setRequestHeader('Content-Type', file.type);
xhr.send(file);


var xhr = new XMLHttpRequest();
xhr.open('POST', 'http://someotherdomain.com');
xhr.withCredentials = true;
xhr.setRequestHeader('Content-Type', 'text/plain');
xhr.send('sometext');