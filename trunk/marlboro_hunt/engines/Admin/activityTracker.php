<?php 
class activityTracker{
	function __construct($admin=null){
		$this->admin = $admin;
	}
	
	function sendActivity($action=null,$description=null){
		if($action==null) return false;
		
		 
		$sql = " INSERT INTO gm_activity_log (	admin_id,	date_ts ,	date_time, 	action ,	description ) 
		VALUES ( {$this->admin->Session->getVariable("uid")},".time().",NOW(),'{$action}','{$description}')
		";
		$rs = $this->admin->query($sql);
 
		if($rs) return true;
		else return false;
	
	}
	
	function sendUserActivity($userid=false,$param=NULL,$id=NULL,$expLog=FALSE){
		global $CONFIG;
		
		if($userid) $this->userID = $userid;
		else $this->userID = 0;
	
		// if($this->userID==null) return false;
		$datenow = strtotime(date('Y-m-d H:i:s'));
		$dateNumNow = date('Y-m-d H:i:s');
		
		if($param!=NULL){
			$actionID=0;
			$userID = $this->userID;
			$actionValue = NULL;
	 
			$sql ="SELECT id,point FROM tbl_activity_actions WHERE activityName = '{$param}' ";
		 
			
			$qData = $this->admin->fetch($sql);
			 
									
			 
			if($qData) 	$actionID = $qData['id'];
			
			$score=$qData['point'];			
			
			
			$qData=NULL;
			if($id!=NULL) $actionValue = $id;
			if(array_key_exists('log_session',$CONFIG)){
				if($CONFIG['log_session']==true){
					$sessionSerial = urlencode64(mysql_escape_string(cleanXSS(serialize($_SESSION))));
					$qSession = "{$sessionSerial}";
				}else $qSession = "";
			}else $qSession = "";
			$sql = "INSERT IGNORE INTO tbl_activity_log 
					(id,user_id,date_ts,date_time,action_id,action_value,ipaddress,session) 
					VALUES 
					(NULL,{$userID},{$datenow},'{$dateNumNow}',{$actionID},'{$actionValue}','{$_SERVER['REMOTE_ADDR']}','{$qSession}')
					";
			// print_r($sql);
			// print_r($actionID);
		// pr($sql);exit;
			//activity log : id 	user_id 	date_ts 	date_time 	action_id 	action_value
			if($actionID!=0 ){
		 
			$data = $this->admin->query($sql);
		 // print_r($data);
									
			}
		
		}
		
		if($expLog==TRUE){
	
		
		
			$actScore = intval($score);
			if($actScore==0)	return false;	
			$sql = "
			INSERT  IGNORE INTO tbl_exp_point 
			(id,user_id,date_time_ts,date_time,activity_id,score) 
			VALUES 
			(NULL,{$userID},{$datenow},'{$dateNumNow}',{$actionID},{$actScore})
			";
		
				if($userID!=0  && $actionID!=0 ){
					 
					$this->admin->query($sql);
			 
				
				}
		}
		
		
		
	}
	
	
	
}
?>