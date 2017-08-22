<?php
class mlbevent extends ServiceAPI{
		
	function beforeFilter(){
		$this->contentHelper = $this->useHelper('contentHelper');
		$this->botHelper = $this->useHelper('botHelper');
	
		global $LOCALE,$CONFIG;
		$this->assign('basedomain', $CONFIG['BASE_DOMAIN']);
		$this->assign('assets_domain', $CONFIG['ASSETS_DOMAIN_WEB']);
		$this->assign('locale', $LOCALE[1]);		
		$this->assign('pages', strip_tags($this->_g('page')));		
			
	}
	
	function savedata(){
		GLOBAL $CONFIG;
		$data['result'] = false;
		$result =  $this->botHelper->get20logingift();
		if($result){ 
			$data['result'] = $result;
		}
		print json_encode($data);exit;		
	}
	
}
?>