<?php 
namespace Traits\Magic;
Class SQL {
	public function ADD( $array = array() ){		return new SQL\ADD( $Array );}
	public function ALIAS( $array = array() ){		return new SQL\ALIAS( $Array ); }
	public function ALTER( $array = array() ){		return new SQL\ALTER( $Array );}
	public function CREATE( $array = array() ){ 		return new SQL\CREATE( $Array );}
	public function COLUMN( $array = array() ){		return new SQL\COLUMN( $Array );}
	public function COLUMNS( $array = array() ){		return new SQL\COLUMNS( $Array );}
	public function CONDITION( $array = array() ){		return new SQL\CONDITION( $Array );}
	public function CONDITIONS( $array = array() ){		return new SQL\CONDITIONS( $Array );}
	public function CONJUNCTION( $array = array() ){	return new SQL\CONJUNCTION( $Array );}
	public function DATABASE( $array = array() ){		return new SQL\DATABASE( $Array );}
	public function DELETE( $array = array() ){		return new SQL\DELETE( $Array );} 
	public function DBO( $array = array() ){ 		return new SQL\DBO( $Array );}
	public function FROM( $array = array() ){ 		return new SQL\FROM( $Array );}
	public function GRANT( $array = array() ){		return new SQL\GRANT( $Array );}
	public function INNER( $array = array() ){		return new SQL\INNER( $Array );}
	public function JOIN( $array = array() ){		return new SQL\JOIN( $Array );}
	public function LEFT( $array = array() ){		return new SQL\LEFT( $Array );}
	public function OUTER( $array = array() ){		return new SQL\OUTER( $Array );}
	public function SELECT( $array = array() ){		return new SQL\SELECT( $Array );}
	public function RELATIONSHIP( $array = array() ){	return new SQL\RELATIONSHIP( $Array );}
	public function TABLE( $array = array() ){		return new SQL\TABLE( $Array );}
	public function UPDATE( $array = array() ){		return new SQL\UPDATE( $Array );}
	public function WHERE( $array = array() ){		return new SQL\WHERE( $Array );}
}?>