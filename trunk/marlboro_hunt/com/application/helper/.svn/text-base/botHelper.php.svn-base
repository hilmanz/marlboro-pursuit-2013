<?php 

class botHelper {

	function __construct($apps){
		global $logger;
		$this->logger = $logger;
		$this->apps = $apps;
		$this->uid  = 0;
		if(is_object($this->apps->user)) $this->uid = intval($this->apps->user->id);
		
		$this->dbshema = "marlborohunt";	
		
		$this->week = 7;
		$week = intval($this->apps->_request('weeks'));
		if($week!=0) $this->week = $week;
		
		$this->startweekcampaign = "2013-05-20";
		$this->datetimes = date("Y-m-d H:i:s");
		$this->multiple = 20;
		// pr($this->apps->_request('week'));
	}

	
	function get20logingift()
	{
		global $LOCALE;
		$contentHelper = $this->apps->useHelper("contentHelper");
		$checkCode = false;
		$mytoken = false;
		$gamesarrayid = array(1,2);
		$token = strip_tags($this->apps->_p('token'));
		$salt = "gameapihelper";
		// if(!$token) return false;
		/* token matching with*/
		$mytoken = sha1($this->uid.date("YmdHi")."true{".$salt."}");
	
	
		/* validation */
		if($this->uid==0) return false;		
		// if($token!=$mytoken) return false;
		
		/* check this user has got 20 login codes */
		$checkgot20logincode  = $this->checkgot20logincode();
		if($checkgot20logincode) return false;
		
		/* check this user has 20++ login  */
		$checkuserlogincount  = $this->checkuserlogincount();
		if(!$checkuserlogincount) return false;
		
		
		$checkCode = $this->checkpublicexistsinventory();
		if(!$checkCode) return false;
		
		if($checkCode['result']){
			
					/* save to inventory user if  */
					$saved = $this->savetoinventory(true,$checkCode['data']);
					if($saved){
							sleep(1);
							$contentHelper->completetask(1460);
							sleep(1);
							$this->apps->log('accompalished',$LOCALE[1]['20thloginupdate']);
							return $checkCode;
					}
			
		}else{
			
			$checkCode = false;
			/* if not found code in publicity code, create 1 code for this user */
			
			$firstcreatecode = $this->generateCode();
			if(!$firstcreatecode) return false;		
			
			$checkCode = $this->checkpublicexistsinventory();
			if(!$checkCode) return false;
				
			if($checkCode['result']){
				
					/* save to inventory user   */
			
						
					$saved = $this->savetoinventory(true,$checkCode['data']);
					
					if($saved) {
						sleep(1);
						$contentHelper->completetask(1460);
						sleep(1);
						$this->apps->log('accompalished',$LOCALE[1]['20thloginupdate']);
						return $checkCode;
					}
			
			}else return false;
		}
		
		return false;
	}
	
	function getuserfirstloginletter()
	{
		$checkCode = false;
		$mytoken = false;
		$usertype = array(0,1,2);
		$gamesarrayid = array(1,2);
		$usertypeletter[0]='onlineuserletter';
		$usertypeletter[1]='offlineuserletter';
		$usertypeletter[2]='existinguserletter';
		$token = strip_tags($this->apps->_p('token'));
		$salt = "gameapihelper";
		// if(!$token) return false;
		/* token matching with*/
		$mytoken = sha1($this->uid.date("YmdHi")."true{".$salt."}");
	
	
		/* validation */
	
		if($this->uid==0) return false;		
		// if($token!=$mytoken) return false;
		
		
		
		/* check this user */
		$checkuserexist  = $this->checkuserisexisting();
		if($checkuserexist['usertype']==2) $howmany =2 ;
		else $howmany=1;
		
		for($i=1;$i<=$howmany;$i++):
		
		if(!in_array($checkuserexist['usertype'],$usertype)) return false;	
		
		/* check this user has got existing codes */
		$checkgotfirstlogincode  = $this->checkgotfirstlogincode($usertypeletter[$checkuserexist['usertype']]);		
		if($checkgotfirstlogincode) return false;
		
		$checkCode = $this->checkpublicexistsinventory($usertypeletter[$checkuserexist['usertype']]);
		
		if(!$checkCode) return false;
	
		if($checkCode['result']){
			
					/* save to inventory user if  */
					
					$saved = $this->savetoinventory(true,$checkCode['data'],$usertypeletter[$checkuserexist['usertype']]);
					
					if($saved) {
						
						if ($i>=$howmany) {
							// $this->apps->contentHelper->completetask();
							return array('status'=>true, 'type'=>$checkuserexist['usertype']);
						}
					}
			
		}else{
		
			$checkCode = false;
			/* if not found code in publicity code, create 1 code for this user */
			
			$firstcreatecode = $this->generateCode("FIRST LOGIN LETTER USER",$usertypeletter[$checkuserexist['usertype']]);
			if(!$firstcreatecode) return false;		
				
			$checkCode = $this->checkpublicexistsinventory($usertypeletter[$checkuserexist['usertype']]);
			if(!$checkCode) return false;
				
			if($checkCode['result']){
			
					/* save to inventory user   */
			
						
					$saved = $this->savetoinventory(true,$checkCode['data'],$usertypeletter[$checkuserexist['usertype']]);
					
					if($saved){
						// echo $i.' and how many'.$howmany;
						if ($i>=$howmany) {
							// $this->apps->contentHelper->completetask();
							return array('status'=>true, 'type'=>$checkuserexist['usertype']);
						}
						// return array('status'=>true, 'type'=>$checkuserexist['usertype']);
					}
			
			}else {
			
			return false;
			}
		}
		
		endfor;
		return false;
	}
	
	
	function checkuserisexisting(){
		$sql ="SELECT usertype FROM social_member WHERE id={$this->uid} LIMIT 1";
		$qData = $this->apps->fetch($sql);
		
	
		if($qData) {
			return $qData;
		
		}
		return false;
	
	}
	
	
	function checkgot20logincode(){
		global $CONFIG;
		
		if($CONFIG['20loginevent']==false) return true;
		
		$sql ="
			SELECT COUNT(*) total FROM tbl_code_publicity public
			WHERE EXISTS 
			(SELECT * FROM tbl_code_inventory WHERE codepublicityid = public.id AND userid={$this->uid} ) 
			AND channel='games20th' 
			AND n_status = 1 
			LIMIT 1";
		
	
		$qData = $this->apps->fetch($sql);
		
		if($qData) {
		
			if($qData['total']>0) 	{
			
				return true;
			}else return false;
		
		}
		return false;
	
	
	}
	
	function checkuserlogincount(){
		// $sql ="SELECT login_count FROM social_member WHERE id={$this->uid} LIMIT 1";
		$sql ="
		SELECT COUNT(*) total FROM (
			SELECT user_id,DATE(date_time) dd FROM tbl_activity_log WHERE user_id={$this->uid} AND DATE(date_time) >= '2013-07-01' GROUP BY dd LIMIT 20
		) a
		";
		$qData = $this->apps->fetch($sql);
		
	
		if($qData) {
		
			if($qData['total']>=$this->multiple) 	{
				return true;
				// if(($qData['login_count']%$this->multiple)==0) 	return true;
			}
		
		}
		return false;
	}
	
	
	
	function savetoinventory($win=false,$code=false,$gamenames="20th",$howmany=1){
		// pr("stat: {$win} , {$code} , {$gamenames} ");
		if(!$win) return false;
		if(!$code) return false;
		$contentHelper = $this->apps->useHelper("contentHelper");
		/* randcode , add proba in here if want to use of fontend */
		$getMasterCodeForRandom = $this->getMasterCodeForRandom();
		$randcodeidmekans = $contentHelper->randomcodegen($getMasterCodeForRandom);
		if($randcodeidmekans!=false) $code['codeid']=$randcodeidmekans;
			
		/* userid 	codeid 	codepublicityid 	n_status 	histories */
		$date = date('Y-m-d H:i:s');
		
		if(preg_match("/commentpoint/i",$gamenames)) {
			$histories = "{$gamenames}";
			$n_status = 6;
		}else {
			$histories = "  get from {$gamenames}  ";
			$n_status = 0;
		}
		
		$sql = " INSERT INTO tbl_code_inventory 
		(userid, codeid, codepublicityid, point, n_status, datetimes, histories) 
		VALUES ({$this->uid},{$code['codeid']},{$code['id']},{$howmany}, {$n_status}, '{$date}', '{$histories}')";		
		// pr($sql);
		$this->apps->query($sql);
		if($this->apps->getLastInsertId()){
			return true;
		}else return false;
		return false;
	}
	
	function savetoinventorybyuser($win=false,$code=false,$gamenames="20th",$howmany=1, $userid){
		
		if(!$win) return false;
		if(!$code) return false;
		if ($userid) $uid = $userid;
		else $uid = $this->uid;
		$contentHelper = $this->apps->useHelper("contentHelper");
		/* randcode , add proba in here if want to use of fontend */
		$getMasterCodeForRandom = $this->getMasterCodeForRandom();
		$randcodeidmekans = $contentHelper->randomcodegen($getMasterCodeForRandom);
		if($randcodeidmekans!=false) $code['codeid']=$randcodeidmekans;
			
		/* userid 	codeid 	codepublicityid 	n_status 	histories */
		$date = date('Y-m-d H:i:s');
		$sql = " INSERT INTO tbl_code_inventory 
		(userid, codeid, codepublicityid, point, n_status, datetimes, histories) 
		VALUES ({$uid},{$code['codeid']},{$code['id']},{$howmany}, 0, '{$date}', ' get from {$gamenames} ')";		
		// pr($sql);
		$this->apps->query($sql);
		if($this->apps->getLastInsertId()){
			return true;
		}else return false;
		return false;
	}
	
	
	function checkgotfirstlogincode($channel='games20th'){
	
		$sql ="
			SELECT COUNT(*) total FROM tbl_code_publicity public
			WHERE EXISTS 
			(SELECT * FROM tbl_code_inventory WHERE codepublicityid = public.id AND userid={$this->uid} ) 
			AND channel='{$channel}' 
			AND n_status = 1 
			LIMIT 1";
		
	
		$qData = $this->apps->fetch($sql);
		
		if($qData) {
			if($channel=='existinguserletter') $howmany = 1;
			else $howmany = 0;
			// pr($qData);
			// pr($channel);
			// pr($howmany);
			if($qData['total']>$howmany) 	{
			
				return true;
			}else return false;
		
		}
		return false;
	
	
	}
	
	function checkpublicexistsinventory($channel='games20th', $when=null){
		
		$date = date('Y-m-d');
		
		if ($when =='day') $channel = $channel.'_'.$date;
		else $channel = $channel;
		$data['result'] = false;
		$data['data'] = false;
		
		$sql ="
			SELECT * FROM tbl_code_publicity public
			WHERE NOT EXISTS 
			(SELECT * FROM tbl_code_inventory WHERE codepublicityid = public.id AND userid={$this->uid} ) 
			AND channel='{$channel}' 
			AND n_status = 1 
			LIMIT 1";
		// pr($sql);
		$qData = $this->apps->fetch($sql);
		
		
		if($qData) {
				
			$data['result'] = true;
			$data['data'] = $qData;
		
		}
		return $data;
		
	
	}
	
	function checkpublicexistsinventorybyuser($channel='games20th', $when=null, $userid=false){
		
		$date = date('Y-m-d');
		$datetimes = date('Y-m-d H:i:s');
		if ($when =='day') $channel = $channel.'_'.$date;
		if ($when =='second') $channel = $channel.'_'.$datetimes;
		$data['result'] = false;
		$data['data'] = false;
		
		if ($userid) $uid = $userid;
		else $uid = $this->uid;
		$sql ="
			SELECT * FROM tbl_code_publicity public
			WHERE NOT EXISTS 
			(SELECT * FROM tbl_code_inventory WHERE codepublicityid = public.id AND userid={$uid} ) 
			AND channel='{$channel}' 
			AND n_status = 1 
			LIMIT 1";
		// pr($sql);
		$qData = $this->apps->fetch($sql);
		
		
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
		$masterCode = $this->apps->fetch($sql,1);
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
			$res = $this->apps->query($sql);
			if($this->apps->getLastInsertId()){
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
		
		$this->apps->query($sql);
		if($this->apps->getLastInsertId()){
			return true;
		}else return false;
	
	}
	
		
	function getMasterCode()
	{
		$sql = "SELECT id, codename FROM tbl_code_detail WHERE n_status = 1 ";
		$result = $this->apps->fetch($sql,1);
		if ($result){
		$data = false;
			foreach ($result as $value){
				$data[$value['id']] = $value['codename'];
				// $dataID[] = $value['id'];
				// $codeName[] = $value['codename'];
			}
	
			$this->logger->log(json_encode($data));
	
			return $data;
		}else{
			return false;
		}
	}
	
	function getMasterCodeForRandom()
	{
		global $CONFIG;
		if($CONFIG['usingelusive']) $qidcode = "";
		else $qidcode = " AND codetype = 0 ";
		
		$sql = "SELECT * FROM tbl_code_detail WHERE n_status = 1 {$qidcode} ";
		$result = $this->apps->fetch($sql,1);
		if ($result){
		
			return $result;
		}else{
			return false;
		}
	}
	
	function sendUserMailviaServices()
	{
		$start = "2013-08-20";
		$end = "2013-08-22 12:00:00";
			
		$sql = "SELECT mc.email, sm.name, sm.last_name, mc.redeemdate, mm.letter
				FROM my_merchandise mc
				LEFT JOIN social_member sm ON mc.userid = sm.id
				LEFT JOIN marlborohunt_merchandise mm ON mc.merchandiseid = mm.id
				WHERE redeemdate >= '{$start}'
				AND redeemdate < '{$end}'";
		// pr($sql);
		$res = $this->apps->fetch($sql,1);
		if ($res){
			return $res;
		}
		
		return false;
		
	}
	
	function checkpublicityexist($location='LOGIN GIFT',$channel='loginpoint', $when=null)
	{
		
		$date = date('Y-m-d');
		
		if ($location == null) return false;
		if ($channel == null) return false;
		if ($when == 'day') $channel = $channel.'_'.$date;
		$sql = "SELECT COUNT(id) total FROM tbl_code_publicity WHERE location = '{$location}' AND channel = '{$channel}'";
		// pr($sql);
		$res = $this->apps->fetch($sql);
		if ($res['total']>0){
			return true;
		}
		
		return false;
	}
	
	function setPointgift($location="LOGIN GIFT", $channel="loginpoint", $point=1, $when=null)
	{
		
		if ($location == null) return false;
		if ($channel == null) return false;
		
		$history = $channel.'gift';
		// pr('masuk');
		$checkBefore = $this->checkpublicityexist($location, $channel, $when);
		if(preg_match("/commentpoint/i",$channel)) {
			$commentdatadaily = $this->checkusercommentthisday();
			// pr('masuk comment point');
			// pr(' point nya :'.$commentdatadaily);
			if($commentdatadaily) $checkBefore = false;
		}
		if (!$checkBefore){
			$createcode = $this->generateCode($location, $channel, $when);
			if(!$createcode) return false;
			$this->logger->log('generate new code for '.$channel);
			// echo 'generate';
		}
			// pr('masuk comment chcek');
		// echo 'ada1';
		$checkCode = $this->checkpublicexistsinventory($channel, $when);
		if(!$checkCode) return false;
		$this->logger->log('publicity code exist');
		// echo 'ada2';
		// pr($checkCode);
		if($checkCode['result']){
			// pr('masuk comment result');
			/* save to inventory user   */
			
			$saved = $this->savetoinventory(true,$checkCode['data'],$history, $point);
			if (!$saved) return false;
			$this->logger->log('save to inventory');
			// echo 'ada3';
		}
		
		// echo 'ada4';
	}
	function checkusercommentthisday(){
		$datetimes = date("Y-m-d");
		$sql ="SELECT COUNT(*) total FROM marlborohunt_news_content_comment WHERE DATE(date) = '{$datetimes}' AND userid={$this->uid} ";
		$qData =$this->apps->fetch($sql);
		$totalcomment = 0;
		if($qData){
			$totalcomment = intval($qData['total']);
			if($totalcomment<=100) return true;
		}else return false;
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
			return true;
		}
	}
	
	
	function reachLeaderboardGameweekly()
	{
		
		$gamesid = array(1,2,4,5);
		$gamesValue = array('crossout','walbreaker','doubtcrash','wordhunt');
		// pr($gamesid);
		
		foreach ($gamesid as $key => $val){
			$sql = "SELECT SUM(point) totalpoint, mg.userid, sm.name, sm.last_name FROM my_games AS mg
					LEFT JOIN social_member AS sm ON mg.userid = sm.id
					WHERE mg.gamesid = {$val} 
					AND sm.name IS NOT NULL 
					AND sm.name <> ''
					AND mg.datetimes >= (SELECT DATE_ADD(CURDATE(), INTERVAL (MOD(DAYOFWEEK(CURDATE())-2, 7)*-1) DAY) AS week_start)
					AND mg.datetimes < (SELECT DATE_ADD(CURDATE(), INTERVAL ((MOD(DAYOFWEEK(CURDATE())-1, 7)*-1)+8) DAY) AS week_end)
					GROUP BY mg.userid
					ORDER BY totalpoint DESC LIMIT 1";
			$qData = $this->apps->fetch($sql);
			// pr($sql);
			if($qData){
				
				$this->setPointByUser("REACH LEADERBOARD GAME", "reachleaderboardweekly_".$gamesValue[$key], 3, null, $qData['userid']);
				// return true;
				// check if user has got point
				// json_encode(array('status'=>true));
			}
			// return false;
		}
		
	}
	
	function reachLeaderboardGamemonth()
	{
		$gamesid = array(1,2,4,5);
		$gamesValue = array('crossout','walbreaker','doubtcrash','wordhunt');
		
		foreach ($gamesid as $key => $val){
			$sql = "SELECT SUM(point) totalpoint, mg.userid, sm.name, sm.last_name FROM my_games AS mg
					LEFT JOIN social_member AS sm ON mg.userid = sm.id
					WHERE mg.gamesid = {$val} 
					AND sm.name IS NOT NULL 
					AND sm.name <> ''
					AND mg.datetimes >= (SELECT DATE_SUB(LAST_DAY(DATE_ADD(NOW(), INTERVAL 0 MONTH)), INTERVAL DAY(LAST_DAY(DATE_ADD(NOW(), INTERVAL 0 MONTH)))-1 DAY) AS firstOfNextMonth)
					AND mg.datetimes < (SELECT DATE_SUB(LAST_DAY(DATE_ADD(NOW(), INTERVAL 1 MONTH)), INTERVAL DAY(LAST_DAY(DATE_ADD(NOW(), INTERVAL 1 MONTH)))-1 DAY) AS lastOfNextMonth)
					GROUP BY mg.userid
					ORDER BY totalpoint DESC LIMIT 1";
			$qData = $this->apps->fetch($sql);
			// pr($sql);
			if($qData){
				
				$this->setPointByUser("REACH LEADERBOARD GAME", "reachleaderboardmonth_".$gamesValue[$key], 5, null, $qData['userid']);
				// return true;
				// check if user has got point
			}
			// return false;
		}
		
	}
	
	function memberUpdateStatus($userid=false)
	{
		
		if (!$userid) return false;
		$sql = "UPDATE social_member SET n_status = 1 WHERE id = {$userid} LIMIT 1";
		// pr($sql);
		$res = $this->apps->query($sql);
		if ($res) return true;
		return false;
	}
	
	
	function bhreportdata(){
		
		$idoftask = intval($this->apps->_g('taskid'));
		$datas = array();
		if($idoftask==0) return $datas;
		$domain = "http://marlboro-prod-mobi.yellowhub.com";
		$url = "{$domain}/api/taskdata/lists/{$idoftask}";
		
		$user_agent = 'Mozilla/5.0 (Windows; U;Windows NT 5.1; ru; rv:1.8.0.9) Gecko/20061206 Firefox/1.5.0.9';
				
		$ch = curl_init($url);                                                                      
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");                                               
                                                             
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);                                                                      
		curl_setopt($ch, CURLOPT_HTTPHEADER, array(                                                                          
			'Content-Type: application/json',                                                                                
			'Content-Length:0 ' )                                                                       
		);                         
		curl_setopt($ch, CURLOPT_USERAGENT, $user_agent);  		
		$result = curl_exec($ch);
		$info = curl_getinfo($ch);
		curl_close($ch);
		 
		$thedata =json_decode($result);
		if($thedata){
			foreach($thedata as $val){
				$arremail[$val->email] = "'{$val->email}'";
				$arrtransid[$val->transac_id] = "'{$val->transac_id}'";
				$arrtaskid[$val->task_id] = "'{$val->task_id}'";
				$arrInsert[$val->email] = "('{$val->device_id}','{$val->transac_id}','{$val->bh_id}','{$val->task_title}','','{$val->task_id}','{$val->email}','{$val->code}','{$val->survey_date}','{$val->upload_date}',1)";
			}
			if($arrInsert){ 

				
				if($arremail){
					$stremail = implode(',',$arremail);
					$strtrandid = implode(',',$arrtransid);
					$strtaskid = implode(',',$arrtaskid);
					$sql =" 
					SELECT email,id 
					FROM social_member sm 
					WHERE 
						n_status = 1 
						AND email IN ({$stremail}) 
						AND NOT EXISTS ( SELECT * FROM tbl_report_letter_elusive e WHERE e.email  IN ({$stremail}) AND transid IN ({$strtrandid}) AND  taskid IN ({$strtaskid}) )  ";
					
					$qData = $this->apps->fetch($sql,1);
					
							// pr($sql);
					// pr($qData);exit;
					$strInsertData = implode(',',$arrInsert);
					$sql ="  INSERT INTO `tbl_report_letter_elusive` ( `deviceid` ,`transid` ,`bhid` ,`bhname` ,`bhcity` ,`taskid` ,`email` ,`code` ,`surveydate` ,`uploaddate` ,`n_status`)
					VALUES {$strInsertData}";
						// pr($sql);exit;
					$this->apps->query($sql);
					
			
					if($qData){
						foreach($qData as $val){
							
							 
							$datas[$val['email']] = $this->setPointByUser("BH DATA API", "bhdataimporter", 5,null,$val['id']);
							sleep(1);
						}
					}else $datas[] = "no data to alter, email not already exists {$stremail} ";
					
				}else $datas[] = "no data to alter, email not  exists ";
			}else $datas[] = "no data to alter, no data email to insert on elusive report ";
		}else $datas[] = "no data to alter, no data feeding from API {$url} ";
		
		return $datas;
		
	}
	
	
}

?>


