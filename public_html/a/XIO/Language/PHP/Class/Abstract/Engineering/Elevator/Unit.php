<?php
/*******************Requires******************/
//Classes
if(!class_exists('MS_SQL_Object')){require('cgi-bin/php/classes/MS_SQL_Object.php');}
//Traits
//if(!trait_exists('select_entity_count')){require('cgi-bin/php/traits/select_entity_count.php');}
/*******************Class******************/
if(!class_exists('Unit')){
	class Unit extends MS_SQL_Object {
		/*******************Traits*********************/
		/*NONE*/
		/*******************Variables******************/
		protected $table = 'Unit';
		protected $primary_key = 'ID';
		protected $columns = array('ID', 'Location', 'Name', 'Description', 'Type', 'Building_ID', 'City_ID', 'Salesforce_ID', 'Total_Service_ID');
		protected $header = array('Location_Name', 'City_ID');
		protected $plural = 'Units';
		//Columns
		protected $ID = NULL;
		protected $Location = NULL;
		protected $Name = NULL;
		protected $Description = NULL;
		protected $Type = NULL;
		protected $Building_ID = NULL;
		protected $City_ID = NULL;
		//External Services
		protected $Salesforce_ID = NULL;
		protected $Total_Service_ID = NULL;
		//Extra Columns
		//Location
		protected $Location_ID = NULL;
		protected $Location_Name = NULL;
		//Customer
		protected $Customer_ID = NULL;
		protected $Customer_Name = NULL;
		//Counts
		protected $Ticket_count = NULL;
		protected $Job_count = NULL;
		/*******************Functions******************/
		public function __construct($data = Null){
			parent::__construct($data);
			parent::__capture();
			parent::__connect();
		}
		public function __updater(){
			$this->__update_foreign_entity('Unit', 'Location_ID', 'Location');
		}
		public function __sync_Total_Service(){
			if(self::__select_Total_Service()){
				parent::__update();
				if(!is_string(parent::__get('Salesforce_ID')) || !self::__upload_Salesforce()){
					$response = self::__insert_Salesforce_Unit();
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
		public function __insert_Salesforce_Unit(){
			$r = sqlsrv_query(parent::__get('connection'),
				"	SELECT 	Salesforce_ID
					FROM 		Location
					WHERE 	Location.ID = ?
				;", array(parent::__get('Location')));
			if(!$r){return FALSE;}
			$row = sqlsrv_fetch_array($r);
			if(!is_array($row) || count($row) == 0){return FALSE;}
			$json = json_encode(array('Name' => parent::__get('Building_ID'), 'AccountId' => $row['Salesforce_ID'], 'RecordTypeId' => '0121U000000Jz3vQAC'));
			$url = '/services/data/v37.0/sobjects/Asset/';
			return parent::salesforce_insert($url, $json);
		}
		public function __upload_Salesforce(){
			$r = sqlsrv_query(parent::__get('connection'),
				"	SELECT 	Salesforce_ID
					FROM 		Location
					WHERE 	Location.ID = ?
				;", array(parent::__get('Location')));
			if(!$r){return FALSE;}
			$row = sqlsrv_fetch_array($r);
			if(!is_array($row) || count($row) == 0){return FALSE;}
			$json = json_encode(array('Name' => parent::__get('Building_ID'), 'AccountId' => $row['Salesforce_ID']));
			$url = '/services/data/v37.0/sobjects/Asset/' . self::__get('Salesforce_ID');
			return parent::salesforce_update($url, $json);
		}
		public function __sync_Salesforce(){
			$Fields = get_object_vars(json_decode(parent::salesforce_exec('/services/data/v39.0/sobjects/Asset/' . self::__get('Salesforce_ID') . '?fields=Name,RecordTypeId,AccountId')));
			$RecordType = get_object_vars(json_decode(parent::salesforce_exec('/services/data/v39.0/sobjects/RecordType/' . $Fields['RecordTypeId'] . '?fields=Name')));
			unset(
				$Fields['attributes'],
				$RecordType['attributes']
			);
			$Fields['Building_ID'] = $Fields['Name'];
			$Fields['Location'] = $Fields['AccountId'];
			$Fields['Type'] = $RecordType['Name'];
			parent::__set($Fields);
			$r = sqlsrv_query(parent::__get('connection'),
				"	SELECT 	Top 1
									ID
					FROM 		Location
					WHERE 	Location.Salesforce_ID = ?
				;", array(parent::__get('Location')));
			if(!$r){return false;}
			$row = sqlsrv_fetch_array($r);
			if(!is_array($row)){return false;}
			parent::__set('Location', $row['ID']);
			if(!self::__select_Salesforce()){self::__insert();}
			else{
				parent::__set('Building_ID', $Fields['Name']);
				parent::__update();
			}
			self::__select();
			if(!self::__upload_Total_Service_Elev()){return self::__insert_Total_Service_Elev();}
			return true;
		}
		public function __insert_Total_Service_Elev(){
			parent::__connect_Total_Service();
			$total_service_database = parent::__get('total_service_database');
			$r = sqlsrv_query(parent::__get('total_service'),
				"	SELECT 	Max(ID) AS ID
					FROM 	 	Elev
				;", array());
			if(!$r){return false;}
			$ID = sqlsrv_fetch_array($r)['ID'];
			$ID++;
			$r = sqlsrv_query(parent::__get('connection'),
				"	SELECT 	Total_Service_ID
					FROM 		Location
					WHERE 	Location.ID = ?
				;", array(parent::__get('Location')));
			if(!$r){return FALSE;}
			$row = sqlsrv_fetch_array($r);
			if(!is_array($row) || count($row) == 0){return FALSE;}
			$Location_Total_Service_ID = $row['Total_Service_ID'];
			$r = sqlsrv_query(parent::__get('total_service'),
				"	SELECT 	Owner
					FROM 		Loc
					WHERE 	Loc.Loc = ?
				;", array($Location_Total_Service_ID));
			if(!$r){return FALSE;}
			$row = sqlsrv_fetch_array($r);
			if(!is_array($row) || count($row) == 0){return FALSE;}
			$Customer_Total_Service_ID = $row['Owner'];
			sqlsrv_query(parent::__get('total_service'),
				"	INSERT INTO Elev(ID, Unit, State, Loc, Owner, Cat, Type, Building, Manuf, Remarks, InstallBy, Since, Last, Price, fDesc, [Serial], Template, Status, Week)
					VALUES(?, ?, ?, ?, ?, 'N/A', ?, 'Other', ' ', ' ', ' ', ?, ?, ?, ' ', ' ', 1, 0, ' ')
				;", array($ID, is_null(parent::__get('Building_ID')) ? ' ' : parent::__get('Building_ID'), is_null(parent::__get('City_ID')) ? ' ' : parent::__get('City_ID'), $Location_Total_Service_ID, $Customer_Total_Service_ID, parent::__get('Type'), date('Y-m-d 00:00:00.000'), date('Y-m-d 00:00:00.000'), .00));
			$r = sqlsrv_query(parent::__get('total_service'),
				"	SELECT 	Max(ID) AS ID
					FROM 		ElevTItem
				;", array());
			if(!$r){return FALSE;}
			$row = sqlsrv_fetch_array($r);
			if(!is_array($row) || count($row) == 0){return FALSE;}
			$ID2 = $row['ID'] + 1;
			sqlsrv_query(parent::__get('total_service'),
				"	INSERT INTO ElevTItem(ID, ElevT, Elev, CustomID, fDesc, [Line], [Value], Format, fExists)
					VALUES(?, 1, ?, 1, 'Capacity', 1, ' ', NULL, NULL)
				;", array($ID2, $ID));
			self::__select();
			parent::__set(array('Total_Service_ID' => $ID));
			parent::__update();
			return TRUE;
		}
		public function __upload_Total_Service_Elev(){
			parent::__connect_Total_Service();
			if(!is_numeric(parent::__get('Total_Service_ID')) || parent::__get('Total_Service_ID') < 1){return FALSE;}
			$total_service_database = parent::__get('total_service_database');
			sqlsrv_query(parent::__get('total_service'),
				"	UPDATE 	[{$total_service_database}].dbo.Elev
					SET 		Elev.Unit = ?,
									Elev.State = ?
					WHERE 	Elev.ID = ?
				;", array(parent::__get('Building_ID'), parent::__get('City_ID'), parent::__get('Total_Service_ID')));
			return TRUE;
		}
		public function __select_Total_Service(){
			parent::__connect_Total_Service();
			if(!is_numeric(parent::__get('Total_Service_ID')) || parent::__get('Total_Service_ID') < 1){return FALSE;}
			$total_service_database = parent::__get('total_service_database');
			$r = sqlsrv_query(parent::__get('total_service'),
				"	SELECT 	*
					FROM 		[{$total_service_database}].dbo.Elev
					WHERE 	Elev.ID = ?
				;", array(parent::__get('Total_Service_ID')));
			if(!$r){return FALSE;}
			$row = sqlsrv_fetch_array($r);
			if(!is_array($row) || $row == array() || count($row) == 0){return FALSE;}
			$row['City_ID'] = $row['State'];
			$row['Building_ID'] = $row['Unit'];
			$row['Location'] = $row['Loc'];
			unset($row['ID']);
			parent::__set($row);
			$r = sqlsrv_query(parent::__get('connection'),
				"	SELECT 	ID
					FROM 		Location
					WHERE 	Location.Total_Service_ID = ?
				;", array(parent::__get('Location')));
			if(!$r){return FALSE;}
			$row = sqlsrv_fetch_array($r);
			if(!is_array($row) || count($row) == 0){return FALSE;}
			parent::__set('Location', $row['ID']);
			$r = sqlsrv_query(parent::__get('connection'),
				"	SELECT 	*
					FROM 		Unit
					WHERE 	Unit.Total_Service_ID = ?
				", array(parent::__get('Total_Service_ID')));
			if($r){
				$row = sqlsrv_fetch_array($r);
				parent::__set('ID', $row['ID']);
				parent::__set('Salesforce_ID', $row['Salesforce_ID']);
			}
			return TRUE;
		}
		public function __Salesforce_Unit_Root($ParentId){
			while($ParentId != NULL){
				$ParentIdx = $ParentId;
				$ParentId = get_object_vars(json_decode(parent::salesforce_exec('/services/data/v39.0/sobjects/Account/' . $ParentId . '?fields=ParentId')))['ParentId'];
			}
			return $ParentIdx;
		}
		private function __select_Unit($SQL_END = '', $PARAMETERS = array()){
			$SQL_STRING = "	SELECT 	Top 1
								[Unit].*,
								[Location].ID AS Location_ID,
								[Location].Name AS Location_Name,
								[Customer].ID AS Customer_ID,
								[Customer].Name AS Customer_Name
				FROM 		[Unit]
								LEFT JOIN [Location] ON [Unit].[Location] = [Location].ID
								LEFT JOIN [Customer] ON [Location].[Customer] = [Customer].ID
				{$SQL_END}
			;";
			$r = sqlsrv_query(parent::__get('connection'), $SQL_STRING, $PARAMETERS);
			if(!$r){return FALSE;}
			$row = sqlsrv_fetch_array($r);
			if(!is_array($row)){return FALSE;}
			parent::__set($row);
			return TRUE;
		}
		public function __select(){
			$SQL_END = 'WHERE [Unit].[ID] = ?';
			$PARAMETERS = array(parent::__get('ID'));
			return self::__select_Unit($SQL_END, $PARAMETERS);
		}
		public function __previous(){
			$SQL_END = 'WHERE [Unit].Location = ? AND [Unit].ID < ? ORDER BY Unit.ID DESC';
			$PARAMETERS = array(parent::__get('Location_ID'), parent::__get('ID'));
			return self::__select_Unit($SQL_END, $PARAMETERS);
		}
		public function __next(){
			$SQL_END = 'WHERE [Unit].Location = ? AND [Unit].ID > ? ORDER BY Unit.ID DESC';
			$PARAMETERS = array(parent::__get('Location_ID'), parent::__get('ID'));
			return self::__select_Unit($SQL_END, $PARAMETERS);
		}
		public function __list_group_item_counts(){
			self::__list_group_item_count('Job', 'Jobs');
			self::__list_group_item_count('Ticket', 'Tickets');
		}
		public function __select_Job_count(){
			$r = sqlsrv_query(parent::__get('connection'),
        "	SELECT 	Count(Job.ID) AS Count
          FROM		Job
                  LEFT JOIN Job_has_Unit ON Job.ID = Job_has_Unit.Job
          WHERE 	[Job_has_Unit].[Unit] = ?
        ;", array(parent::__get('ID')));
      if(!$r){return FALSE;}
      parent::__set('Job_count',sqlsrv_fetch_array($r)['Count']);
      return TRUE;
		}
		public function __select_Ticket_Count($SQL_END = '', $PARAMETERS = array()){
      $r = sqlsrv_query(parent::__get('connection'),
        "	SELECT 	Count(Ticket.ID) AS Count
          FROM		Ticket
          WHERE 	Ticket.Unit = ?
        ;", array(parent::__get('ID')));
      if(!$r){return FALSE;}
      parent::__set('Ticket_count',sqlsrv_fetch_array($r)['Count']);
      return TRUE;
    }
		public function __select_Salesforce(){
			$SQL_END = "WHERE [Unit].[Salesforce_ID] = ?";
			$PARAMETERS = array(parent::__get('Salesforce_ID'));
			return self::__select_Unit($SQL_END, $PARAMETERS);
		}
		public function __menu($options = array()){parent::__menu($options == array() ? array('Duplicate', 'Delete') : $options);}
		public function __icon(){Font_Awesome_Icons::getInstance()->__Unit();}
		public function __insert_card(){$this->__card(TRUE);}
		public function __select_card(){$this->__card(FALSE);}
		public function __edit_card(){$this->__card(TRUE);}
		public function __list_group_items($i){
			parent::__list_group_item('City_ID', 			$i);
			parent::__list_group_item('Building_ID', 	$i);
		}
		public function __list_group_item_entities($i){
			self::__list_group_item_Customer($i);
			self::__list_group_item_Location($i);
		}
		public function __list_group_item_Customer($i = FALSE){parent::__list_group_item_entity('Customer', 'Customers', $i);}
		public function __list_group_item_Location($i = FALSE){parent::__list_group_item_entity('Location', 'Locations', $i);}
		public function __display_Customer(){parent::__display_entity('Customer');}
		public function __display_Location(){parent::__display_entity('Location');}
	}
}?>
