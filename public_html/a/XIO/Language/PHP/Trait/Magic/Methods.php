<?php
namespace magic;
trait methods {
 public function __construct($array = Null){
  self::__set($array);
 }
 public function __toString($delimiter = null){
  $strings = [];
  foreach(get_object_vars($this) as $key=>$value){
   if(is_array($value) || is_object($value)){continue;}
   $strings[] = "{$key}='{$value}'";
  }
  echo implode($delimiter, $strings);
 }
 public function __sleep($data = Null){
  if(is_null($data)){
   return self::__sleep(self::__get('toSleep'));
  } elseif(is_array($data)){
   if(count($data) > 0){
    foreach($data as $k=>$v){
     if(self::__isset($k)){$data[$k] = self::__get($k);}
    }
   }
   return data;
  } elseif(self::__isset($data)){
   return array($data => self::__get($data));
  }
 }
 public function __get($key){
  if(is_array($key)){
   if(count($key) > 0){
    $keys = array();
    foreach($key as $i=>$k){
     $keys[] = self::__isset($k) ? self::__get($k) : '';
    }
    return $keys;
   }
  } elseif(self::__isset($key)) {
   return $this->$key;
  }
 }
 public function __set($key, $value = Null){
  if(is_array($key)){
   if(count($key) > 0){
    foreach($key as $k=>$v){
     if(self::__isset($k)){
      $this->$k = $v;
     }
    }
   }
  } elseif(self::__isset($key)){
    $this->$key = $value;
  }
 }
 public function __isset($key){
  return property_exists($this, $key);
 }
 public function __destroy(){}
 public function __echo($key){
  if(self::__isset($key)){echo self::__get($key);}
  else{echo NULL;}
 }
}
?>
