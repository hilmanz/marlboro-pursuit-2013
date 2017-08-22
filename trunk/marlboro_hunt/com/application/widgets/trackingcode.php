<?php
class trackingcode{
	
	function __construct($apps=null){
		$this->apps = $apps;	
		global $LOCALE,$CONFIG;
		$this->apps->assign('basedomain', $CONFIG['BASE_DOMAIN']);
		$this->apps->assign('assets_domain', $CONFIG['ASSETS_DOMAIN_WEB']);
		$this->apps->assign('locale',$LOCALE[$this->apps->lid]);
	}

	function main(){
		
		$email_token = strip_tags($this->apps->_g('token'));
		
		$this->apps->assign('token',$email_token);
		return $this->apps->View->toString(TEMPLATE_DOMAIN_WEB ."widgets/tracking-code.html"); 
		
	}
}
?>