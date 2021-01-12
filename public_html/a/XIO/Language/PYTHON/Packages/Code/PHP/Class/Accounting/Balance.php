<?php
/*******************Requires******************/
//Classes
if(!class_exists('MS_SQL_Object')){require('cgi-bin/php/classes/MS_SQL_Object.php');}
//Traits
/*Empty*/
/*******************Object******************/
if(!class_exists('Invoice')) {
	class Invoice extends MS_SQL_Object {
		/*******************Variables******************/
		//Vars
		protected $table = 'Invoice';
		protected $primary_key = 'ID';
		protected $columns = array('ID', 'Name', 'Description', 'Customer', 'Location', 'Amount', 'Created', 'Billed');
		//Columns
		protected $ID = NULL;
		protected $Name = NULL;
		protected $Description = NULL;
		protected $Customer = NULL;
		protected $Location = NULL;
		protected $Amount = NULL;
		protected $Created = NULL;
		protected $Billed = NULL;
		//Extra Columns
		protected $Customer_ID = NULL;
		protected $Customer_Name = NULL;
		protected $Location_ID = NULL;
		protected $Locationn_Name = NULL;
		//Arrays
		protected $Tickets = NULL;
		//Functions
		public function __construct($array = array()){
			parent::__construct($arrays);
			parent::__connect();
		}
		private function __select_Entity($SQL_END, $PARMATERS){
			if(!is_numeric(parent::__get('ID'))){return FALSE;}
			$r = sqlsrv_query(parent::__get('connection'),
				"	SELECT  TOP 1
									Invoice.*,
									Customer.ID AS Customer_ID,
									Customer.Name AS Customer_Name,
									[Location].ID AS Location_ID,
									[Location].Name AS Location_Name
					FROM 		Invoice
									LEFT JOIN Customer 		ON Invoice.Customer 		= Customer.ID
									LEFT JOIN [Location] 	ON Invoice.[Location] 	= [Location].ID
					{$SQL_END}
				;", $PARAMETERS);
		}
		public function __select(){
			$SQL_END = "WHERE [Invoice].[ID] = ?";
			$PARAMETERS = array(parent::__get('ID'));
			self::__select_Entity($SQL_END, $PARAMATERS);
		}
		public function __next(){
			$SQL_END = "WHERE [Invoice].[ID] > ? [Invoice].[Location] = ? ORDER BY [Invoice].[ID] ASC";
			$PARAMETRES = array(parent::__get('ID'), parent::__get('Location'));
			self::__select_Entity($SQL_END, $PARAMETERS);
		}
		public function __next(){
			$SQL_END = "WHERE [Invoice].[ID] < ? AND [Invoice].[Location] = ? ORDER BY [Invoice].[ID] DESC";
			$PARAMETRES = array(parent::__get('ID'), parent::__geT('Location'));
			self::__select_Entity($SQL_END, $PARAMETERS);
		}
	}
}?>
