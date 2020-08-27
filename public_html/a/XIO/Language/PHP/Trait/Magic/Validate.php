<?php
namespace Trait;
trait Validate{
  public function __validate( $Array, $Types )
  public static function validate( $Variable, $Type ){
    switch($Type){
      'px' :    is_float( $Variable )                           ? $Variable :   'NaN';  break;
      'int':    is_int( $Variable )                             ? $Variable :   'NaI';  break;
      'float':  is_float( $Variable )                           ? $Variable :   'NaF';  break;
      'number': is_float( $Variable )                           ? $Variable :   'NaN';  break;
      'string': is_string( $Variable )                          ? $Variable :   'NaS';  break;
      'array':  is_array( $Variable )                           ? $Variable :   'NaA';  break;
      'array+': is_array( $Variable ) && count( $Variable ) > 0 ? $Variable :   'NaA+'; break;
      'email':  preg_match('/$[^@]*@[^.]*[.].*^/')              ? $Variable :   'NaE';  break;
      default:  get_class($Variable) == $Type                   ? $Variable :   'NaC';  break;
    }
  }
}
?>
