<?php
class event_past_list{
	
	function __construct($apps=null){
		$this->apps = $apps;	
		global $LOCALE,$CONFIG;
		$this->apps->assign('basedomain', $CONFIG['BASE_DOMAIN']);
		$this->apps->assign('assets_domain', $CONFIG['ASSETS_DOMAIN_WEB']);
		$this->apps->assign('locale',$LOCALE[$this->apps->lid]);
	}

	function main(){
	
		if ($_GET['id']){
			$pastEvent = $this->apps->newsHelper->pastEvent(false,true);
		}else{
			$pastEvent = $this->apps->newsHelper->pastEvent(true,true);
		}
		
		
		// $pastEvent = false;
		$this->apps->assign("pastEvent",$pastEvent);
			/* 	$no = 1;
			foreach ($pastEvent as $key => $value){
				$pastEvent[$key]['no'] = $no;
				$no++;
			}
	
		
		$this->apps->assign('pastEvent',$pastEvent); */
		// pr($pastEvent);
		$this->apps->assign('idactive',$_GET['id']);
		return $this->apps->View->toString(TEMPLATE_DOMAIN_WEB ."widgets/event_past_list.html");
	
	}
	
}


?>