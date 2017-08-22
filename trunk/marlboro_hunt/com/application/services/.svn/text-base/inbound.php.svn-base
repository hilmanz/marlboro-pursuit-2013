<?php
class inbound extends ServiceAPI{
			

	function beforeFilter(){
		
		$this->inboundHelper = $this->useHelper('inboundHelper');
		
		global $LOCALE,$CONFIG;
		$this->assign('basedomain', $CONFIG['BASE_DOMAIN']);
		$this->assign('assets_domain', $CONFIG['ASSETS_DOMAIN_WEB']);

		
	}
	
	
	function runservice(){
		GLOBAL $CONFIG;
		$data = $this->inboundHelper->runservice();
		print json_encode($data);exit;		
	}
}
?>
