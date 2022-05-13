function diamondMenu(Menu){
	var children = Menu.children;
	var parent;
	var i = 0;
	while(child = children[i++]){if(child.classList == 'Parent'){
		parent = child;
		parent.style.left = parseInt(document.getElementById('Browser').offsetWidth)   / 2;
		parent.style.top  = parseInt(document.getElementById('Browser').offsetHeight)  / 2;
	}}
	var angle = 360 / (children.length - 1);
	var distance = 150;
	var accumulate_angle = angle;
	if(children.length > 1){
		var pivot = children[0]; 
		pivot.style.top  = parseInt(parent.style.top) - distance;
		pivot.style.left = parseInt(parent.style.left);
		if(children.length > 2 ){
			var i = 1;
			var top  = parseInt(pivot.style.top);
			var left = parseInt(pivot.style.left);
			while(child = children[i++]){
				if(child.classList.contains('Parent')){continue;}
				if(accumulate_angle < 90){
					child.style.top  = top  + ((((accumulate_angle % 90) / 90) % 1) * distance);
					child.style.left = left + ((((accumulate_angle % 90) / 90) % 1) * distance);
				} else if(accumulate_angle == 90){
					child.style.top = top + distance;
					child.style.left = left + distance;
				} else if(accumulate_angle  < 180){
					child.style.top = top + distance + ((((accumulate_angle % 90) / 90) % 1) * distance);
					child.style.left = left + distance - ((((accumulate_angle % 90) / 90) % 1) * distance);	
				} else if(accumulate_angle == 180){
					child.style.top = top + distance * 2;
					child.style.left = left;
				} else if(accumulate_angle < 270){
					child.style.top = top + distance + ((((accumulate_angle % 90) / 90) % 1) * distance);
					child.style.left = left - distance + ((((accumulate_angle % 90) / 90) % 1) * distance);	
				} else if(accumulate_angle == 270){
					child.style.top = top + distance;
					child.style.left = left - distance;
				} else {
					child.style.top = top + distance - ((((accumulate_angle % 90) / 90) % 1) * distance);
					child.style.left = left - ((((accumulate_angle % 90) / 90) % 1) * distance);	
				}
				accumulate_angle = accumulate_angle + angle;
			}
		}
	}
}	