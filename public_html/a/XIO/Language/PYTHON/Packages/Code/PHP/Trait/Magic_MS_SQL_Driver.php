<?php
if(!trait_exists('ticket_actions')){
  trait magic_ms_sql_driver {
    private function __select(){
			if(!is_numeric(parent::__get(parent::__get('primary_key')))){return FALSE;}
      $t = $this->__get('table');
      $p = $this->__get('primary_key');
      $v = $this->__get($p);
			$r = sqlsrv_query(parent::__get('connection'),
				"	SELECT 	[{$t}].*,
					FROM 		[{$t}]
					WHERE [{$t}].[{$p}] = ?
				;", array($v));
			if(!$r){return FALSE;}
			$row = sqlsrv_fetch_array($r);
			if(!is_array($row)){return FALSE;}
			$this->__set($row);
			return TRUE;
		}
    public function __update(){}
    public function __delete(){
			$t = $this->__get('table');
			$p = $this->__get('primary_key');
      $v = $this->__get($p);
			sqlsrv_query(self::__get('connection'),
				"	DELETE FROM 	[{$table}]
					WHERE 				[{$table}].[{$primary_key}] = ?
				;", array(self::__get($primary_key)));
			return TRUE;
		}
    public function __insert(){
			$table = self::__get('table');
			$primary_key = self::__get('primary_key');
			$columns = array_diff(self::__get('columns'), array($primary_key));
			$SQL_INSERT = array();
			if(is_array($columns) && count($columns) > 0){foreach($columns as $column){$SQL_INSERT[] = "[{$column}]";}}
			$SQL_VALUES = implode(',', array_fill(0, count($SQL_INSERT), '?'));
			$SQL_INSERT = implode(',', $SQL_INSERT);
			$PARAMETERS = self::__get($columns);
			$SQL_STRING = "INSERT INTO [{$table}]({$SQL_INSERT}) VALUES({$SQL_VALUES});";
			sqlsrv_query(self::__get('connection'), $SQL_STRING, $PARAMETERS);
			$r = sqlsrv_query(self::__get('connection'),
				"	SELECT 		Top 1
										Max([{$table}].{$primary_key}) AS {$primary_key}
					FROM 			[{$table}]
				;");
			if(!$r){return -1;}
			self::__set(self::__get('primary_key'), sqlsrv_fetch_array($r)[$primary_key]);
			$_GET['ID'] = self::__get('ID');
			return TRUE;
		}
    public function __create_table(){
      
    }
    public function __alter_table(){}
    public function __duplicate(){return self::__insert();}
    public function __update(){
			$table = self::__get('table');
			$columns = self::__get('columns');
			$primary_key = self::__get('primary_key');
			$columns = array_diff(self::__get('columns'), array($primary_key));
			$SQL_SET = array();
			if(is_array($columns) && count($columns) > 0){foreach($columns as $column){$SQL_SET[] = "[{$table}].[{$column}] = ?";}}
			$SQL_SET = implode(',', $SQL_SET);
			$SQL_WHERE = "[{$table}].[{$primary_key}] = ?";
			$PARAMETERS = self::__get($columns);
			$PARAMETERS[] = self::__get($primary_key);
			$SQL_STRING = "	UPDATE 	[{$table}]
				SET 		{$SQL_SET}
				WHERE 	{$SQL_WHERE}
			;";
			sqlsrv_query(self::__get('connection'), $SQL_STRING, $PARAMETERS);
			return TRUE;
		}
    protected function __update_column($e, $c){
			if(isset($_GET[$c])){
				sqlsrv_query(self::__get('connection'),
					"	UPDATE 	{$e}
						SET 		{$e}.{$c} = ?
						WHERE 	{$e}.[ID]	=	?
					;", array($_GET[$c], $this->__get('ID')));
			}
		}
  }
}
?>
