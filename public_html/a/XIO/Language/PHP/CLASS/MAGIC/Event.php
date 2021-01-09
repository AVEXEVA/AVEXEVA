<?php
namespace Trait\Magic;
Class Event {
	public function ONCLICK( $array = array() ){		return new HTML\EVENT\ONCLICK( $Array );}
	public function ONDBLCLICK( $array = array() ){		return new HTML\EVENT\DBLCLICK( $Array );}
	public function ONKEYPRESS( $array = array() ){		return new HTML\EVENT\ONKEYPRESS( $Array );}
	public function ONKEYDOWN( $array = array() ){		return new HTML\EVENT\ONKEYDOWN( $Array );}
	public function ONKEYUP( $array = array() ){		return new HTML\EVENT\ONKEYUP( $Array );}
	public function ONMOUSEOVER( $array = array() ){	return new HTML\EVENT\ONMOUSEOVER( $Array );}
	public function ONMOUSEOUT( $array = array() ){		return new HTML\EVENT\ONMOUSEOUT( $Array );}
	public function ONMOUSEENTER( $array = array() ){	return new HTML\EVENT\ONMOUSEENTER( $Array );}
	publiC function ONLOAD( $array = array() ){		return new HTML\EVENT\ONLOAD( $Array );}
}?>