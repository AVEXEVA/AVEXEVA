function GET(URL, callback)
{
    var Request  = new XMLHttpRequest();
    Request.onreadystatechange = function() { 
        if (Request.readyState == 4 && Request.status == 200)
            callback(Request.responseText);
    }
    Request.open("GET", URL, true);
    Request.send(null);
}