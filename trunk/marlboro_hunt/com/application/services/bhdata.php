<?php
class bhdata extends ServiceAPI{
			

	function beforeFilter(){
		
		// $this->inboundHelper = $this->useHelper('inboundHelper');
		$this->botHelper = $this->useHelper('botHelper');
		
		global $LOCALE,$CONFIG;
		$this->assign('basedomain', $CONFIG['BASE_DOMAIN']);
		$this->assign('assets_domain', $CONFIG['ASSETS_DOMAIN_WEB']);

		
	}
	
	
	function runservice(){
		global $CONFIG;
		// pr($_GET);
		 // pr("masuk");exit;
			$updateUser = $this->botHelper->bhreportdata();
			 
		 
		print json_encode(array('status'=>$updateUser));
		
		exit;		
	}
}
?>
