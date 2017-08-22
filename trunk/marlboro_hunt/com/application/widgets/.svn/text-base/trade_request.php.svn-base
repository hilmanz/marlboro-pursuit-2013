<?php
class trade_request{
	
	function __construct($apps=null){
		$this->apps = $apps;	
		global $LOCALE,$CONFIG;
		$this->apps->assign('basedomain', $CONFIG['BASE_DOMAIN']);
		$this->apps->assign('assets_domain', $CONFIG['ASSETS_DOMAIN_WEB']);
		$this->apps->assign('locale',$LOCALE[$this->apps->lid]);
	}

	function main(){
		
		$myTradeReq = $this->apps->contentHelper->getMyTradeReq();
		if ($myTradeReq){
			// pr($myTradeReq);
			$this->apps->View->assign('myTradeReq', $myTradeReq);
		}
		
		return $this->apps->View->toString(TEMPLATE_DOMAIN_WEB ."widgets/trade-request.html"); 
	
		
		
	}
}
?>