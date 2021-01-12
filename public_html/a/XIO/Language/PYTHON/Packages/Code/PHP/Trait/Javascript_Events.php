<?php
trait javascript_events {
	public $onClick = Null;
	public $onKeyPress = Null;
	public $onKeyDown = Null;
	public $onKeyUp = Null;
	public $onFocus = Null;
	public function __javascript_events(){
		return array("onClick"=>$this->__get("onClick"),
					"onKeyPress"=>$this->__get("onKeyPress"),
					"onKeyDown"=>$this->__get("onKeyDown"),
					"onKeyUp"=>$this->__get("onKeyUp"),
					"onFocus"=>$this->__get("onFocus"));
	}
}
?>
