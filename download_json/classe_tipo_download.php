<?php

class Download{
	public $item1 = array();
	//public $item2;
	//public $item3;
	//public $item4;
	//public $item5;
	
	/*function __construct($item2,$item3,$item4,$item5){
		$this->item2= $item2;
		$this->item3= $item3;
		$this->item4= $item4;
		$this->item5= $item5;
		
	}
	*/
	function __construct($item1){
		$this->item1[]=$item1;
	}
	/*public function get_item1(){
		return $this->$item1;
	}*/
	public function set_item1($item1){
		$this->item1 = $item1;
	}
	/*function get_item2(){
		return $this->$item2;
	}
	function get_item3(){
		return $this->$item3;
	}
	function get_item4(){
		return $this->$item4;
	}
	function get_item5(){
		return $this->$item5;
	}
	*/

}

?>