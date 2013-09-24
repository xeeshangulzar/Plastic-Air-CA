// JavaScript Document
/*
makeRequest('/js/catalog.php?category=&ttt='+tttt(), searchCatalogue, '')

function searchCatalogue(content, nn){ //alert(content);
		if(content){
			document.getElementById("showCatalog").innerHTML = content;
		}
	}

*/
    function makeRequest(url, inner_func, other_arg) {
        var http_request = false;
        if (window.XMLHttpRequest) { // Mozilla, Safari,...
            http_request = new XMLHttpRequest();
            if (http_request.overrideMimeType) {
                http_request.overrideMimeType('text/xml');
                // See note below about this line
            }
        } else if (window.ActiveXObject) { // IE
            try {
                http_request = new ActiveXObject("Msxml2.XMLHTTP");
            } catch (e) {
                try {
                    http_request = new ActiveXObject("Microsoft.XMLHTTP");
                } catch (e) {}
            }
        }

        if (!http_request) {
            alert('Giving up :( Cannot create an XMLHTTP instance');
            return false;
        }
        http_request.onreadystatechange = function() { alertContents(http_request, inner_func, other_arg); };
        http_request.open('GET', url, true);
        http_request.send(null);
    }
	
	function makePostRequest(url, inner_func, snd, other_arg) {
        var http_request = false;
        if (window.XMLHttpRequest) { // Mozilla, Safari,...
            http_request = new XMLHttpRequest();
            if (http_request.overrideMimeType) {
                http_request.overrideMimeType('text/xml');
                // See note below about this line
            }
        } else if (window.ActiveXObject) { // IE
            try {
                http_request = new ActiveXObject("Msxml2.XMLHTTP");
            } catch (e) {
                try {
                    http_request = new ActiveXObject("Microsoft.XMLHTTP");
                } catch (e) {}
            }
        }

        if (!http_request) {
            alert('Giving up :( Cannot create an XMLHTTP instance');
            return false;
        }
        
		http_request.onreadystatechange = function() { alertContents(http_request, inner_func, other_arg); };
		http_request.open('POST', url, true);
		http_request.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
		http_request.setRequestHeader("Content-length", snd.length);
      	http_request.setRequestHeader("Connection", "close");
      	http_request.send(snd);

    }

    function alertContents(http_request, inner_func, other_arg) {

        if (http_request.readyState == 4) {
            if (http_request.status == 200) {
				inner_func(http_request.responseText, other_arg);
//				document.getElementById('loading').style.display='none';
            } else {
                //alert('There was a problem with the request1.');
            }
        }

    }
function getJsTime(){
	var someDate = new Date();
	return someDate.getTime();
	}