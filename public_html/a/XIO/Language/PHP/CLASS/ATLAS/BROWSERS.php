<?PHP
NAMESPACE ATLAS;
CLASS BROWSERS EXTENDS \INDEX {
  PUBLIC FUNCTION __CONSTRUCT( $_ARGS = NULL ){
    NEW \ATLAS\BROWSER( ARRAY( 'NAME' => 'a' ) );
    NEW \ATLAS\BROWSER( ARRAY( 'NAME' => 'v' ) );
    SELF::e();
    SELF::eXte();
  }
  PRIVATE FUNCTION e(){
    ?><DIV ID='e' class='Browser' style='display:<?php echo isset($_GET['Image']) ? 'block' : 'none';?>;'>
  		<DIV Class='Menu'>
  			<?php foreach(scandir('e/Picture/') as $Picture){
  				if($Picture == '..'){continue;}
  				if($Picture == '.'){continue;}
  				?><DIV><a href='ATLAS.php?Image=<?php echo $Picture;?>'><?php echo $Picture;?></a></DIV>
  			<?php }?></DIV>
  		<DIV Class='Container' style='height:100%;<?php echo isset($_GET['Image']) && substr($_GET['Image'], strlen($_GET['Image']) - 3, 3) == 'png' ? 'background-color:black;' : null;?>'><img src='e/Picture/<?php echo isset($_GET['Image']) ? $_GET['Image'] : 'XXXNULLXLUNNXXX.jpg';?>' style='object-fit:contain;height:100%;width:100%;object-position:top;' /></DIV>
  		<DIV Class='Space' style='height:100%;<?php echo isset($_GET['Image']) && substr($_GET['Image'], strlen($_GET['Image']) - 3, 3) == 'png' ? 'background-color:black;' : null;?>'>&nbsp;</DIV>
  	</DIV><?PHP
  }
  PRIVATE FUNCTION eXte(){
    ?><PRE ID='eXte' style='display:<?php
      echo  !  (isset($_GET['File']) || isset($_GET['Folder']))
            || (isset($_GET['File']) && !in_array(substr($_GET['File'], 0, 1), array('a','v')) && !isset($_GET['Folder']))
            || !in_array(substr($_GET['Folder'], 0, 1), array('a','v')) ? 'block' : 'none';?>;'><?PHP REQUIRE('a/Xt/X');?></PRE><?PHP
  }
}?>
