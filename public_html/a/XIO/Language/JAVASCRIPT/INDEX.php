<?php
LOAD( '../JAVASCRIPT/FUNCTION/INDEX.php' );
LOAD( '../JAVASCRIPT/DOM/INDEX.php' );
LOAD( '../JAVASCRIPT/EVENT/INDEX.php' );
LOAD( '../JAVASCRIPT/GUI/INDEX.php' );
IF( file_exists( __DIR__ . '/PAGE/' . substr( $_SERVER['SCRIPT_NAME'], 1, strlen( $_SERVER['SCRIPT_NAME'] ) - 5 ) . '/INDEX.php') ){
  LOAD( '../JAVASCRIPT/PAGE/' . substr( $_SERVER['SCRIPT_NAME'], 1, strlen( $_SERVER['SCRIPT_NAME'] ) - 5 ) . '/INDEX.php' );
}?>
<SCRIPT SRC='a/XIO/Language/JAVASCRIPT/FUNCTION/loadMenu.js?<?PHP ECHO RAND(0,99999999);?>'></SCRIPT>
<SCRIPT>
  var animate = true;
  var idt = 0;
  var idc = 0;
  var phases = Array(
    'laser',
    'vex'
  );
  var phase = 'cross';
  var phase_homed = true;
  var audio = false;
  function getAngleDegrees(fromX,fromY,toX,toY,force360 = false) {
    let deltaX = fromX - toX;
    let deltaY = fromY - toY;
    let radians = Math.atan2(deltaY, deltaX)
    let degrees = ((radians * 180) / Math.PI) - 90;
    if (force360) { degrees  = (degrees + 360) % 360; }
    return degrees;
  }
  function is_in_circle(circle_x, circle_y, r, x, y){
    var d = Math.sqrt((circle_x - x)^2 + (circle_y - y)^2);
    return d <= r;
  }
  function FOCUSMENU ( event ){
    var BROWSER = event.currentTarget;
    var MENU = BROWSER.children[0];
    var LIS = MENU.children;
    var i = 0;
    var O0 = LIS[0].getBoundingClientRect();
    var X0 = parseFloat( O0.left );
    var Y0 = parseFloat( O0.top );
    //alert( X0 + ' / ' + Y0 );
    var X1 = parseFloat( event.clientX );
    var Y1 = parseFloat( event.clientY );
    //alert( X1 + ' / ' + Y1 );
    while( LI = LIS[ i++ ] ){
      if( LI.classList.contains ( 'Parent' ) ){ continue; }
      var O2 = LI.getBoundingClientRect();
      var X2 = parseFloat( O2.left );
      var Y2 = parseFloat( O2.top );
      var ANGLE = getAngleDegrees ( X2, Y2, X1 - 25 , Y1 - 10);
      LINES = LI.children;
      //LI.style.transform = "rotate(" + ANGLE + "deg)";
      var i2 = 0;
      while ( LINE = LINES[ i2++ ] ){
        if( LINE.nodeName == 'A' ){
          continue;
        }
        accumulatedAngle = ANGLE + parseFloat( LINE.getAttribute( 'rel' ) );
        LINE.style.transform = "rotate(" + accumulatedAngle + "deg)";
        var O3 = LINE.getBoundingClientRect();
        var X3 = parseFloat( O3.left );
        var Y3 = parseFloat( O3.top );
        if( LINE.offsetTop >= -10){
          //LINE.style.display = 'none';
          //continue;
        } else {
          LINE.style.display = 'block';
        }
      }
    }
  }
  function keyPress(e){
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
  }
  function consoleKey(message){
    var node = document.createElement('div');
    node.style.position = 'absolute';
    node.style.left = 10;
    node.style.bottom = 10;
    var textnode = document.createTextNode(message);
    node.appendChild(textnode);
    document.getElementById('Header').appendChild(node);
    setTimeout(function(){node.remove();}, 5000);
  }
  function addCharacter(keyCode){
    var char = String.fromCharCode(keyCode);
    var node = document.createElement("span");
    var textnode = document.createTextNode(char);
    node.id = "character_" + idt + "_" + idc;
    node.className = 'character';
    idc++;
    node.appendChild(textnode);
    node.style.position = 'absolute';
    node.style.left = Math.floor(Math.random() * parseInt(document.getElementById('Header').offsetWidth));
    node.style.bottom  = Math.floor(Math.random() * parseInt(document.getElementById('Header').offsetHeight));
    document.getElementById('Header').appendChild(node);
  }
  function animateCharacters(){
    if(animate == true){
      var children = document.getElementById('Header').children;
      var i = 0;
      while(child = children[i++]){
        if(child.nodeName == 'SPAN'){
          left = parseInt(child.style.left);
          bottom  = parseInt(child.style.bottom);
          child.style.left = left == 0 ? 1 : left == parseInt(document.getElementById('Header').offsetWidth)   ? left - 1 : left + Math.floor((Math.random() * 3) - 1);
          child.style.bottom  = bottom  == 0 ? 1 : bottom  == parseInt(document.getElementById('Header').offsetHeight)  ? bottom - 1  : bottom  + Math.floor((Math.random() * 3) - 1);
        }
      }
      setTimeout(function(){animateCharacters();}, 60);
    }
  }
  function phasePoint(center, point){return point < center / 2 - 8 ? point + 8 : point - 8 ;}
  function phaseCharacters(b = false){
    if(b == false){
      animate = false;
      var Header = document.getElementById('Header');
      var children = Header.children;
      var count = 0;
      var removed = 0;
      var i = 0;
      var first = 0;
      while(child = children[i++]){
        if(child.nodeName == 'SPAN'){
          if(first == 0){left = parseInt(child.style.left);bottom = parseInt(child.style.bottom);first = 1;}
	    count++;
	    var check = true;
	    switch(phase){
	      case 'cross':
	        left = parseInt(child.style.left);
	        bottom = parseInt(child.style.bottom);
	       left = phasePoint(parseInt(document.getElementById('Header').offsetWidth), left);
	        bottom  = phasePoint(parseInt(document.getElementById('Header').offsetHeight), bottom);
	        break;
	      case 'laser':
	        left = phasePoint(parseInt(document.getElementById('Header').offsetWidth), left);
	        bottom  = phasePoint(parseInt(document.getElementById('Header').offsetHeight), bottom);
	        break;
	      case 'vex':
	       cleft = (parseInt(document.getElementById('Header').offsetWidth) / 2 - 8);
	        cbottom = (parseInt(document.getElementById('Header').offsetHeight) / 2 - 8);
	        left = parseInt(child.style.left);
	        bottom = parseInt(child.style.bottom);
	        var angle = Math.atan2(cbottom - bottom, cleft - left);
	        left   = left   + Math.cos(angle) * 8;
	        bottom = bottom + Math.sin(angle) * 8;
	        break;
	    }
	    child.style.left = left;
	    child.style.bottom = bottom;
            if((left > (parseInt(document.getElementById('Header').offsetWidth)  / 2) - 16    && left < (parseInt(document.getElementById('Header').offsetWidth)  / 2)) &&  (bottom   > (parseInt(document.getElementById('Header').offsetHeight) / 2) - 16 && bottom   < (parseInt(document.getElementById('Header').offsetHeight) / 2))){
              document.getElementById(child.id).remove();
	      i = i - 1;
	    }
         }
       }
       if(document.getElementsByClassName('character').length == 0 && !phase_homed){
	 animate = true;
	 b=true;
         animateCharacters();
       } else {setTimeout(function(){phaseCharacters(false);}, 25);}
    }
  }
  var Character = 0;
  function toggle(C){
    if(C == 'a' && C != Character){
      document.getElementById('eXte').style.display='none';
      document.getElementById('a').style.display='block';
      document.getElementById('v').style.display='none';
      document.getElementById('e').style.display='none';
      loadMenu(document.getElementById('a').children[0]);
      Character = 'a';
    } else if(C == 'v' && C != Character){
      document.getElementById('eXte').style.display='none';
      document.getElementById('a').style.display='none';
      document.getElementById('v').style.display='block';
      document.getElementById('e').style.display='none';
      loadMenu(document.getElementById('v').children[0]);
      Character = 'v';
    } else if(C == 'e' && C != Character){
      document.getElementById('eXte').style.display='none';
      document.getElementById('a').style.display='none';
      document.getElementById('v').style.display='none';
      document.getElementById('e').style.display='block';
      Character = 'e';
    } else{
      document.getElementById('eXte').style.display='block';
      document.getElementById('a').style.display='none';
      document.getElementById('v').style.display='none';
      document.getElementById('e').style.display='none';
      Character = 0;
    }
  }
</script>
<SCRIPT></SCRIPT>
