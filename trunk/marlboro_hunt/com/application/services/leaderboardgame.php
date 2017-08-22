<?php
class leaderboardgame extends ServiceAPI{
		
	function beforeFilter(){
			$this->botHelper = $this->useHelper('botHelper');
			
	}
	
	
	function runserviceweekly()
	{
		$data = $this->botHelper->reachLeaderboardGameweekly();
		print json_encode($data);exit;	
	}
	
	function runservicemonth()
	{
		$data = $this->botHelper->reachLeaderboardGamemonth();
		print json_encode($data);exit;	
	}
	
}
?>