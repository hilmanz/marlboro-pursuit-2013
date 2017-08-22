<?php
class dyo extends App{
	function beforeFilter(){
	
		$this->DYOHelper = $this->useHelper('DYOHelper');
		$this->activityHelper = $this->useHelper('activityHelper');
		$this->userHelper = $this->useHelper('userHelper');
		global $LOCALE,$CONFIG;
		$this->assign('basedomain', $CONFIG['BASE_DOMAIN']);
		$this->assign('assets_domain', $CONFIG['ASSETS_DOMAIN_WEB']);
		
		$this->assign('locale', $LOCALE[1]);
		
	}
	
	function main()
	{
	
		
		$this->View->assign('dyo_shirt',$this->setWidgets('dyo_shirt'));
		// $this->View->assign('countdown',$this->setWidgets('countdown'));
		
		
		$this->View->assign('popup_game',$this->setWidgets('popup_game'));
		return $this->View->toString(TEMPLATE_DOMAIN_WEB .'/apps/dyo-pages.html');
	}
	

	function ajaxgallery()
	{
		$data = false;
		$needs = $this->_request('needs');
		// pr($needs);
		if($needs=='favorite'){
			$data = $this->DYOHelper->addFavorite();
			$this->log('surf', 'Vote Faforite');
		}
		if($needs=='pagegallery'){
			$data = $this->DYOHelper->getGaleryDyo();
			$this->log('surf', 'DYO Galery');
		}

		
		
		
		
		if ($data){
			print json_encode(array('status'=>true,'result'=>$data));
		}else{
			print json_encode(array('status'=>false,'result'=>$data));
		}
		
		
		exit;
	}
	
	function ajaxDYO()
	{
		
		$checkDyo = $this->DYOHelper->checkBeforeCreateDyo();
		// var_dump($checkDyo);
		
		if ($checkDyo){
			$this->log('surf', 'Failed create DYO');
			print json_encode(array('status'=>false));
		}else{
			$this->log('surf', 'Create DYO');
			print json_encode(array('status'=>true));
		}
		
		exit;
	}
	function games(){
		
		return $this->View->toString(TEMPLATE_DOMAIN_WEB .'/apps/dyo-games.html');
	
	}
}
?>