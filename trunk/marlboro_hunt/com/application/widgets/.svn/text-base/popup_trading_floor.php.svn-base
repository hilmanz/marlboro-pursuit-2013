
<?php
class popup_trading_floor{
	
	function __construct($apps=null){
		$this->apps = $apps;	
		global $LOCALE,$CONFIG;
		$this->apps->assign('basedomain', $CONFIG['BASE_DOMAIN']);
		$this->apps->assign('assets_domain', $CONFIG['ASSETS_DOMAIN_WEB']);
		$this->apps->assign('locale',$LOCALE[$this->apps->lid]);
	}

	function main(){
		
		$listTradeCode = $this->apps->contentHelper->listTradeCode();
		
		$user = $this->apps->user;
		if ($listTradeCode){
			$this->apps->View->assign('listTradeCode', $listTradeCode);
			$this->apps->View->assign('user', $user);
		}else{
			$this->apps->View->assign('listTradeCode', false);
		}
		
		// pr($listTradeCode);
		
		return $this->apps->View->toString(TEMPLATE_DOMAIN_WEB ."widgets/popup-trading-floor.html"); 
		
	}
}
?>