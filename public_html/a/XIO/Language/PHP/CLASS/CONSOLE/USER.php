<?php
namespace Console\Input;
class User extends Console\Input {
    //Functions
  public function __construct( $Array = array()){parent::__construct( $Array );}
	public function __constructor(){}
	public function __construction(){
    parent::CSS(new CSS\Style(array(
      'width'             =>  '100%',
      'height'            =>  '42px',
      'background-color'  =>  'transparent'
      'color'             =>  'white'
    )));
    parent::HTML(new DIV(array(
      'ID'    => 'Console_Input',
      'Class' => 'Console_Input'
    ))); 
    parent::Javascript(new Graphic\Console\Dance());
    parent::Javascript(new Graphic\Console\VEX());
    parent::Javascript(new Graphic\Console\Cross());
  }
}
?>
