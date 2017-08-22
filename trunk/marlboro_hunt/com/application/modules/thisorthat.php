<?php
class thisorthat extends App{
	
	
	function beforeFilter(){

		global $LOCALE,$CONFIG;
		// $this->contentHelper = $this->useHelper('contentHelper');
		$this->uploadHelper = $this->useHelper('uploadHelper');
		$this->eventHelper = $this->useHelper('eventHelper');
		$this->assign('basedomain', $CONFIG['BASE_DOMAIN']);
		$this->assign('assets_domain', $CONFIG['ASSETS_DOMAIN_WEB']);
		
	}
	
	function main(){
		global $CONFIG;
	   $this->log('surf','this or that page');
	   
		$eventdata =  $this->eventHelper->checkcurrentevent(4);
		// pr($eventdata);
		if($eventdata){
			$conditional = unserialize($eventdata['tags']);
			
			$this->View->assign('conditional',$conditional);
			$this->View->assign('eventdata',$eventdata);
		}
		
		
	   
	   $post = $this->_p('token');
	  
	   if($post == 2){
		
			$file = $_FILES['image'];
			$path = $CONFIG['LOCAL_PUBLIC_ASSET']."thisorthat/";
			
			$upFoto = $this->uploadHelper->uploadThisImage($file, $path);
			if ($upFoto['result']){
				// pr($upFoto);exit;
				$dataImage = $upFoto['arrImage'];
				$insert = $this->eventHelper->postData($dataImage); 
				
				$this->View->assign('status_upload', 1);
			}else{
				$this->View->assign('status_upload', 2);
			}
	   } 
	   
	   if($post == 3){
	  
			$file = $_FILES['image'];
			$path = $CONFIG['LOCAL_PUBLIC_ASSET']."thisorthat/";
			
			// $upFoto = $this->uploadHelper->uploadThisImage($file, $path);
			$upVid = $this->uploadHelper->uploadThisVideo($file, $path);
			
			if ($upVid['result']){
				$dataVid = $upVid['arrVideo'];
				// pr($dataVid);exit;
				$insert = $this->eventHelper->postVideo($dataVid); 
				
				$this->View->assign('status_upload', 1);
			}else{
				$this->View->assign('status_upload', 2);
			}
	   } 
	   
		return $this->View->toString(TEMPLATE_DOMAIN_WEB .'/apps/this-or-that.html');
	}
	
	function winner(){
		
		$getround = $this->_g('start');
		if ($getround==null) $getround = 1;
		// var_dump($getround);
		$getWinner = $this->eventHelper->thisorthatwinner();
		if($getWinner){
			
			
			for ($i=1; $i<=$getWinner['total']; $i++){
				$round[] = $i;
			}
			// pr($round);
			// pr($getround);
			$this->View->assign('thewinner', $getWinner['res']);
			$this->View->assign('totalwinner', $round);
			$this->View->assign('activeround', $getround);
			if ($getround<=count($round)){
				$this->View->assign('nextround', $getround+1);
			}
			if ($getround>1){
				$this->View->assign('prevround', $getround-1);
			}
		}
		
		return $this->View->toString(TEMPLATE_DOMAIN_WEB .'/apps/this-or-that-winner.html');
	}
	
}
?>