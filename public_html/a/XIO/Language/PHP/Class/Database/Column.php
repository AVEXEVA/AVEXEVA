<?php
namespace Database;
class Column extends Magic {
  //Variables
  private $ID;
  private $Table;
  private $Name;
  private $Ordinal;
  //Functions
  public function __construct( $Array = array()){parent::__construct( $Array );}
	public function __constructor(){}
	public function __construction(){parent::DIV(array(
		'ID'    => 'File_' 					. str_replace('/', '_', parent::__get('Path')),
		'Name'  => 'File_' 					. str_replace('/', '_', parent::__get('Path')),
		'Class' => 'Database_Table_Column'
		'HTML'  => parent::__get('Name') //new Elements()
	));}
}
?>
