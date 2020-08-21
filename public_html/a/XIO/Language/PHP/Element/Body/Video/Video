<?php Class Video extends Element {
	//Attributes
	public $Width = '100%';
	public $Height = '100%';
	public $Controls = False;
	public $Autoplay = False;
	//Elements
	public $Sources = array();
	//Functions
	public function Video(){?><Video <?php parent::Attributes();?>><?php
		for($i=0;$i<count($this->Sources);$i++){$this->Sources[$i]->Source();}
	?></Video><?php
}?>