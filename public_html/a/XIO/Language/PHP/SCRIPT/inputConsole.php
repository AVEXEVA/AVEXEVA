<?php if(isset($_POST['keyCode'])){
  if(in_array($_POST['keyCode'], $keyCodes) && is_int($_POST['keyCode'])){
      exec("py ../../Python/Input.py " . $_POST['keyCode']);
  }
}?>
