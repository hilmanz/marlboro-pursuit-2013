<?php
class web_activities extends App{
	
	
	function beforeFilter(){
		
		$this->webActivityHelper = $this->useHelper("webActivityHelper");
		
		global $LOCALE,$CONFIG;
		$this->assign('basedomain', $CONFIG['DASHBOARD_DOMAIN']);
		$this->assign('basedomainpath', $CONFIG['BASE_DOMAIN_PATH']);
		$this->assign('assets_domain', $CONFIG['ASSETS_DOMAIN_DASHBOARD']);
		
		$this->assign('locale', $LOCALE[1]);
		$this->assign('startdate', $this->_g('startdate'));
		$this->assign('enddate', $this->_g('enddate'));
	
		
	}
	function main(){
		
		$top10visitedPage = $this->webActivityHelper->top10visitedPage();
		$weeklyreport = $this->webActivityHelper->weeklyreport();
		$gamePursuit = $this->webActivityHelper->gamePursuit();
		$joinPursuitAct = $this->webActivityHelper->joinPursuitAct();
		$tradePursuit = $this->webActivityHelper->tradePursuit();
		$redeemMerchandiseAct = $this->webActivityHelper->redeemMerchandiseAct();
		$thisorthatact = $this->webActivityHelper->thisorthatact();
		$eventAct = $this->webActivityHelper->eventAct();
		$newsAct = $this->webActivityHelper->newsAct();
		$myAccountact = $this->webActivityHelper->myAccountact();
		$thisorthatsubmission = $this->webActivityHelper->thisorthatsubmission(); 		
		
		$uploadActivity = $this->webActivityHelper->uploadActivity();
		$commentActivity = $this->webActivityHelper->commentActivity();
		$userHighPoint = $this->webActivityHelper->userHighPoint();
		$topViewedArticle = $this->webActivityHelper->topViewedArticle();
		$topCommentArticle = $this->webActivityHelper->topCommentArticle();
		$uploadFotoEvent = $this->webActivityHelper->uploadFotoEvent();
		$totalCommentAricle = $this->webActivityHelper->totalCommentAricle();
		$totalUserDyo = $this->webActivityHelper->totalUserDyo();
		$totalSubmissiondyo = $this->webActivityHelper->totalSubmissiondyo();
		$hiddenMarlboro = $this->webActivityHelper->hiddenMarlboro();
		
		$numParticipantMovefd = $this->webActivityHelper->numParticipantMovefd();
		$numShareExpMovefd = $this->webActivityHelper->numShareExpMovefd();
		$top5sharepost = $this->webActivityHelper->top5sharepost();
		$top5sharexp = $this->webActivityHelper->top5sharexp();
		
		
		// pr($uploadActivity);
		// pr($weeklyreport['thisorthat']);
		$this->assign("top10visitedPage",$top10visitedPage);
		$this->assign("weeklyreport",$weeklyreport);

		$this->assign("gamePursuit",$gamePursuit);
		$this->assign("joinPursuitAct",$joinPursuitAct);
		$this->assign("tradePursuit",$tradePursuit);
		$this->assign("redeemMerchandiseAct",$redeemMerchandiseAct);
		$this->assign("thisorthatact",$thisorthatact);
		$this->assign("eventAct",$eventAct);
		$this->assign("newsAct",$newsAct);
		$this->assign("myAccountact",$myAccountact);
		$this->assign("thisorthatsubmission",$thisorthatsubmission);
		
		
		$this->assign("uploadActivity",$uploadActivity);
		$this->assign("commentActivity",$commentActivity);
		$this->assign("userHighPoint",$userHighPoint);
		$this->assign("topViewedArticle",$topViewedArticle);
		$this->assign("topCommentArticle",$topCommentArticle);
		$this->assign("uploadFotoEvent",$uploadFotoEvent);
		$this->assign("totalCommentAricle",$totalCommentAricle);
		$this->assign("totalUserDyo",$totalUserDyo);
		$this->assign("totalSubmissiondyo",$totalSubmissiondyo);
		$this->assign("hiddenMarlboro",$hiddenMarlboro);
		
		$this->assign("numParticipantMovefd",$numParticipantMovefd);
		$this->assign("numShareExpMovefd",$numShareExpMovefd);
		$this->assign("top5sharexp",$top5sharexp);
		$this->assign("top5sharepost",$top5sharepost);

		
		if(strip_tags($this->_g('page'))=='home') $this->log('surf','home');
		return $this->View->toString(TEMPLATE_DOMAIN_DASHBOARD .'apps/web_activities.html');
		
	}
	
	function downloadreport(){
			$weeklyreport = $this->webActivityHelper->weeklyreport(true);
			$this->assign("weeklyreport",$weeklyreport);
			$filename = "weekly_report_".date('Ymd_gia').".xls";
			header("Content-Type:   application/vnd.ms-excel; charset=utf-8");
			header("Content-Disposition: attachment; filename=$filename");  //File name extension was wrong
			header("Expires: 0");
			header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
			header("Cache-Control: private",false);
			// echo "Some Text"; //no ending ; here
	
			print $this->View->toString(TEMPLATE_DOMAIN_DASHBOARD .'apps/web_activities_report.html');
			exit;
	}
	
	function downloadreporttop(){
			$userHighPoint = $this->webActivityHelper->userHighPoint(true);
			$this->assign("userHighPoint",$userHighPoint);
			$filename = "top100_report".date('Ymd_gia').".xls";
			header("Content-Type:   application/vnd.ms-excel; charset=utf-8");
			header("Content-Disposition: attachment; filename=$filename");  //File name extension was wrong
			header("Expires: 0");
			header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
			header("Cache-Control: private",false);
			// echo "Some Text"; //no ending ; here
	
			print $this->View->toString(TEMPLATE_DOMAIN_DASHBOARD .'apps/web_activities_report_top.html');
			exit;
	}
	
	function downloadreportupload(){
			$uploadfotoReport = $this->webActivityHelper->uploadfotoReport(true);
			$commentReport = $this->webActivityHelper->commentReport(true);
			$this->assign("uploadfotoReport",$uploadfotoReport);
			$this->assign("commentReport",$commentReport);
			$filename = "upload&comment_report".date('Ymd_gia').".xls";
			header("Content-Type:   application/vnd.ms-excel; charset=utf-8");
			header("Content-Disposition: attachment; filename=$filename");  //File name extension was wrong
			header("Expires: 0");
			header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
			header("Cache-Control: private",false);
			// echo "Some Text"; //no ending ; here
	
			print $this->View->toString(TEMPLATE_DOMAIN_DASHBOARD .'apps/web_activities_report_upload_comment.html');
			exit;
	}
	
}
?>