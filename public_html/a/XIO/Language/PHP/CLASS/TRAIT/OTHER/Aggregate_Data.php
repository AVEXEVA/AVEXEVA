<?php
if(!trait_exists('ticket_actions')){
  trait Aggregate_Data {
  	protected $counts = array();
  	public function count($entity, $data){
  		parent::__push('counts', count($data), $entity);
  		return parent::__get('counts')[$entity];
  	}
  }
}
?>
