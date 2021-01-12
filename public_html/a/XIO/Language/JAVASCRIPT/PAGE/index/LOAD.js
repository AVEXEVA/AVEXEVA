function LOAD( ID ){
  var ELEMENT = document.getElementById( ID );
  if( ELEMENT.classList.contains( 'BROWSER' ) ){
    loadMenu( ELEMENT.children[0] );
  }
}
