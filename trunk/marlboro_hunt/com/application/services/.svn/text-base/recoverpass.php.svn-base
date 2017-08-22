<?php
class recoverpass extends ServiceAPI{
			

	function beforeFilter(){
		
		// $this->inboundHelper = $this->useHelper('inboundHelper');
		$this->userHelper = $this->useHelper('userHelper');
		
		global $LOCALE,$CONFIG;
		$this->assign('basedomain', $CONFIG['BASE_DOMAIN']);
		$this->assign('assets_domain', $CONFIG['ASSETS_DOMAIN_WEB']);

		
	}
	
	
	function runservice(){
		global $CONFIG;
		
		$recoverPass = $this->userHelper->recoverpassword();
		exit;		
	}
}
?>
