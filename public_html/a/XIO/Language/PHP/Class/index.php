<?php
function AutoLoader($Class){
  $Class = str_replace('\\', '/', $Class);
  if(file_exists(PROJECT_ROOT.'/PHP/Class/' . $Class . '.php')){
    include(PROJECT_ROOT.'/PHP/Class/' . $Class . '.php');
  }
}
spl_autoload_register('AutoLoader');
?>

