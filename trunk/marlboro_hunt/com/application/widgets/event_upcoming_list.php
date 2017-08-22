<?php
class event_upcoming_list{
	
	function __construct($apps=null){
		$this->apps = $apps;	
		global $LOCALE,$CONFIG;
		$this->apps->assign('basedomain', $CONFIG['BASE_DOMAIN']);
		$this->apps->assign('assets_domain', $CONFIG['ASSETS_DOMAIN_WEB']);
		$this->apps->assign('locale',$LOCALE[$this->apps->lid]);
	}

	function main(){
	
		$upcomingEvent = $this->apps->newsHelper->upcomingEvent(true,true);
		// pr($upcomingEvent);
		$this->apps->assign("upcomingEvent",$upcomingEvent);
			/* 	$no = 1;
			foreach ($pastEvent as $key => $value){
				$pastEvent[$key]['no'] = $no;
				$no++;
			}
	
		pr($pastEvent);
		$this->apps->assign('pastEvent',$pastEvent); */
		
		return $this->apps->View->toString(TEMPLATE_DOMAIN_WEB ."widgets/event_upcoming_list.html");
	
	}
	

}


?>