<?php

global $ENGINE_PATH;
include_once $ENGINE_PATH."Utility/Paginate.php";
		
class botManager extends Admin{
	function __construct(){	
		parent::__construct();	
		
		global $logger;
		
		$this->type = "1,5";
		$this->contentType = "0,3";
		$this->folder =  'gallery';
		$this->dbclass = 'marlborohunt';
		$this->fromwho = 0; // 0 is admin/backend
		$this->total_per_page = 20;
		$this->logger = $logger;
	}
	
	/* Point manager */
	
	function getPointByUser($channel="loginpoint", $point=1, $userid=false)
	{
		
		if ($channel == null) return false;
		
		$sql = "SELECT id, n_status FROM tbl_code_inventory WHERE userid = {$userid} AND point = {$point}
				AND histories LIKE '%{$channel}%' AND n_status = 0 LIMIT 1";
		// pr($sql);
		$res = $this->fetch($sql);
		if ($res){
			if($res['n_status']==0){
				
				$update = "UPDATE tbl_code_inventory SET n_status = 6 WHERE id = {$res['id']} LIMIT 1";
				$result = $this->query($update);
				if ($result) return true;
			}
		}
		
		return false;
	}
	
	
	function setPointByUser($location="LOGIN GIFT", $channel="loginpoint", $point=1, $when=null, $userid=false)
	{
		
		if ($location == null) return false;
		if ($channel == null) return false;
		
		$history = $channel.'gift';
		
		$checkBefore = $this->checkpublicityexist($location, $channel, $when);
		if (!$checkBefore){
			$createcode = $this->generateCode($location, $channel, $when);
			if(!$createcode) return false;
		}
		
		$checkCode = $this->checkpublicexistsinventorybyuser($channel, $when, $userid);
		if(!$checkCode) return false;
		
		if($checkCode['result']){
		
			/* save to inventory user   */
	
			$saved = $this->savetoinventorybyuser(true,$checkCode['data'],$history, $point, $userid);
			if (!$saved) return false;
		}
	}
	
	function checkpublicityexist($location='LOGIN GIFT',$channel='loginpoint', $when=null)
	{
		
		$date = date('Y-m-d');
		
		if ($location == null) return false;
		if ($channel == null) return false;
		if ($when == 'day') $channel = $channel.'_'.$date;
		$sql = "SELECT COUNT(id) total FROM tbl_code_publicity WHERE location = '{$location}' AND channel = '{$channel}'";
		// pr($sql);
		$res = $this->fetch($sql);
		if ($res['total']>0){
			return true;
		}
		
		return false;
	}
	
	function savetoinventorybyuser($win=false,$code=false,$gamenames="20th",$howmany=1, $userid){
		
		if(!$win) return false;
		if(!$code) return false;
		if ($userid) $uid = $userid;
		else return false;
		// $contentHelper = $this->useHelper("contentHelper");
		/* randcode , add proba in here if want to use of fontend */
		$getMasterCodeForRandom = $this->getMasterCodeForRandom();
		$randcodeidmekans = $this->randomcodegen($getMasterCodeForRandom);
		if($randcodeidmekans!=false) $code['codeid']=$randcodeidmekans;
			
		/* userid 	codeid 	codepublicityid 	n_status 	histories */
		$date = date('Y-m-d H:i:s');
		$sql = " INSERT INTO tbl_code_inventory 
		(userid, codeid, codepublicityid, point, n_status, datetimes, histories) 
		VALUES ({$uid},{$code['codeid']},{$code['id']},{$howmany}, 0, '{$date}', ' get from login {$gamenames} ')";		
		// pr($sql);
		$this->query($sql);
		if($this->getLastInsertId()){
			return true;
		}else return false;
		return false;
	}
	
	function randomcodegen($getMasterCode=false){
			global $CONFIG;
			if($getMasterCode==false) return false;
			$this->log->sendActivity(json_encode($getMasterCode));
			$randcode = false;
		 
				foreach ($getMasterCode as $key => $value){
					if(isset($CONFIG['usingelusive'])){
							$popprob = ($value['prob'] * ($value['prob'] * (rand(1,12))));
							$randcode[$value['id']] = $popprob;
					}else{
						if($value['codetype']==0){
							$popprob = ($value['prob'] * ($value['prob'] * (rand(1,12))));
							$randcode[$value['id']] = $popprob;
						}
					}
				}
				if($randcode){
					$this->log->sendActivity(json_encode($randcode));
					$maxCode = max($randcode);
					$this->log->sendActivity($maxCode);
					$codeid = array_search($maxCode, $randcode);
					$this->log->sendActivity($codeid);
					return $codeid;
				}
				return false;
	}
	
	
	
	function checkpublicexistsinventorybyuser($channel='games20th', $when=null, $userid=false){
		
		$date = date('Y-m-d');
		$datetimes = date('Y-m-d H:i:s');
		
		if ($when =='day') $channel = $channel.'_'.$date;
		if ($when =='second') $channel = $channel.'_'.$datetimes;
		$data['result'] = false;
		$data['data'] = false;
		
		if ($userid) $uid = $userid;
		else return false;
		$sql ="
			SELECT * FROM tbl_code_publicity public
			WHERE NOT EXISTS 
			(SELECT * FROM tbl_code_inventory WHERE codepublicityid = public.id AND userid={$uid} ) 
			AND channel='{$channel}' 
			AND n_status = 1 
			LIMIT 1";
		// pr($sql);
		$qData = $this->fetch($sql);
		
		
		if($qData) {
				
			$data['result'] = true;
			$data['data'] = $qData;
		
		}
		return $data;
		
	
	}
	
	function generateCode($codename="20TH LOGIN CODE",$channel="games20th",$when=null)
	{
	
		
		$location = $codename;
		if ($when ==null){
			$channel = $channel;
		}else{
			$date = date('Y-m-d');
			$datetimes = date('Y-m-d H:i:s');
			if ($when =='once') $channel = $channel;
			if ($when =='day') $channel = $channel.'_'.$date;
			if ($when =='second') $channel = $channel.'_'.$datetimes;
		}
		
		$posteddate = date('Y-m-d H:i:s');
		$expireddate =date('Y-m-d H:i:s');
		$group = $channel;

		$sql = "SELECT * FROM tbl_code_detail WHERE n_status = 1 AND  codetype <> 1 ";
		$masterCode = $this->fetch($sql,1);
		$datetime = date("Y-m-d H:i:s");
		$getres = false;
		

			foreach ($masterCode as $key => $value){
				$popprob = ($value['prob'] * ($value['prob'] * (rand(1,12))));
				$code[$value['id']] = $popprob;
				$data[$value['id']] = $value['codename'];
				$masterCode[$key]['popprob'] = $popprob;

			}
			$maxCode = max($code);
			$key = array_search($maxCode, $code);
			$codevalue = $data[$key];
			$letters  = "ABCDEFGHJKMNPQRSTUVWXYZ23456789";
			$maskcode = substr(str_shuffle($letters), 0, 8);
			

			$sql = "INSERT INTO tbl_code_publicity 
					(maskcode, codeid, location, channel, codevalue, datetime, grouptype, n_status, used,posted_date,expired_date)
					VALUES 
					('{$maskcode}', {$key}, '{$location}', '{$channel}', '{$codevalue}', '{$datetime}', '{$group}', 1,0,'{$posteddate}','{$expireddate}')";
			// pr($sql);
			$res = $this->query($sql);
			if($this->getLastInsertId()){
				$getres[$maskcode] = 1;
			}else $getres[$maskcode] = 0;
			
	
		
		if($getres){
			$success = 0;
			$failed = 0;
			foreach($getres as $key => $val){
				if($val==1) $success++;
				else $failed++;			
			}
		
			/* for log generator */
			$this->logthisgenerator($success,$failed,$datetime,$masterCode);
				
				return true;
		}
		
				
			return false;
	}
	
	
	
	function logthisgenerator($success=0,$failed=0,$datetime=null,$data=false){
	
		if($datetime==null) return false;
		if($data==false) return false;
		
		
		$data = serialize($data);
		
		$sql = " INSERT INTO tbl_code_log (success,failed,datetime,data) VALUES ({$success},{$failed},'{$datetime}','{$data}')";		
		
		$this->query($sql);
		if($this->getLastInsertId()){
			return true;
		}else return false;
	
	}
	
		
	function getMasterCode()
	{
		$sql = "SELECT id, codename FROM tbl_code_detail WHERE n_status = 1 ";
		$result = $this->fetch($sql,1);
		if ($result){
		$data = false;
			foreach ($result as $value){
				$data[$value['id']] = $value['codename'];
				// $dataID[] = $value['id'];
				// $codeName[] = $value['codename'];
			}
	
			$this->log->sendActivity(json_encode($data));
	
			return $data;
		}else{
			return false;
		}
	}
	
	function getMasterCodeForRandom()
	{
		global $CONFIG;
		if(isset($CONFIG['usingelusive'])) $qidcode = "";
		else $qidcode = " AND codetype = 0 ";
		
		$sql = "SELECT * FROM tbl_code_detail WHERE n_status = 1 {$qidcode} ";
		$result = $this->fetch($sql,1);
		if ($result){
		
			return $result;
		}else{
			return false;
		}
	}
}