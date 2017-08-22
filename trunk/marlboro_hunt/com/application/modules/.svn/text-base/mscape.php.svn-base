<?php
class mscape extends App{
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
			$this->log('surf','mscape_main');
		// $this->View->assign('tasklist',$this->setWidgets('task_list'));
		$this->View->assign('popup_game',$this->setWidgets('popup_game'));
		return $this->View->toString(TEMPLATE_DOMAIN_WEB .'apps/mscape-pages.html');
	}
	
	function photo()
	{
			$this->log('surf','mscape_photo');
		$getPhoto = $this->newsHelper->mscape();
		$this->View->assign("getPhoto",$getPhoto);
		// pr($getPhoto);
		return $this->View->toString(TEMPLATE_DOMAIN_WEB .'widgets/mscape-photos.html');
	}
	
	
	function video()
	{
		$this->log('surf','mscape_video');
		$getVideo = $this->newsHelper->mscape();
		$this->View->assign("getVideo",$getVideo);
		return $this->View->toString(TEMPLATE_DOMAIN_WEB .'widgets/mscape-videos.html');
	}
	
	function video2()
	{	
		$this->log('surf','mscape_video2');
		$getVideo = $this->newsHelper->mscape();
		$this->View->assign("getVideo",$getVideo);
		return $this->View->toString(TEMPLATE_DOMAIN_WEB .'widgets/mscape-videos2.html');
	}
	
	function itinerary()
	{
		$this->log('surf','mscape_itinerary');
		$getData = $this->newsHelper->mscape();
		$this->View->assign("getData",$getData);
		// pr($getData);
		$this->View->assign('popup_game',$this->setWidgets('popup_game'));
		return $this->View->toString(TEMPLATE_DOMAIN_WEB .'widgets/mscape-itinerary.html');
	}
	
	
}
?>