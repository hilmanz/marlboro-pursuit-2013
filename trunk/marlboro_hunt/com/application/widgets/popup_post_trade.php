
<?php
class popup_post_trade{
	
	function __construct($apps=null){
		$this->apps = $apps;	
		global $LOCALE,$CONFIG;
		$this->apps->assign('basedomain', $CONFIG['BASE_DOMAIN']);
		$this->apps->assign('assets_domain', $CONFIG['ASSETS_DOMAIN_WEB']);
		$this->apps->assign('locale',$LOCALE[$this->apps->lid]);
	}

	function main(){
		
		$makeTradeCode = $this->apps->contentHelper->makeTradeCode();
		// pr($makeTradeCode);
		if ($makeTradeCode){
			
			foreach ($makeTradeCode['mycode'] as $value){
				$mycode[] = $value;
			}
			
			foreach ($makeTradeCode['needcode'] as $value){
				$needcode[] = $value;
			}
			
			$codeID = $makeTradeCode['codeID'];
			$mycodeID = $makeTradeCode['mycodeID'];
			
			
			$this->apps->View->assign('mycode', $mycode);
			$this->apps->View->assign('needcode', $needcode);
			$this->apps->View->assign('codeID', $codeID);
			$this->apps->View->assign('mycodeID', $mycodeID);
		}
		
		return $this->apps->View->toString(TEMPLATE_DOMAIN_WEB ."widgets/popup-post-trade.html"); 
		
	}
}
?>