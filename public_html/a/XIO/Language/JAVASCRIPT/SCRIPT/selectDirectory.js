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
