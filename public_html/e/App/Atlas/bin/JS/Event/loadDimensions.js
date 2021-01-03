function loadDimensions( Args ){
  Args.Window = {
    Width : parseInt( window.innerWidth ),
    Height : parseInt( window.innerHeight )
  };
  if( Args.Window.Width < 1000 ){
    document.getElementById('Menu').style.width = 'Menu' in Args ? Args.Menu.Width : '100%' ;
    document.getElementById('Menu').style.minWidth = 'Menu' in Args ? Args.Menu.Width : '100%' ;
    document.getElementsByClassName('Page')[0].style.width = 'Page' in Args ? Args.Page.Width : '100%' ;
    document.getElementsByClassName('Page')[0].style.minWidth = 'Page' in Args ? Args.Page.Width : '100%' ;
  } else if(Args.Window.Width >= 2400){
    document.getElementById('Menu').style.width = 'Menu' in Args ? Args.Menu.Width : '350px' ;
    document.getElementById('Menu').style.minWidth = 'Menu' in Args ? Args.Menu.Width : '350px' ;
    document.getElementsByClassName('Page')[0].style.width = 'Page' in Args ? Args.Page.Width : 2050 + 'px' ;
    document.getElementsByClassName('Page')[0].style.minWidth = 'Page' in Args ? Args.Page.Width : 2050 + 'px' ;
  } else {
    document.getElementById('Menu').style.width = 'Menu' in Args ? Args.Menu.Width : '350px' ;
    document.getElementById('Menu').style.minWidth = 'Menu' in Args ? Args.Menu.Width : '350px' ;
    document.getElementsByClassName('Page')[0].style.width = 'Page' in Args ? Args.Page.Width : ( Args.Window.Width - 350 ).toString() + 'px' ;
    document.getElementsByClassName('Page')[0].style.minWidth = 'Page' in Args ? Args.Page.Width : ( Args.Window.Width - 350 ).toString() + 'px' ;
  }
}
