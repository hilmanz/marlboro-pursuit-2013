<?php
class news_list_devel{
	
	function __construct($apps=null){
		$this->apps = $apps;	
		global $LOCALE,$CONFIG;
		$this->apps->assign('basedomain', $CONFIG['BASE_DOMAIN']);
		$this->apps->assign('assets_domain', $CONFIG['ASSETS_DOMAIN_WEB']);
		$this->apps->assign('locale',$LOCALE[$this->apps->lid]);
	}

	function main(){
	
		$newsList = $this->apps->newsHelper->newsDetail(true,true);
		// pr($newsList);
		// $newsList = false;
		if ($newsList){
			
			
			foreach ($newsList as $key => $value){
				
				list($dateFormat, $timeFormat) = explode(' ',$value['posted_date']);
				list($year, $month, $date) = explode('-',$dateFormat);
				$newsList[$key]['datachange'] = $date.'/'.$month.'/'.$year;
				
				
			}
			
			$limit = 3;
			$index = 0;
			$loop = 1;
			foreach ($newsList as $key => $value){
				
				if ($loop<=$limit){
					
					$dataNews[$index][] = $value;
					
					if ($loop==$limit){
						$loop = 1;
						$index++;
					}else{
						$loop++;
					}
				}
				
				
			}
			
			// pr($dataNews);
			
		}else{
			$newsList = false;
		}
		
		
		$this->apps->assign("newsList",$dataNews);
		return $this->apps->View->toString(TEMPLATE_DOMAIN_WEB ."test/news_list_devel.html"); 
	
	}
}
?>