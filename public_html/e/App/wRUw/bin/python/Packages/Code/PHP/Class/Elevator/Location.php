<?php
/*******************Requires******************/
//Classes
if(!class_exists('MS_SQL_Object')){require('cgi-bin/php/classes/MS_SQL_Object.php');}
/*******************object******************/
if(!class_exists('Location')){
	class Location extends MS_SQL_Object {
		/*******************Traits*********************/

		/*******************Variables******************/
		protected $table = 'Location';
		protected $primary_key = 'ID';
		protected $columns = array('ID', 'Name', 'Customer', 'Description', 'Route', 'Division', 'Street', 'City', 'State', 'Zip_Code', 'Latitude', 'Longitude', 'Salesforce_ID', 'Total_Service_ID');
		protected $header = array('Name', 'City');
		protected $plural = 'Locations';
		//Columns
		protected $ID						= 	Null;
		protected $Name 				=		NULL;
		protected $Customer 		=		NULL;
		protected $Route 				=		NULL;
		protected $Division			=		NULL;
		protected $Street		 		= 	Null;
		protected $City					= 	Null;
		protected $State				= 	Null;
		protected $Zip_Code			= 	Null;
		protected $Latitude			=		Null;
		protected $Longitude		=	 	Null;
		protected $Floors 			=		NULL;
		//External Services
		protected $Salesforce_ID = NULL;
		protected $Total_Service_ID = NULL;
		//Arrays
		protected $Comments 		=		NULL;
		//Customer
		protected $Customer_ID 	=		NULL;
		protected $Customer_Name =	NULL;
		//Route
		protected $Route_ID 			=	NULL;
		protected $Route_Name 		=	NULL;
		//Division
		protected $Division_ID 		=	NULL;
		protected $Division_Name 	= NULL;
		//User
		protected $User_ID 				=	NULL;
		protected $User_First_Name = NULL;
		protected $User_Last_Name = NULL;
		//Count of Records
		protected $Unit_count 			= NULL;
		protected $Job_count 				= NULL;
		protected $Ticket_count 		= NULL;
		//History
		protected $Ticket_last 			=	NULL;
		//Magic Methods
		public function __construct($data = Null){
			parent::__construct($data);
			parent::__capture();
			parent::__connect();
		}
		public function __icon(){Font_Awesome_Icons::getInstance()->__Location();}
		public function __menu($options = array()){parent::__menu($options == array() ? array('Duplicate', 'Delete', 'Download', 'Upload') : $options);}
		public function __updater(){
			$this->__update_foreign_entity('Location', 'Customer_ID', 'Customer');
			$this->__update_foreign_entity('Location', 'Division_ID', 'Division');
			$this->__update_foreign_entity('Location', 'Route_ID', 'Route');
			$this->__update_column('Location', 'Street');
			$this->__update_column('Location', 'City');
			$this->__update_column('Location', 'State');
			$this->__update_column('Location', 'Zip_Code');
			$this->__update_column('Location', 'Latitude');
			$this->__update_column('Location', 'Longitude');
		}
		public function __select_Total_Service(){
			parent::__connect_Total_Service();
			if(!is_numeric(parent::__get('Total_Service_ID')) || parent::__get('Total_Service_ID') < 1){return FALSE;}
			$total_service_database = parent::__get('total_service_database');
			$r = sqlsrv_query(parent::__get('total_service'),
				"	SELECT 	*
					FROM 		[{$total_service_database}].dbo.Loc
					WHERE 	Loc.Loc = ?
				;", array(parent::__get('Total_Service_ID')));
			if(!$r){return FALSE;}
			$row = sqlsrv_fetch_array($r);
			if(!is_array($row) || $row == array() || count($row) == 0){return FALSE;}
			$row['Street'] = $row['Address'];
			$row['Name'] = $row['Tag'];
			$row['Customer'] = $row['Owner'];
			unset($row['ID']);
			parent::__set($row);
			$r = sqlsrv_query(parent::__get('connection'),
				"	SELECT 	ID
					FROM 		Customer
					WHERE 	Customer.Total_Service_ID = ?
				;", array(parent::__get('Customer')));
			if(!$r){return FALSE;}
			$row = sqlsrv_fetch_array($r);
			if(!is_array($row) || $row == array() || count($row) == 0){return FALSE;}
			parent::__set('Customer', $row['ID']);
			$r = sqlsrv_query(parent::__get('connection'),
				"	SELECT 	*
					FROM 		Location
					WHERE 	Location.Total_Service_ID = ?
				", array(parent::__get('Total_Service_ID')));
			if($r){
				$row = sqlsrv_fetch_array($r);
				parent::__set('ID', $row['ID']);
				parent::__set('Salesforce_ID', $row['Salesforce_ID']);
			}
			return TRUE;
		}
		public function __upload_Salesforce(){
			$r = sqlsrv_query(parent::__get('connection'),
				"	SELECT 	Salesforce_ID
					FROM 		Customer
					WHERE 	Customer.ID = ?
				;", array(parent::__get('Customer')));
			if(!$r){return FALSE;}
			$row = sqlsrv_fetch_array($r);
			if(!is_array($row) || count($row) == 0){return FALSE;}
			$json = json_encode(array('Name' => parent::__get('Name'), 'ParentId' => $row['Salesforce_ID'], 'RecordTypeId' => "0121U000000KBI3QAO"));
			$url = '/services/data/v37.0/sobjects/Account/' . self::__get('Salesforce_ID');
			return parent::salesforce_update($url, $json);
		}
		public function __insert_Salesforce_Account(){
			$r = sqlsrv_query(parent::__get('connection'),
				"	SELECT 	Salesforce_ID
					FROM 		Customer
					WHERE 	Customer.ID = ?
				;", array(parent::__get('Customer')));
			if(!$r){return FALSE;}
			$row = sqlsrv_fetch_array($r);
			if(!is_array($row) || count($row) == 0){return FALSE;}
			$json = json_encode(array('Name' => parent::__get('Name'), 'ParentId' => $row['Salesforce_ID'], 'RecordTypeId' => "0121U000000KBI3QAO"));
			$url = '/services/data/v37.0/sobjects/Account/';
			return parent::salesforce_insert($url, $json);
		}
		public function __sync_Total_Service(){
			if(self::__select_Total_Service()){
				parent::__update();
				if(!is_string(parent::__get('Salesforce_ID')) || !self::__upload_Salesforce()){
					$response = self::__insert_Salesforce_Account();
					if($response != FALSE){
						$response = get_object_vars(json_decode($response));
						var_dump($response);
						parent::__set('Salesforce_ID', $response['id']);
						parent::__insert();
					}
				}
				return TRUE;
			}
			return FALSE;
		}
		public function __Salesforce_Account_Root($ParentId){
			while($ParentId != NULL){
				$ParentIdx = $ParentId;
				$ParentId = get_object_vars(json_decode(parent::salesforce_exec('/services/data/v39.0/sobjects/Account/' . $ParentId . '?fields=ParentId')))['ParentId'];
			}
			return $ParentIdx;
		}
		public function __sync_Salesforce(){
			$Fields = get_object_vars(json_decode(parent::salesforce_exec('/services/data/v39.0/sobjects/Account/' . self::__get('Salesforce_ID') . '?fields=Name,ParentId,BillingStreet,BillingCity,BillingState,BillingPostalCode,BillingLatitude,BillingLongitude,RecordTypeId')));
			$RecordType = get_object_vars(json_decode(parent::salesforce_exec('/services/data/v39.0/sobjects/RecordType/' . $Fields['RecordTypeId'] . '?fields=Name')));
			unset(
				$Fields['attributes'],
				$RecordType['attributes']
			);
			$Fields['Street'] 								= $Fields['BillingStreet'];
	    $Fields['City'] 									= $Fields['BillingCity'];
	    $Fields['State'] 									= $Fields['BillingState'];
	    $Fields['Zip_Code'] 							= $Fields['BillingPostalCode'];
			$Fields['Latitude'] 							= $Fields['BillingLatitude'];
			$Fields['Longitude'] 							= $Fields['BillingLongitude'];
			$Fields['Salesforce_Record_Type'] = $RecordType['Name'];
			unset(
				$Fields['BillingStreet'],
				$Fields['BillingCity'],
				$Fields['BillingState'],
				$Fields['BillingPostalCode'],
				$Fields['BillingLatitude'],
				$Fields['BillingLongitude']
			);
			parent::__set($Fields);
			$ParentId = self::__Salesforce_Account_Root($Fields['ParentId']);
			if(!is_null($ParentId) && in_array($Fields['Salesforce_Record_Type'], array('Property'))){self::__sync_Salesforce_extends($ParentId);}
		}
		public function __sync_Salesforce_extends($ParentId){
			if(!class_exists('Location')){require('cgi-bin/php/classes/Location.php');}
			$r = sqlsrv_query(parent::__get('connection'),
				"	SELECT 	Top 1
									ID
					FROM 		Customer
					WHERE 	Customer.Salesforce_ID = ?
				;", array($ParentId));
			if(!$r){return false;}
			$row = sqlsrv_fetch_array($r);
			if(!is_array($row)){return false;}
			$Location = new Location(array(
				'Salesforce_ID' => parent::__get('Salesforce_ID'),
				'Customer'	=>	$row['ID'],
				'Name'			=>	parent::__get('Name'),
				'Street'		=>	parent::__get('Street'),
				'City'			=>	parent::__get('City'),
				'State'			=>	parent::__get('State'),
				'Zip_Code'	=>	parent::__get('Zip_Code'),
				'Latitude'	=>	parent::__get('Latitude'),
				'Longitude'	=>	parent::__get('Longitude')
			));
			if($Location->__select_Salesforce()){
				$Location->__set(array(
					'Customer'	=>	$row['ID'],
					'Name'			=>	parent::__get('Name'),
					'Street'		=>	parent::__get('Street'),
					'City'			=>	parent::__get('City'),
					'State'			=>	parent::__get('State'),
					'Zip_Code'	=>	parent::__get('Zip_Code'),
					'Latitude'	=>	parent::__get('Latitude'),
					'Longitude'	=>	parent::__get('Longitude')
				));
				$Location->__update();
			} else { $Location->__insert(); }
			$Location->__select();
			if(!$Location->__upload_Total_Service_Loc()){$Location->__insert_Total_Service_Loc();}
			return true;
		}
		public function __insert_Total_Service_Loc(){
			parent::__connect_Total_Service();
			$total_service_database = parent::__get('total_service_database');
			$r = sqlsrv_query(parent::__get('total_service'),
				"	SELECT 	Max(ID) AS ID
					FROM 	 	Rol
				;", array());
			if(!$r){return false;}
			$ID = sqlsrv_fetch_array($r)['ID'];
			$ID++;
			$r = sqlsrv_query(parent::__get('total_service'),
				"	SELECT 	Max(Loc) AS ID
					FROM 	 	Loc
				;", array());
			if(!$r){return false;}
			$ID2 = sqlsrv_fetch_array($r)['ID'];
			$ID2++;
			sqlsrv_query(parent::__get('total_service'),
				"	INSERT INTO [{$total_service_database}].dbo.Rol(ID, Name, Address, City, State, Zip, Type, Geolock, fLong, Latt, Contact, Phone, Fax, Since, Last, EN, Cellular, Country, Website, EMail)
					VALUES(?, ?, ?, ?, ?, ?, 4, 0, 0, 0, ' ', '(', '(', ?, ?, ?, '(', 'United States', ' ', ' ')
				;", array($ID, parent::__get('Name'), is_null(parent::__get('Street')) ? ' ' : parent::__get('Street'), is_null(parent::__get('City')) ? ' ' : parent::__get('City'), parent::__return_State_Code(parent::__get('State')), is_null(parent::__get('Zip_Code')) ? ' ' : parent::__get('Zip_Code'), date("Y-m-d H:i:s"), date("Y-m-d H:i:s"), 1));
			$r = sqlsrv_query(parent::__get('connection'),
				"	SELECT 	Total_Service_ID
					FROM   	Customer
					WHERE 	Customer.ID = ?
				;", array(parent::__get('Customer')));
			if(!$r){return FALSE;}
			$Customer_ID = sqlsrv_fetch_array($r)['Total_Service_ID'];
			sqlsrv_query(parent::__get('total_service'),
				"	INSERT INTO Loc(Loc, Rol, Owner, Tag, ID, Address, City, State, Zip, Geolock, STax, Maint, InUse, Elevs, Status, PriceL, PaidNumb, PaidDays, WriteOff, CareOf, Job, Type, Country, DispAlertType, PrintInvoice)
					VALUES(?, ?, ?, ?, ?, ?, ?, ?, ?, 0, '60602E', 0, 0, 0, 0, 1, 0, 0, 0.00, ' ', 0, 'Non-Contract', 'United States', 0, 0)
				;", array($ID2, $ID, $Customer_ID, parent::__get('Name'), substr(preg_replace("/[^a-zA-Z]/", "", parent::__get('Name')), 0, 15), is_null(parent::__get('Street')) ? ' ' : parent::__get('Street'), is_null(parent::__get('City')) ? ' ' : parent::__get('City'), is_null(parent::__get('State')) ? ' ' : parent::__get('State'), is_null(parent::__get('Zip_Code')) ? ' ' : parent::__get('Zip_Code')));
			self::__select();
			parent::__set(array('Total_Service_ID' => $ID2));
			parent::__update();
			return TRUE;
		}
		public function __upload_Total_Service_Loc(){
			parent::__connect_Total_Service();
			if(!is_numeric(parent::__get('Total_Service_ID')) || parent::__get('Total_Service_ID') < 1){return FALSE;}
			$total_service_database = parent::__get('total_service_database');
			sqlsrv_query(parent::__get('total_service'),
				"	UPDATE 	[{$total_service_database}].dbo.Loc
					SET 		Loc.ID = ?,
									Loc.Tag = ?,
									Loc.Address = ?,
									Loc.City = ?,
									Loc.State = ?,
									Loc.Zip = ?
					WHERE 	Loc.Loc = ?
				;", array(substr(preg_replace("/[^a-zA-Z]/", "", parent::__get('Name')), 0, 15), parent::__get('Name'), parent::__get('Street'), parent::__get('City'), parent::__return_State_Code(parent::__get('State')), parent::__get('Zip_Code'), parent::__get('Total_Service_ID')));
			return TRUE;
		}
		private function __select_Location($SQL_END, $PARAMETERS){
			if(!is_numeric(parent::__get('ID')) && !is_numeric(parent::__get('Customer')) && is_null(parent::__get('Salesforce_ID'))){return FALSE;}
			$r = sqlsrv_query(parent::__get('connection'),
				"	SELECT 	[Location].*,
									Customer.ID 				AS Customer_ID,
									Customer.Name 			AS Customer_Name,
									Route.ID 						AS Route_ID,
									Route.Name 					AS Route_Name,
									[User].ID 					AS User_ID,
									[User].First_Name 	AS User_First_Name,
									[User].Last_Name 		AS User_Last_Name,
									[Division].ID 			AS Division_ID,
									[Division].Name 		AS Division_Name
					FROM 		[Location]
									LEFT JOIN Customer 		ON 	[Location].Customer = 	Customer.ID
									LEFT JOIN Route 			ON 	[Location].Route 		= 	Route.ID
									LEFT JOIN Division 		ON	[Location].Division = 	[Division].ID
									LEFT JOIN [User]			ON	[Route].[User]			=		[User].ID
					{$SQL_END}
				;", $PARAMETERS);
			if(!$r){return FALSE;}
			$row = sqlsrv_fetch_array($r);
			if(!is_array($row)){return FALSE;}
			parent::__set($row);
			return TRUE;
		}
		public function __select(){
			$SQL_END = "WHERE [Location].ID = ?";
			$PARAMETERS = array(parent::__get('ID'));
			return self::__select_Location($SQL_END, $PARAMETERS);
		}
		public function __select_Salesforce(){
			$SQL_END = "WHERE [Location].[Salesforce_ID] = ?";
			$PARAMETERS = array(parent::__get('Salesforce_ID'));
			return self::__select_Location($SQL_END, $PARAMETERS);
		}
		public function __select_Customer(){
			$SQL_END = "WHERE [Location].[Customer] = ?";
			$PARAMETERS = array(parent::__get('Customer'));
			return self::__select_Location($SQL_END, $PARAMETERS);
		}
		public function __previous(){
			$SQL_END = 'WHERE [Location].Customer = ? AND [Location].ID < ? ORDER BY Location.ID DESC';
			$PARAMETERS = array(parent::__get('Customer'), parent::__get('ID'));
			return self::__select_Location($SQL_END, $PARAMETERS);
		}
		public function __next(){
			$SQL_END = 'WHERE [Location].Customer = ? AND [Location].ID > ? ORDER BY Location.ID DESC';
			$PARAMETERS = array(parent::__get('Customer'), parent::__get('ID'));
			return self::__select_Location($SQL_END, $PARAMETERS);
		}
		public function __select_card(){parent::__card(FALSE);}
		public function __edit_card(){parent::__card(TRUE);}
		public function __insert_card(){parent::__card(TRUE);}

		public function __list_group_item_counts($i = FALSE){
			if($i == FALSE){
				parent::__list_group_item_count('Job', 'Jobs');
				parent::__list_group_item_count('Ticket', 'Tickets');
				parent::__list_group_item_count('Unit', 'Units');
			}
		}
		public function __list_group_item_history($i = FALSE){
			if($i == FALSE){
				parent::__list_group_item_last('Ticket');
			}
		}
		public function __list_group_items($i){
			parent::__list_group_item('Street', $i);
			parent::__list_group_item('City', $i);
			parent::__list_group_item('State', $i);
			parent::__list_group_item('Zip_Code', $i);
			parent::__list_group_item('Latitude', $i);
			parent::__list_group_item('Longitude', $i);
			//parent::__list_group_item('Floors', $i);
		}
		public function __list_group_item_entities($i){
			self::__list_group_item_Customer($i);
			self::__list_group_item_Division($i);
			self::__list_group_item_Route($i);
		}
		public function __list_group_item_Customer($i = FALSE){parent::__list_group_item_entity('Customer', 'Customers', $i);}
		public function __list_group_item_Division($i = FALSE){parent::__list_group_item_entity('Division', 'Divisions', $i);}
		public function __list_group_item_Route($i = FALSE){parent::__list_group_item_entity('Route', 'Routes', $i);}
		public function __select_Job_count(){
			$r = sqlsrv_query(parent::__get('connection'),
			"	SELECT 	Count(Job.ID) AS Count
			FROM		Job
			WHERE 	Job.Location = ?
			;", array(parent::__get('ID')));
			if(!$r){return FALSE;}
			parent::__set('Job_count',sqlsrv_fetch_array($r)['Count']);
			return TRUE;
		}
		public function __select_Ticket_count(){
			$r = sqlsrv_query(parent::__get('connection'),
				"	SELECT 	Count(Ticket.ID) AS Count
					FROM		Ticket
					WHERE 	Ticket.Location = ?
				;", array(parent::__get('ID')));
			if(!$r){return FALSE;}
			parent::__set('Ticket_count',sqlsrv_fetch_array($r)['Count']);
			return TRUE;
		}
		public function __select_Unit_count(){
			$r = sqlsrv_query(parent::__get('connection'),
				"	SELECT 	Count(Unit.ID) AS Count
					FROM		Unit
					WHERE 	Unit.Location = ?
				;", array(parent::__get('ID')));
			if(!$r){return FALSE;}
			parent::__set('Unit_count',sqlsrv_fetch_array($r)['Count']);
			return TRUE;
		}
		public function __select_Ticket_last(){
			$r = sqlsrv_query(parent::__get('connection'),
				"	SELECT 		Top 1
										Ticket.*
					FROM 			Ticket
					WHERE 		Ticket.[Location] = ?
										AND Ticket.[Completed] IS NOT NULL
										AND Ticket.[Status] <> 'Void'
					ORDER BY	Ticket.[Completed] DESC
				;", array(parent::__get('ID')));
			if(!$r){return FALSE;}
			if(!class_exists('Ticket')){require('cgi-bin/php/classes/Ticket.php');}
			parent::__set('Ticket_last', new Ticket(sqlsrv_fetch_array($r)));
			return TRUE;
		}

	}
}?>
