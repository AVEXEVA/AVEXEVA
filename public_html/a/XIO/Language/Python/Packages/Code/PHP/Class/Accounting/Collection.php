<?php
/*******************Requires******************/
//Classes
if(!class_exists('object')){require(ELEVATE_ROOT.'/php/classes/object/index.php');}
//Traits
/*Empty*/
/*******************Object******************/
if(!class_exists('object_collection')){
	class object_collection extends object {
		/********Variables**********/
		//Database
		protected $server       = '172.16.12.45';
		protected $database 	= 'nei';
		//Table
		protected $table 		= 'OpenAR';
		protected $primary_key  = 'Ref';
		protected $columns 		= array(
			'Ref'		=>'primary_key',
			'Loc'		=>'foreign_key',
			'fDate'		=>'date',
			'Due'		=>'date',
			'fDesc'		=>'varchar',
			'Original'	=>'currency',
			'Balance'	=>'currency',
			'Type'		=>'bit',
			'Selected'	=>'float',
			'TransID'	=>'int'
		);
		//Columns
		protected $Ref 			= Null;
		protected $Loc  		= Null;
		protected $fDate 		= Null;
		protected $Due 			= Null;
		protected $fDesc 		= Null;
		protected $Original 	= Null;
		protected $Balance 		= Null;
		protected $Type			= Null;
		protected $Selected		= Null;
		protected $TransID		= Null;
		//Meta
		protected $name 		= 'Ref';
		protected $singular     = 'collection';
		protected $plural 		= 'collections';
		//Relationships
		protected $aliases 		= array();
		protected $parents 		= array('Invoice');
		protected $children 	= array();
		protected $tranversal	= array(
			'location'	=>'Loc',
			'invoice' 	=>'Ref'
		);
		protected $references = array(
			'location'	=> array(
				'Custom3'
			),
			'customer'	=> array(
				'Name'
			),
			'invoice' 	=> array(
				'PO'
			)
		);
		//Aesthetics
		protected $icon 		= 'fas fa-money-check-alt';
		//Views
		protected $viewport		= array('singular');
		/*******************Functions******************/
		/*Empty*/
	}
}?>
