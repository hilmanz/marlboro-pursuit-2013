<?php
class track extends App{
		
	
	function main(){
		print json_encode($this->isUserOnline());
		exit;
	}
	
}
?>