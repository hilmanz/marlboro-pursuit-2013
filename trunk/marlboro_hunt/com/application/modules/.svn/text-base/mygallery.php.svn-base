<?php
class mygallery extends App{	
	
	function beforeFilter(){
		$this->contentHelper = $this->useHelper('contentHelper');
		$this->userHelper  = $this->useHelper('userHelper');
		$this->wallpaperHelper = $this->useHelper('wallpaperHelper');
		$this->uploadHelper = $this->useHelper('uploadHelper');
		$this->activityHelper = $this->useHelper('activityHelper');
		global $LOCALE,$CONFIG;
		$this->assign('basedomain', $CONFIG['BASE_DOMAIN']);
		$this->assign('assets_domain', $CONFIG['ASSETS_DOMAIN_WEB']);
		$this->assign('pages', strip_tags($this->_g('page')));
		$this->assign('tokenize',gettokenize(5000*60,$this->user->id));
	}
	
	function main(){
		
		return $this->View->toString(TEMPLATE_DOMAIN_WEB .'/apps/my-gallery.html');
	}
	
	
}
?>