<?php

class messageHelper {
	
	
	function __construct($apps){
	global $logger,$CONFIG;
	$this->logger = $logger;
	$this->apps = $apps;
	if($this->apps->isUserOnline())  {
			if(is_object($this->apps->user)) $this->uid = intval($this->apps->user->id);
	}
	else $this->uid = 0;
	$this->dbshema = "marlborohunt";
	$this->action_id = '20,21,23,24,25,26,27,28,30,45';
	$this->limitComment = 5;
	}
	
	
	function getMessage($count=false){
		if($count){
			$qCount = "  0 ";
		}else $qCount = "  0,1   ";
		$type = $this->action_id; // type log action
		$sql =  "
		 
		SELECT my.id, '' fromid, '' recipientid, '' fromwho,my.date_time datetime, my.n_status, '' name, '' last_name,0 type
		FROM tbl_activity_log AS my 
		LEFT JOIN social_member AS person 
		ON my.user_id = person.id 
		WHERE my.user_id = {$this->uid} AND action_id IN ({$type}) AND my.n_status IN ({$qCount}) AND person.id <>'' AND person.id IS NOT NULL
		ORDER BY my.date_time DESC 
		 
		 
		";
		// pr($sql);
		$this->logger->log($sql);
		$qData = $this->apps->fetch($sql,1);
		
		if($qData) {
		
			return $qData;
		
		}
		
		return false;
		
	}
	
	function getInbox($count=false){
		if($count){
			$qCount = " AND my.n_status = 0 ";
		}else $qCount = "  AND my.n_status IN ( 0,1 )  ";
		
		$type = $this->action_id; // type log action
		$sql =  "
		SELECT  my.id,my.fromid,my.recipientid,my.fromwho,my.datetime,my.n_status, sm.name,  sm.last_name, 1 type
		FROM my_message my
		LEFT JOIN social_member sm ON sm.id = my.recipientid		
		WHERE my.recipientid = {$this->uid} {$qCount}
		ORDER BY datetime DESC
		";
		//pr($sql);exit;
		$this->logger->log($sql);
		$qData = $this->apps->fetch($sql,1);
		
		if($qData) {
		
			return $qData;
		
		}
		
		return false;
		
	}
	
	function seeMessage(){
		$qData = $this->getMessage();
		$arrid = false;
		if(!$qData) return false;
		foreach($qData as $val){
		
			$arrid[$val['id']] = $val['id'];
		}
		
		if(!$arrid) return false;
		$strid = implode(',',$arrid);
		
		$sql = "UPDATE my_message SET n_status = 1 WHERE id IN ({$strid}) ";
		$qData = $this->apps->query($sql);
		
		$sqlLog = "UPDATE tbl_activity_log SET n_status = 1 WHERE id IN ({$strid}) ";
		$qDataLog = $this->apps->query($sqlLog);
		
	}
	
	function seeMessageByid($read=false){
		// $qData = $this->getMessage();
		
		$id = $this->apps->_p('id');
		$type = intval($this->apps->_p('type'));
		$fromadmin = intval($this->apps->_p('fromadmin'));
		
		if (!isset($id)) return false;
		// if (!isset($type)) return false;
		
		$getSession = @$_SESSION['statusinbox'];
		if (!$getSession) return false;	
		if ($getSession){
			
			if ($getSession ==1) $n_status = 1; // read inbox
			if ($getSession ==2) $n_status = 0;
			if ($getSession ==3) $n_status = 0; // read trash
			if ($getSession ==4) $n_status = 2; // read trash
		}else{
			$n_status = 1;
		}
		
		if($read){	
			$selectData = false;
			if ($type==1){
				$sql = "SELECT n_status, id FROM my_message  WHERE id = {$id} LIMIT 1 ";
			
				$selectData = $this->apps->fetch($sql);
			}
			if ($type==0){
				if($fromadmin==3)	$sql = "SELECT n_status, id FROM gm_activity_log  WHERE id = {$id} LIMIT 1 ";
				else	$sql = "SELECT n_status, id FROM tbl_activity_log  WHERE id = {$id} LIMIT 1 ";
				
				$selectData = $this->apps->fetch($sql);
			}
				// pr($selectData);
			$n_status = 1;
			if($selectData)if($selectData['n_status']==2) $n_status = 2;
			
		}
		//pr($sql);exit;
		if ($type==1){
			$sql = "UPDATE my_message SET n_status = {$n_status} WHERE id = {$id} ";
			//pr($sql);
			$qData = $this->apps->query($sql);
		}
		
		if ($type==0){
			if($fromadmin==3) $sqlLog = "UPDATE gm_activity_log SET n_status = {$n_status} WHERE id = {$id} ";
			else $sqlLog = "UPDATE tbl_activity_log SET n_status = {$n_status} WHERE id = {$id} ";
			//pr($sqlLog);
			$qDataLog = $this->apps->query($sqlLog);
		}
		return true;
		
		exit;
	}
	
	function deleteMessage(){
		
		$data = $_POST;
		//pr($data);
		if (!isset($data['id'])) return false;
		if (!isset($data['type'])) return false;
		if (!isset($data['admin'])) return false;
		
		foreach ($data['id'] as $key => $value){
			if (intval($key) <=4){
				$res[intval($value)] = $data['type'][intval($key)];
			}
		}
		//pr($data['admin']);
		//if(sizeof($data['admin'])>0){
			foreach ($data['admin'] as $k => $v){
				if (intval($k) <=4){
					if(intval($v)>1){
						$resAdmin[] = intval($v);
					}
				}
			}
		//}
		
		if (!isset($res)) return false;
		
		foreach ($res as $key => $value){
			
			if ($value == 1){
				$message[] = $key;
			}
			if ($value == 0){
				$log[] = $key; 
			}
		}
		
		$getSession = @$_SESSION['statusinbox'];
			
		if ($getSession){
			
			if ($getSession ==1) $n_status = 2; // move to trash
			if ($getSession ==2) $n_status = 0;
			if ($getSession ==3) $n_status = 2; // delete from trash
			if ($getSession ==4) $n_status = 3; // delete from trash
		}else{
			$n_status = 0;
		}
		
		if (isset($message)){
		
			$strid = implode(',',$message);
			$deleteFromTrash = "AND recipientid = {$this->uid}";
			if($n_status==3||$getSession==3){
				$deleteFromTrash = "";
			}
			$sql = "UPDATE my_message SET n_status = {$n_status} WHERE id IN ({$strid}) {$deleteFromTrash} ";
			//pr($getSession);exit;
			//pr($sql);exit;
			$qData = $this->apps->query($sql);
		}
		
		if (isset($log)){
		
			$strid = implode(',',$log);
			$admin_id = implode(',',$resAdmin);
			if ($getSession ==2) $n_status = 2;

			$deleteFromTrash2 = "AND user_id = {$this->uid}";
			if($n_status==3){
				$deleteFromTrash2 = "";
			}
			//pr($resAdmin);
			if($resAdmin){
				 
				$sqlLog = "UPDATE gm_activity_log SET n_status = {$n_status} WHERE id IN ({$strid}) AND action <> 'inputtask'";
				//sql admin per user message status data
				$sqlunnotifadmin = "INSERT INTO tbl_un_notification (userid,notifid,type,n_status) VALUES ";
				$insertVALUES = false;
				$arrstrids = explode(',',$strid);
				// pr($arrstrids);
				foreach($arrstrids as $val){
					$insertVALUES[] = " ({$this->uid},{$val},'adminnotif',{$n_status}) ";
				}
				$sqlpdateduplicate = " ON DUPLICATE KEY UPDATE n_status = VALUES(n_status) ";
				if($insertVALUES){
					$strinsertvalues = implode(',',$insertVALUES);
					$theinsertunnotif  = $sqlunnotifadmin.$strinsertvalues.$sqlpdateduplicate;
					// pr($theinsertunnotif);
					$this->apps->query($theinsertunnotif);
				}
			}else{
				$sqlLog = "UPDATE tbl_activity_log SET n_status = {$n_status} WHERE id IN ({$strid}) {$deleteFromTrash2} ";
			}

			//pr($sqlLog);exit;
			$qDataLog = $this->apps->query($sqlLog);
		}
		
		return true;
	}
	
	function readMessage(){
		
		$id = intval($this->apps->_request('id'));
		if($id==0) return false;
		$sql =  "
		SELECT msg.*,sm.name , sm.last_name 
		FROM my_message msg
		LEFT JOIN social_member sm ON sm.id = msg.userid		
		WHERE recepientid = {$this->uid} AND id={$id} LIMIT 1 ";
		$qData = $this->apps->fetch($sql);
		
		if($qData) {
				
			return $qData;
		
		}
		
		return false;
	}
	
	function createMessage($recipientid=0,$msg=null){
		$datatime = date("Y-m-d H:i:s");
		if($recipientid==0) return false;
		if($msg==null)return false;
		$sql ="
			INSERT INTO my_message (fromid, recipientid, fromwho, message, datetime, n_status)
			VALUES ({$this->uid}, {$recipientid}, 1, '{$msg}', '{$datatime}', 0)
			";
		$this->apps->query($sql);
			
		if($this->apps->getLastInsertId()>0) return true;
		
		return false;
	
	}
	
	function getLimitComment($page=1,$cid=false)
	{
		if (!$page) return false;
		
		$date = date('Y-m-d');
		$filter = "";
		
		switch ($page){
			case '1':
			{
				// news
				if ($cid) $filter = " AND contentid = {$cid}";
				
				$sql = "SELECT COUNT(*) total FROM {$this->dbshema}_news_content_comment 
						WHERE userid={$this->apps->user->id} AND 
						DATE(date) = '{$date}' {$filter}";
			}
			break;
			case '2':
			{
				// move forward
				if ($cid) $filter = " AND parent = {$cid}";
				$sql = "SELECT COUNT(*) total FROM my_post WHERE userid={$this->apps->user->id} AND 
						DATE(datetime) = '{$date}' AND parent IS NOT NULL {$filter}";
				
			}
			break;
		}
		// pr($sql);
		$res = $this->apps->fetch($sql);
		if ($res['total']==intval($this->limitComment)) return true;
		return false;
		
	}
	
	function sendMessage()
	{
		
		$to = strip_tags($this->apps->_p('to'));
		$subject = strip_tags($this->apps->_p('subject'));
		$message = strip_tags($this->apps->_p('message'));
		$receiptID = intval($this->apps->_p('hiddenid'));
		
		$date = date('Y-m-d H:i:s');
		$sql = "INSERT INTO my_message (fromid, recipientid, type, fromwho, subject,message, datetime,n_status)
				VALUES ({$this->apps->user->id}, {$receiptID}, 0, 1, '{$subject}', '{$subject}', '{$date}', 1)";
		// pr($sql);
		$res = $this->apps->query($sql);
		if ($res) return true;
		
		return false;
	}
	
}