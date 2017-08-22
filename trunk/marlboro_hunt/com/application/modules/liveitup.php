<?php
class liveitup extends App{
	function beforeFilter(){
	
		$this->contentHelper = $this->useHelper('contentHelper');
		$this->activityHelper = $this->useHelper('activityHelper');
		$this->userHelper = $this->useHelper('userHelper');
		$this->newsHelper = $this->useHelper('newsHelper');
		global $LOCALE,$CONFIG;
		$this->assign('basedomain', $CONFIG['BASE_DOMAIN']);
		$this->assign('assets_domain', $CONFIG['ASSETS_DOMAIN_WEB']);
		$this->assign('locale', $LOCALE[1]);
	}
	
	function main()
	{
		
		$getPrize = $this->contentHelper->getPrize();
		// pr($getPrize);
		// exit;
		$this->View->assign('getPrize',$getPrize['category']);
		
		return $this->View->toString(TEMPLATE_DOMAIN_WEB .'apps/liveitup.html');
	}
	
	function bid()
	{
		return $this->View->toString(TEMPLATE_DOMAIN_WEB .'widgets/liveitup-bid.html');
	}
	function buy()
	{
		return $this->View->toString(TEMPLATE_DOMAIN_WEB .'widgets/liveitup-buy.html');
	}
	function reserve()
	{
		return $this->View->toString(TEMPLATE_DOMAIN_WEB .'widgets/liveitup-reserve.html');
	}
	
	
}
?>