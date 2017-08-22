<?php
class dyo_shirt{
	
	function __construct($apps=null){
		$this->apps = $apps;	
		global $LOCALE,$CONFIG;
		$this->apps->assign('basedomain', $CONFIG['BASE_DOMAIN']);
		$this->apps->assign('assets_domain', $CONFIG['ASSETS_DOMAIN_WEB']);
		$this->apps->assign('locale',$LOCALE[$this->apps->lid]);
	}

	function main(){
		global $CONFIG;
		$getGalery = $this->apps->DYOHelper->getGaleryDyo();
		
		$this->apps->assign('week',$this->apps->_g('weeks'));
		$this->apps->assign('most',$this->apps->_g('most'));
		$this->apps->assign('weekevent',$getGalery['weekevent']);
		// pr($getGalery['weekevent']);
		if($getGalery){
			$this->apps->assign('dyogalery', $getGalery['result']);
			$this->apps->assign('dyototal', $getGalery['total']);
		}else{
			$this->apps->assign('dyototal', 0);
		}
		$userSes = $this->apps->session->getSession($CONFIG['SESSION_NAME'],"WEB");
		// pr($getGalery);
		$this->apps->assign('userid', $userSes->id);
		return $this->apps->View->toString(TEMPLATE_DOMAIN_WEB ."widgets/dyo-shirt.html"); 
		
	}
}
?>