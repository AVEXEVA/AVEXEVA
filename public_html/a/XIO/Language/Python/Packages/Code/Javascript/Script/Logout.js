function Logout(){
 $.ajax({
  url:"cgi-bin/PHP/Scripts/Logout.php",
  method:"POST",
  data:formData,
  success:function(code){
   document.getElementByID('Body').innerHTML = code;
  }
 });
}
