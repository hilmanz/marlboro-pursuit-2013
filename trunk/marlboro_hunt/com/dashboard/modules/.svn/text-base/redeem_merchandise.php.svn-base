<?php
class redeem_merchandise extends App{
	
	
	function beforeFilter(){
		
		$this->redeemHelper = $this->useHelper("redeemHelper");
		
		global $LOCALE,$CONFIG;
		$this->assign('basedomain', $CONFIG['DASHBOARD_DOMAIN']);
		$this->assign('assets_domain', $CONFIG['ASSETS_DOMAIN_DASHBOARD']);
		
		$this->assign('locale', $LOCALE[1]);
		$this->assign('startdate', $this->_g('startdate'));
		$this->assign('enddate', $this->_g('enddate'));
	
		$this->assign('basedomainpath',$CONFIG['BASE_DOMAIN_PATH']);
	}
	function main(){
		
		$numberRedeem = $this->redeemHelper->numberRedeem();
		// pr($numberRedeem);
		$this->assign("numberRedeem",$numberRedeem);
		
		if(strip_tags($this->_g('page'))=='home') $this->log('surf','home');
		return $this->View->toString(TEMPLATE_DOMAIN_DASHBOARD .'apps/redeem_merchandise.html');
		
	}
	
}
?>