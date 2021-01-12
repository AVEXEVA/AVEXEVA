var loops = 0;
var dir = true;
function loadMenu(Menu, angled = -0){
	var oangled = angled;
	//alert(angled);
	if( oangled <= -360 ){return;}
	var children = Menu.children;
	var parent;
	var i = 0;
	while(child = children[i++]){if(child.classList == 'Parent'){
		parent = child;
		parent.style.left = (parseInt(Menu.offsetWidth)   / 2) - (parseInt(parent.offsetWidth) / 2);
		parent.style.top  = (parseInt(Menu.offsetHeight)  / 4) - (parseInt(parent.offsetHeight) / 4);
	}}
	var angle = 360 / (children.length - 1);
	var radius = 150;
	var thickness = 1;
	var x1 = parseInt(parent.style.left);
	var y1 = parseInt(parent.style.top);
	var i = 0;
	while(child = children[i++]){
		if(child.classList.contains('Parent')){continue;}
		var x2 = x1 + radius * Math.cos(-angled*Math.PI/180);
		var y2 = y1 + radius * Math.sin(-angled*Math.PI/180);
		child.style.left = x2;
		child.style.top = y2;
		x3 = x1 + (child.offsetWidth / 2);
    y3 = y1 + (child.offsetHeight / 2);
    x4 = x2 + (parent.offsetWidth / 2);
    y4 = y2 + (parent.offsetHeight / 2);
    var length = Math.sqrt(((x3-x4) * (x3-x4)) + ((y3-y4) * (y3-y4)));
    var cx = ((x3 + x2) / 2) - (length / 2);
    var cy = ((y3 + y2) / 2) - (thickness / 2);
    var Line = document.createElement("LI");
		Line.className      = 'Line';
		Line.style.position = 'absolute';
		Line.style.height   = thickness;
		Line.style.width    = length / 1.5;
		Line.style.left     = -radius * Math.cos(angled*Math.PI/180) / 2 - 25;
		Line.style.top      = radius * Math.sin(angled*Math.PI/180) / 2 + 12;
		Line.style.transform = "rotate(" + -angled + "deg";
		child.appendChild(Line);
		angled = angled + angle;
	}

  //alert(loops);
	//console.log(loops);
	/*if( dir ){
		loops--;
		dir = loops > 0;
		//i = 0;
		//while( child == children[i++]){ children[i].remove(); }
		setTimeout(function(){loadMenu( Menu, angled + ( angle ) )}, 100 );
	} else {
		loops++;*/
		//dir = loops >= children.length;
		angled = oangled;
		setTimeout(function(){loadMenu( Menu, angled - ( angle ) )}, 100);
	//}
	//animateMenu(Menu);
}
