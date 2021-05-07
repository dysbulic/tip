function get_fortune(event) {
    var callback = function(request) {
        if(request.readyState == 4 && request.status == 200) {
            fortuneElement = document.getElementById("fortune");
            switch(this.type) {
            case 'text/xml':
                fortuneElement.innerHTML = request.responseXML.childNodes[0].childNodes[0].data;
                break;
            case 'application/rss+xml':
                var item = request.responseXML.getElementsByTagName("description")[1];
                if(item != null) {
                    fortuneElement.innerHTML = item.childNodes[0].data;
                } else {
                    var text = request.responseText.replace(/</g, "&lt;");
                    fortuneElement.innerHTML = "<p><strong>Error:</strong> No fortune found in:</p><pre>" + text + "</pre>";
                }
                break;
            default:
                if(fortuneElement.childNodes.length > 1) {
                    while(fortuneElement.childNodes.length > 0) {
                        fortuneElement.removeChild(fortuneElement.lastChild);
                    }
                }
                if(fortuneElement.childNodes.length == 0) {
                    fortuneElement.appendChild(document.createTextNode(" ")); // if this is "" it doesn't work in Safari
                }
                fortuneElement.childNodes[0].data = request.responseText;
            }  
        }
    }
    callback.type = getSource(event).value;
    var request = getXMLHttpRequest(callback);
    request.open("GET", "rest_fortune.php", true);
    request.setRequestHeader("Accept", callback.type);
    request.send(null);
}
