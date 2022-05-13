<?php
namespace Console;
class Input extends Magic {
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
      'ID'      => 'Console_Input',
      'Class'   => 'Console_Input'
    )));
    //Javascript
    //parent::Javascript(new Javascript\Console\Input);
    ?><SCRIPT>document.body.onKeyPress = "consoleInput(e);";</SCRIPT><?php
    ?><SCRIPT>function consoleInput(e){
      var keyCode = window.event ? event.keyCode : e.which ;
    	var phased;
    	if(keyCode == 88){phaseCharacters();}
    	else if(keyCode == 126){
    		consoleKey('Audio: ' + audio);
    	} else if(keyCode == 13){
    		idt++;
    		idc = 0;
    	} else if(keyCode == 60){
    		phased = phase;
    		phase = phases.shift();
    		phases.push(phased);
    		consoleKey('Phase:' + phase);
    	} else if(keyCode == 62){
    		phased = phase;
    		phase = phases.pop();
    		phases.unshift(phased);
    		consoleKey('Phase:' + phase);
    	} else if(keyCode == 63){
    		phase_homed = !phase_homed;
    		consoleKey('Phase Homed: ' + phase_homed);
    	} else {addCharacter(keyCode);}
      inputConsole(keyCode);
    }
  function inputConsole(keyCode){
    $.ajax({
      url:'a/XIO/Language/PHP/POST/inputConsole.php',
      data:{
        keyCode : keyCode
      },
      method:'POST',
      success:function(code){eval(code);}
    });
  }</SCRIPT><?php
  }
}
?>
