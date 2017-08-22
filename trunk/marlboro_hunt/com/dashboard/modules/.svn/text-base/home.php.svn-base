<?php
class home extends App{
	
	
	function beforeFilter(){
	
		$this->uCategoryHelper = $this->useHelper("uCategoryHelper");
		
		global $LOCALE,$CONFIG;
		$this->assign('basedomain', $CONFIG['DASHBOARD_DOMAIN']);
		$this->assign('assets_domain', $CONFIG['ASSETS_DOMAIN_DASHBOARD']);
				
		$this->assign('locale', $LOCALE[1]);
		$this->assign('startdate', $this->_g('startdate'));
		$this->assign('enddate', $this->_g('enddate'));
	
	}
	
	function main(){

		$alluser = $this->uCategoryHelper->allUserRegistration();
		$activeUser = $this->uCategoryHelper->activeUser();
		$userUnverified = $this->uCategoryHelper->userUnverified();
				
		$this->assign("alluser",$alluser);
		$this->assign("activeUser",$activeUser);
		$this->assign("userUnverified",$userUnverified);
		
		/* $this->assign('startdate',$startdate);
		$this->assign('enddate',$enddate); */
		
		
		
		if(strip_tags($this->_g('page'))=='home') $this->log('surf','home');
		return $this->View->toString(TEMPLATE_DOMAIN_DASHBOARD .'apps/home.html');
		
	}
		
}
?>