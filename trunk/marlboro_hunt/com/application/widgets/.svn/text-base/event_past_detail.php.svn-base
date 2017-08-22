<?php
class event_past_detail{
	
	function __construct($apps=null){
		$this->apps = $apps;	
		global $LOCALE,$CONFIG;
		$this->apps->assign('basedomain', $CONFIG['BASE_DOMAIN']);
		$this->apps->assign('assets_domain', $CONFIG['ASSETS_DOMAIN_WEB']);
		$this->apps->assign('locale',$LOCALE[$this->apps->lid]);
	}

	function main(){
		
		$detailevent = $this->apps->newsHelper->pastEvent();
		
		// pr($detailevent);
		$detailevent[0]['total'] = count($detailevent[0]['gallery']);
		
		
		if ($detailevent[0]['total'] > 5){
			$start = 0;
			
			foreach ($detailevent[0]['gallery'] as $key => $value){
				
				$detailevent[0]['gallery_sort'][] = $value;
			}
			
			$start = 0;
			$end = 4;
			
			for($i = $start; $i<=$end; $i++){
				if ($detailevent[0]['gallery_sort'][$i] !=""){
					$detailevent[0]['gallery_paging'][] = $detailevent[0]['gallery_sort'][$i];
				}
				
			}
			
			// foreach ($detailevent[0]['gallery_sort'] as $key => $value){
				
				// if ($start <= $end){
					// $detailevent[0]['gallery_paging'][$key] = $value;
					// $start++;
				// }
				
			// }
			
		}else{
			foreach ($detailevent[0]['gallery'] as $key => $value){
				
				$detailevent[0]['gallery_paging'][] = $value;
			}
		}
		
		pr($detailevent);
		$this->apps->assign("detailevent",$detailevent);
		
		return $this->apps->View->toString(TEMPLATE_DOMAIN_WEB ."widgets/event_past_detail.html");
	
	}
	

}


?>