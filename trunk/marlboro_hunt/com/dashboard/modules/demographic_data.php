<?php
class demographic_data extends App{
	
	
	function beforeFilter(){
		
		$this->demographyHelper = $this->useHelper("demographyHelper");
		
		global $LOCALE,$CONFIG;
		$this->assign('basedomain', $CONFIG['DASHBOARD_DOMAIN']);
		$this->assign('assets_domain', $CONFIG['ASSETS_DOMAIN_DASHBOARD']);
		
		$this->assign('locale', $LOCALE[1]);
		$this->assign('startdate', $this->_g('startdate'));
		$this->assign('enddate', $this->_g('enddate'));
	
		
	}
	function main(){
		
		// pr($_POST);
		$userBasedonAge = $this->demographyHelper->userBasedonAge();
		$basedOnBrandPref = $this->demographyHelper->basedOnBrandPref();
		$basedOnLocation = $this->demographyHelper->basedOnLocation();
		// pr($basedOnLocation);
		$this->assign("userBasedonAge",$userBasedonAge);
		$this->assign("basedOnBrandPref",$basedOnBrandPref);
		$this->assign("basedOnLocation",$basedOnLocation);
		
		if ($this->_p('toptensortby')){
			$this->assign("sortby",intval($this->_p('toptensortby')));
		}else{
			$this->assign("sortby",1);
		}
		if(strip_tags($this->_g('page'))=='home') $this->log('surf','home');
		return $this->View->toString(TEMPLATE_DOMAIN_DASHBOARD .'apps/demographic_data.html');
		
	}
	
}
?>