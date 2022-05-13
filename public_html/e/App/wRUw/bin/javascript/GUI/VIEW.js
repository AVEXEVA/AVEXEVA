function VIEW( LINK ){
	var FILE = LINK.parentNode.parentNode.parentNode.getAttribute( 'rel' );
	document.location.href = '/' + FILE;
}