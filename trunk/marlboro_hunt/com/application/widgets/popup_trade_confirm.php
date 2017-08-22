
<?php
class popup_trade_confirm{
	
	function __construct($apps=null){
		$this->apps = $apps;	
		global $LOCALE,$CONFIG;
		$this->apps->assign('basedomain', $CONFIG['BASE_DOMAIN']);
		$this->apps->assign('assets_domain', $CONFIG['ASSETS_DOMAIN_WEB']);
		$this->apps->assign('locale',$LOCALE[$this->apps->lid]);
	}

	function main(){
		
		if (isset($_SESSION['getTrade'])){
			$sessID = $_SESSION['getTrade'];
			
			$getDataTrade = $this->apps->contentHelper->getDataTrade($sessID);
			if ($getDataTrade){
				
				$this->apps->View->assign('tradeFlor', $getDataTrade);
			}
			
			unset($_SESSION['getTrade']);
			// pr($getDataTrade);
		}else{
			$sessID = false;
		}
		
		return $this->apps->View->toString(TEMPLATE_DOMAIN_WEB ."widgets/popup-trade-confirm.html"); 
		
	}
}
?>