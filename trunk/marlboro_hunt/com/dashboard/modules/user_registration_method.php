<?php
class user_registration_method extends App{
	
	
	function beforeFilter(){
	
		$this->uRegMethodHelper = $this->useHelper("uRegMethodHelper");
		
		global $LOCALE,$CONFIG;
		$this->assign('basedomain', $CONFIG['DASHBOARD_DOMAIN']);
		$this->assign('assets_domain', $CONFIG['ASSETS_DOMAIN_DASHBOARD']);
				
		$this->assign('locale', $LOCALE[1]);
		$this->assign('startdate', $this->_g('startdate'));
		$this->assign('enddate', $this->_g('enddate'));
	
	}
	
	function main(){

		$onlineReg = $this->uRegMethodHelper->onlineReg();	
		$offlineReg = $this->uRegMethodHelper->offlineReg();	
		$verifiedReg = $this->uRegMethodHelper->verifiedReg();	
		$unverifiedReg = $this->uRegMethodHelper->unverifiedReg();	
		// pr($unverifiedReg);
		$this->assign("onlineReg",$onlineReg);
		$this->assign("offlineReg",$offlineReg);
		$this->assign("verifiedReg",$verifiedReg);
		$this->assign("unverifiedReg",$unverifiedReg);
		
		/* $this->assign('startdate',$startdate);
		$this->assign('enddate',$enddate); */
		
		if(strip_tags($this->_g('page'))=='home') $this->log('surf','home');
		return $this->View->toString(TEMPLATE_DOMAIN_DASHBOARD .'apps/user_registration_method.html');
		
	}
		
}
?>