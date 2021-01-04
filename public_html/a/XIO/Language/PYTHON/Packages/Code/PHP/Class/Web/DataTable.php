<?php
namespace Web;
if(!class_exists('Web\Table')){require('cgi-bin/PHP/Classes/Web/Table.php');}
Class Datatable extends Table {
  protected $URL = NULL;
  protected $Method = NULL;
  protected $Success = NULL;
  public function __HTML(){
    $_SESSION['Table_Count'] = isset($_SESSION['Table_Count']) ? $_SESSION['Table_Count'] + 1 : 1;
    ?><table id='Datatable_<?php echo $_SESSION['Table_Count'];?>' class='<?php echo $this->__get('Name');?> Datatable'><?php
      $this->__THEAD();
      $this->__TBODY();
      $this->__TFOOT();
    ?></table><?php
    $this->__Javascript();
  }
  public function __TBODY(){
    ?><tbody><?php
      foreach($this->__get('Rows') as $Row){?><tr><?php
        foreach($Row as $Key=>$Value){?><td class='<?php echo $Key;?>'><?php echo $Value;?></td><?php }
      ?></tr><?php }
    ?></tbody><?php
  }
  public function __THEAD(){
    ?><thead><tr><?php
      foreach($this->__get('Columns') as $Column){$Column->__TH();}
    ?></thead><?php
  }
  public function __TFOOT(){
    ?><tfoot><tr><?php
      foreach($this->__get('Columns') as $Column){$Column->__TH();}
    ?></tfoot><?php
  }
  public function __Javascript(){
    ?><script>
      var Datatable_<?php echo $_SESSION['Table_Count'];?>;
      $(document).ready(function(){
        Datatable_<?php echo $_SESSION['Table_Count'];?> = $('table#Datatable_<?php echo $_SESSION['Table_Count'];?>').Datatable({});
      });
    </script><?php
  }
}
?>
