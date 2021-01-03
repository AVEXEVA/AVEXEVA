<?php
namespace Magic;
trait Methods {
 public function __construct($array = Null){
  self::__set($array);
 }
 public function __toString(){}
 public function __sleep($data = Null){
  if(is_null($data)){
   return self::__sleep(get_object_vars($this));
  } elseif(is_array($data)){
   if(count($data) > 0){
    foreach($data as $k=>$v){
     if(self::__isset($k)){$data[$k] = self::__get($k);}
    }
   }
   return $data;
  } elseif(self::__isset($data)){
   return array($data => self::__get($data));
  }
 }
 public function __call($function, $parameters = NULL){
   if(method_exists($this, $function)){
     return self::$function($parameters);
   } elseif(function_exists($function)) {
     return $function($parameters);
   }
 }
 public function __push($array, $value, $key = NULL){
   if(is_array($this->$array) && self::__isset($array, $key)){
     $this->$array = self::__isset($array, $key) ? array_merge($this->$array, array($key => $value)) : array_merge($this->$array, array($value));
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
 public function __isset($property){return property_exists($this, $property);}
 public function __destroy(){}
 public function __echo($key){
  if(self::__isset($key)){echo self::__get($key);}
  else{echo NULL;}
 }
}
?>
