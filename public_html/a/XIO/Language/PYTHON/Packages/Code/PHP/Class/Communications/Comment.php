<?php
namespace Communications;
if(!trait_exists('Traits\Magic_Methods')){require('cgi-bin/PHP/Traits/Magic_Methods.php');}
Class Comment {
  //Traits
  use Traits\Magic_Methods;
  //Variables
  protected $ID = NULL;
  protected $Table = NULL;
  protected $FK = NULL;
  protected $User = NULL;
  protected $Text = NULL;
  protected $Timestamp = NULL;
  //Functions
  public function __HTML(){?><div class='Comment'><div class='Text'><pre><?php echo $this->__get('Text');?></pre></div><div class='User'>Posted by <span class='User_Name'><?php $this->__get('User')->__get('Name');?></span> at <span class='Timestamp'><?php echo $this->__get('Timestamp');?></span></div><?php }
}
?>
