function scanTables(){
  $.ajax({
    url : 'bin/PHP/POST/Table/scan.php',
    method: 'POST',
    success: function(){ location.reload(); }
  });
}
