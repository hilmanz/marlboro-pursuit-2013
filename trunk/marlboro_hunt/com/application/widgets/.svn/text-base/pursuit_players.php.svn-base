<?php
class pursuit_players{
	
	function __construct($apps=null){
		$this->apps = $apps;	
		global $LOCALE,$CONFIG;
		$this->apps->assign('basedomain', $CONFIG['BASE_DOMAIN']);
		$this->apps->assign('assets_domain', $CONFIG['ASSETS_DOMAIN_WEB']);
		$this->apps->assign('locale',$LOCALE[$this->apps->lid]);
	}

	function main(){
		
		$getPlayer = $this->apps->contentHelper->getPursuitPlayer();
		if($getPlayer){
			// pr($getPlayer);
			$index = 0;
			foreach ($getPlayer as $value){
				$data[$index]['id'] = $value['id'];
				$data[$index]['name'] = $value['name'];
				$index++;
			}
			// pr($data);
			$this->apps->View->assign('player', $data);
		}
		return $this->apps->View->toString(TEMPLATE_DOMAIN_WEB ."widgets/pursuit-players.html"); 
	
	}
}
?>