<?php
NAMESPACE DIRECTORY;
function combineFiles($f){
  $data = array();
  foreach(scandir($f) as $fs){
    if($fs == '..'){continue;}
    if($fs == '.'){continue;}
    if(is_dir($f . '/' . $fs)){array_merge(combineFiles($f . '/' . $fs));}
    else {
      $file = fopen($f . "/" . $fs, "r");
      $data[] = fread($file, filesize($f . "/" . $fs));
      fclose($file);
    }
  }
  return $data;
}
?>
