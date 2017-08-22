<?php
class user_activities extends App{
	
	
	function beforeFilter(){
	
		$this->uActivitiesHelper = $this->useHelper("uActivitiesHelper");
		
		global $LOCALE,$CONFIG;
		$this->assign('basedomain', $CONFIG['DASHBOARD_DOMAIN']);
		$this->assign('assets_domain', $CONFIG['ASSETS_DOMAIN_DASHBOARD']);
				
		$this->assign('locale', $LOCALE[1]);
		$this->assign('startdate', $this->_g('startdate'));
		$this->assign('enddate', $this->_g('enddate'));
	
	}
	
	function main(){
	
		$loginhistories = $this->uActivitiesHelper->loginhistories();
		$numberofLogin = $this->uActivitiesHelper->numberofLogin();
		$loginHistory = $this->uActivitiesHelper->loginHistory();		
		$numberofRegistrant = $this->uActivitiesHelper->numberofRegistrant();
		$numberofExistingUser = $this->uActivitiesHelper->numberofExistingUser();
		$numberofNewUser = $this->uActivitiesHelper->numberofNewUser();
		$maleFemaleUser = $this->uActivitiesHelper->maleFemaleUser();
		$this->Paging = new Paginate();
		$mostLetterCollect = $this->uActivitiesHelper->mostLetterCollect();
		if ($mostLetterCollect){
			$start = $this->_g('st');
			$total_per_page = 20;
			$total = $mostLetterCollect['total'];
			$this->assign("paging",$this->Paging->getAdminPaging($start, $total_per_page, $total,""));	
		}
		
		$timeSpent = $this->uActivitiesHelper->timeSpent();
		$timeVisit = $this->uActivitiesHelper->timeVisit();
		$loginInventory = $this->uActivitiesHelper->loginInventory();
		$regionLogin = $this->uActivitiesHelper->regionLogin();
		$top10page = $this->uActivitiesHelper->top10page();
		$top10pageMovefwd = $this->uActivitiesHelper->top10pageMovefwd();
		$timedayoflogin = $this->uActivitiesHelper->timedayoflogin();
		
		$averageuserpointweekly = $this->uActivitiesHelper->averageuserpointweekly();
		// pr($averageuserpointweekly);exit;
		$dyouserweekly = $this->uActivitiesHelper->dyouserweekly();
		
		// pr($loginhistories['dates']);
		// pr($timedayoflogin);
		$this->assign("loginhistories",$loginhistories);
		$this->assign("numberofLogin",$numberofLogin);
		$this->assign("loginHistory",$loginHistory);		
		$this->assign("numberofRegistrant",$numberofRegistrant);
		$this->assign("numberofExistingUser",$numberofExistingUser);
		$this->assign("numberofNewUser",$numberofNewUser);
		$this->assign("maleFemaleUser",$maleFemaleUser);
		
		$this->assign("mostLetterCollect",$mostLetterCollect['rec']);
		$this->assign("total",round(count($mostLetterCollect['rec'])/2));
		
		if ($this->_p('typeoftime')){
			$this->assign("sortby",intval($this->_p('typeoftime')));
		}else{
			$this->assign("sortby",1);
		}
		$this->assign("timeVisit",$timeVisit);
		$this->assign("timeSpent",$timeSpent);
		$this->assign("loginInventory",$loginInventory);
		$this->assign("regionLogin",$regionLogin);
		$this->assign("top10page",$top10page);
		$this->assign("top10pageMovefwd",$top10pageMovefwd);
		$this->assign("timedayoflogin",$timedayoflogin);
		
		$this->assign("averageuserpointweekly",$averageuserpointweekly);
		$this->assign("dyouserweekly",$dyouserweekly);
		
		if(strip_tags($this->_g('page'))=='home') $this->log('surf','home');
		return $this->View->toString(TEMPLATE_DOMAIN_DASHBOARD .'apps/user_activities.html');
		
	}
	
	function ajax()
	{
		$loginHistory = $this->uActivitiesHelper->loginHistory();
		if ($loginHistory)print json_encode(array('data'=>$loginHistory));
		else print json_encode(array('data'=>false));
		exit;
	}
	
	function downloadreport(){
			$loginhistories = $this->uActivitiesHelper->loginhistories();
			$this->assign("loginhistories",$loginhistories);
			$filename = "user_login_report_".date('Ymd_gia').".xls";
			header("Content-Type:   application/vnd.ms-excel; charset=utf-8");
			header("Content-Disposition: attachment; filename=$filename");  //File name extension was wrong
			header("Expires: 0");
			header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
			header("Cache-Control: private",false);
			// echo "Some Text"; //no ending ; here
	
			print $this->View->toString(TEMPLATE_DOMAIN_DASHBOARD .'apps/user_activities_report.html');
			exit;
	}
	
	function downloadreportdyo(){
			$dyouserweekly = $this->uActivitiesHelper->dyouserweekly();
			$this->assign("dyouserweekly",$dyouserweekly);
			$filename = "user_activities_dyo_report_".date('Ymd_gia').".xls";
			header("Content-Type:   application/vnd.ms-excel; charset=utf-8");
			header("Content-Disposition: attachment; filename=$filename");  //File name extension was wrong
			header("Expires: 0");
			header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
			header("Cache-Control: private",false);
			// echo "Some Text"; //no ending ; here
	
			print $this->View->toString(TEMPLATE_DOMAIN_DASHBOARD .'apps/user_activities_dyo_report.html');
			exit;
	}
	function downloadreportnumlogin(){
			$reporttimelogin = $this->uActivitiesHelper->reporttimelogin();
			$this->assign("reporttimelogin",$reporttimelogin);
			$filename = "numberof_login_report_".date('Ymd_gia').".xls";
			header("Content-Type:   application/vnd.ms-excel; charset=utf-8");
			header("Content-Disposition: attachment; filename=$filename");  //File name extension was wrong
			header("Expires: 0");
			header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
			header("Cache-Control: private",false);
			// echo "Some Text"; //no ending ; here
	
			print $this->View->toString(TEMPLATE_DOMAIN_DASHBOARD .'apps/user_activities_login_report.html');
			exit;
	}
	
	function downloadreportaverageuserpoint(){
			$averageuserpointweekly = $this->uActivitiesHelper->downloadaverageuserpointweekly();
			$this->assign("averageuserpointweekly",$averageuserpointweekly);
			$filename = "weekly_average_user_point_report".date('Ymd_gia').".xls";
			header("Content-Type:   application/vnd.ms-excel; charset=utf-8");
			header("Content-Disposition: attachment; filename=$filename");  //File name extension was wrong
			header("Expires: 0");
			header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
			header("Cache-Control: private",false);
			// echo "Some Text"; //no ending ; here
	
			print $this->View->toString(TEMPLATE_DOMAIN_DASHBOARD .'apps/average_user_point_report.html');
			exit;
	}
	
}
?>