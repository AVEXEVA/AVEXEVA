var pages = [];
$(document).ready(function(){page();})
function page(){pages.push(getUrlVars());}
function page_back(){
  return document.location.href='index.php?' + toQueryString(pages.pop());
}
