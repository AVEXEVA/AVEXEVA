function redirect(data){
	var s = '?';
	s += stringEntity(data);
	document.location.href='index.php' + s;
}