<?php
class task extends App{
	
	
	function beforeFilter(){
		
		$this->taskHelper = $this->useHelper("taskHelper");
		
		global $LOCALE,$CONFIG;
		$this->assign('basedomain', $CONFIG['DASHBOARD_DOMAIN']);
		$this->assign('assets_domain', $CONFIG['ASSETS_DOMAIN_DASHBOARD']);
		
		$this->assign('locale', $LOCALE[1]);
		$this->assign('startdate', $this->_g('startdate'));
		$this->assign('enddate', $this->_g('enddate'));
	
		
	}
	function main(){
		
		$eachTaskCompleted = $this->taskHelper->eachTaskCompleted();
		$mostPopularTask = $this->taskHelper->mostPopularTask();
		$bhInvolvedOffline = $this->taskHelper->bhInvolvedOffline();
		$bhTotal = $this->taskHelper->bhTotal();
		
		// $bhInvolvedOffline = array(0=>array('histories'=>'get from event stranger'),
									// 1=>array('histories'=>'get from event marlboros'),
									// 2=>array('histories'=>'get from event minicooper'));
		// pr($bhInvolvedOffline);
		
		$this->Paging = new Paginate();
		$bhtaskComplete = $this->taskHelper->bhtaskComplete();
		if ($bhtaskComplete){
			$start = $this->_g('st');
			$total_per_page = 10;
			$total = $bhtaskComplete['total'];
			$this->assign("paging",$this->Paging->getAdminPaging($start, $total_per_page, $total,""));	
		}
		
		
		
		$this->assign("eachTaskCompleted",$eachTaskCompleted);
		$this->assign("mostPopularTask",$mostPopularTask);
		$this->assign("bhtaskComplete",$bhtaskComplete['rec']);
		$this->assign("bhInvolvedOffline",$bhInvolvedOffline);
		$this->assign("bhInvolvedOfflinereload",$bhInvolvedOffline[0]['histories']);
		$this->assign("bhTotal",$bhTotal);
		// $this->assign("getBHdetail",$getBHdetail);
		
		if(strip_tags($this->_g('page'))=='home') $this->log('surf','home');
		return $this->View->toString(TEMPLATE_DOMAIN_DASHBOARD .'apps/task.html');
		
	}
	
	function getBHdetail()
	{
		$getBHdetail = $this->taskHelper->getBHdetail();
		if($getBHdetail){
			print json_encode(array('status'=>TRUE, 'rec'=>$getBHdetail));
		}else{
			print json_encode(array('status'=>FALSE));
		}
		
		exit;
	}
	
	function  ajax(){
	
	
	
		$qData = $this->taskHelper->getbhInvolvedOffline();
		// pr($qData);
		if($qData){
			print json_encode(array('status'=>TRUE, 'rec'=>$qData));
		}else{
			print json_encode(array('status'=>FALSE));
		}
		
		exit;
	}
	
	
}
?>