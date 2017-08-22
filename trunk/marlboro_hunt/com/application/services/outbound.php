<?php
class outbound extends ServiceAPI{
			

	function beforeFilter(){
		
		$this->outboundHelper = $this->useHelper('outboundHelper');
		
		global $LOCALE,$CONFIG;
		$this->assign('basedomain', $CONFIG['BASE_DOMAIN']);
		$this->assign('assets_domain', $CONFIG['ASSETS_DOMAIN_WEB']);

		
	}
	
	
	function runservice(){
		GLOBAL $CONFIG;
		$data = $this->outboundHelper->generate();
		print json_encode($data);exit;		
	}
	
	function runservicelocal(){
		GLOBAL $CONFIG;
		$data = $this->outboundHelper->readInsertXMLData();
		print json_encode($data);exit;		
	}
}
?>
