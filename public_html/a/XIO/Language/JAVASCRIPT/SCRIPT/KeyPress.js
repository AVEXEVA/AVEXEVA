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
		var Header = document.body.children[0];
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
				if(     (left > (parseInt(document.getElementById('Header').offsetWidth)  / 2) - 16    && left < (parseInt(document.getElementById('Header').offsetWidth)  / 2))
					&&  (bottom   > (parseInt(document.getElementById('Header').offsetHeight) / 2) - 16 && bottom   < (parseInt(document.getElementById('Header').offsetHeight) / 2))){
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
