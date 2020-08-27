<?php
/*******************Requires******************/
//Classes
if(!class_exists('MS_SQL_Object')){require('cgi-bin/php/classes/MS_SQL_Object.php');}
/*******************object******************/
if(!class_exists('Item')){
	class Item extends MS_SQL_Object {
		/*******************Variables******************/
		//Columns
		protected $ID = Null;
		protected $Name = Null;
		protected $Description = NULL;
		protected $Category = NULL;
		/*******************Functions*************/
		public function __construct($array = array()){
			parent::__construct($array);
			parent::__connect();
		}
	}
}?>
