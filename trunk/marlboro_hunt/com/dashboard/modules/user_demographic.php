<?php
class user_demographic extends App{
	
	
	function beforeFilter(){
	
		$this->uDemoHelper = $this->useHelper("uDemoHelper");
		
		global $LOCALE,$CONFIG;
		$this->assign('basedomain', $CONFIG['DASHBOARD_DOMAIN']);
		$this->assign('assets_domain', $CONFIG['ASSETS_DOMAIN_DASHBOARD']);
				
		$this->assign('locale', $LOCALE[1]);
		$this->assign('startdate', $this->_g('startdate'));
		$this->assign('enddate', $this->_g('enddate'));
	
	}
	
	function main(){

		$participantByGender = $this->uDemoHelper->participantByGender();
		$paticipantByAge = $this->uDemoHelper->paticipantByAge();
		$participantByCity = $this->uDemoHelper->participantByCity();
		// pr($participantByCity);
		$this->assign("participantByGender",$participantByGender);
		$this->assign("paticipantByAge",$paticipantByAge);
		$this->assign("participantByCity",$participantByCity);
		
		if(strip_tags($this->_g('page'))=='home') $this->log('surf','home');
		return $this->View->toString(TEMPLATE_DOMAIN_DASHBOARD .'apps/user_demographic.html');
		
	}
		
}
?>