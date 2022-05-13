function Login(){
 var formData = new FormData(document.getElementByID('Login'));
 $.ajax({
  url:"cgi-bin/php/POST/Login.php",
  method:"POST",
  data:formData,
  success:function(code){
   document.getElementByID('Body').innerHTML = code;
  }
 });
}
