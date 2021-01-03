function newServiceCall(){
  $.ajax({
    url:"cgi-bin/php/element/map/Service_Call.php",
    method:"GET",
    success:function(code){
      $("body").append(code);
    }
  });
}
