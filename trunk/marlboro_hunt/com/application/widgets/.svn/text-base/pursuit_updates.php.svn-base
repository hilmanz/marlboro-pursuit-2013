<?php
class pursuit_updates{
	
	function __construct($apps=null){
		$this->apps = $apps;	
		global $LOCALE,$CONFIG;
		$this->apps->assign('basedomain', $CONFIG['BASE_DOMAIN']);
		$this->apps->assign('assets_domain', $CONFIG['ASSETS_DOMAIN_WEB']);
		$this->apps->assign('locale',$LOCALE[$this->apps->lid]);
	}

	function main(){
		
		$getUpdate = $this->apps->contentHelper->getPursuitUpdate();
		// pr($getUpdate);
		// exit;
		if ($getUpdate){
			
			$this->apps->View->assign('update', $getUpdate);
			
		}
		return $this->apps->View->toString(TEMPLATE_DOMAIN_WEB ."widgets/pursuit-updates.html"); 
	
		
		
	}
}
?>