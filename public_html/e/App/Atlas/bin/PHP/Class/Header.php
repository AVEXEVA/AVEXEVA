<?php
Class Header extends Magic {
  //Variables
  protected $Session = NULL;
  //Functions
  public function __construct( $Data = array() ){
    parent::__construct( $Data );
    ?><header>
    	<nav>
    	    <div class='row'>
            <div class='col'>
    	        <a class="BankGothic" href="Home.php" >
    	            <img src='bin/Media/Image/Icon/NEI.png' width='30px' align='left' />
    	            Nouveau
    	        </a>
            </div>
    	    </div>
    	</nav>
    </header><?php }
}
?>
