<?php
class news_detail{
	
	function __construct($apps=null){
		$this->apps = $apps;	
		global $LOCALE,$CONFIG;
		$this->apps->assign('basedomain', $CONFIG['BASE_DOMAIN']);
		$this->apps->assign('assets_domain', $CONFIG['ASSETS_DOMAIN_WEB']);
		$this->apps->assign('locale',$LOCALE[$this->apps->lid]);
	}

	function main(){
	
		$newsDetail = $this->apps->newsHelper->newsDetail();
		
		// pr($newsDetail);
		if ($newsDetail){
			foreach ($newsDetail as $key => $value){
				list($dateFormat, $timeFormat) = explode(' ',$value['posted_date']);
				list($year, $month, $date) = explode('-',$dateFormat);
				$newsDetail[$key]['datachange'] = $date.'/'.$month.'/'.$year;
			}
		}else{
			$newsDetail = false;
		}
		
		$this->apps->assign("newsDetail",$newsDetail);
		// pr($newsDetail);
		return $this->apps->View->toString(TEMPLATE_DOMAIN_WEB ."widgets/news_detail.html"); 
	
	}
}
?>