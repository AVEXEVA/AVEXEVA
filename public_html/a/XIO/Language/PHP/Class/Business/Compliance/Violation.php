<?php
/*******************Requires******************/
//Classes
if(!class_exists('object')){require(ELEVATE_ROOT.'php/classes/object/index.php');}
//Traits
/*EMPTY*/
/*******************Class******************/
if(!class_exists('object_violation')){
	class object_violation extends object {
		/*******************Variables******************/
		//Database
		protected $server       = 	'172.16.12.45';
		protected $database 	= 	'nei';
		//Table
		protected $table 		= 	'Violation';
		protected $primary_key 	= 	'ID';
		protected $columns 		= 	array(
			'ID'				=>	'primary_key',
			'Loc'				=>	'foreign_key',
			'Job'				=>	'foreign_Key',
			'Name'				=>	'varchar',
			'fDate'				=>	'datetime',
			'Status'			=>	'varchar'
		);
		//Columns
		protected $ID 			= 	Null;
		protected $Name 		= 	Null;
		protected $fDate 		= 	Null;
		protected $Status		= 	Null;
		protected $Loc 			= 	Null;
		protected $Job 			= 	Null;
		//Meta
		protected $singular 	= 	'violation';
		protected $plural 		= 	'violations';
		protected $name 		= 	'Name';
		//Relationships
		protected $aliases 		= 	array();
		protected $parents 		= 	array('location','job');
		protected $children		= 	array('ticket');
		protected $tranversal 	= 	array(
			'location' 			=>	'Loc',
			'job'				=>	'Job'
		);
		protected $references = array(
			'job'	=> array(
				'ID',
				'fDesc',
				'Status'
			)
		);
		//View
		protected $icon 		= 	'fa fa-exclamation-triangle';
		//View
		protected $viewport		= 	array();
		/*******************Variables******************/
		//Magic Methods
		//Select By Columns
		//Select Columns
		public function __select_Status(){
			?><select name='Status'><?php
				?><option value=''>Select</option><?php
				$r = sqlsrv_query($this->__get('connection'),"
					SELECT   {$this->__get('table')}.Status
					FROM     {$this->__get('database')}.dbo.{$this->__get('table')}
					GROUP BY {$this->__get('table')}.Status
				;");
				if($r){while($row = sqlsrv_fetch_array($r)){?><option value='<?php echo $row['Status'];?>'><?php echo $row['Status'];?></option><?php }}
			?></select><?php
		}
	}
}?>
