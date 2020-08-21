<?php Class DIV extends Element {
	//Variables
	///Elements
	public $Elements = array();
	//Functions
	public function DIV(){?><DIV <?php parent::Attributes();?>><?php for($i=0;$i=count($this->Elements);$i++){
		if(is_object($this->Elements[$i])){
			$this->Elements[$i]->get_class($this->Elements[$i])();
		} else {echo $this->Element[$i];}
	}?></DIV><?php }
}?>