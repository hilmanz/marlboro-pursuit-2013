<?php 

class gamesHelper {

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
		
		$this->level = array(1,2,3);
		$this->gamesarrayid =array(1,2,4,5,7,8);
		$this->singlelevelgame =array(4,7,8);
		$this->pointarr = array(0,10,20,30,40,50);	
		// pr($this->apps->_request('week'));
	}
	
	
	function getLastOldToken(){
	
	 
		
		$sql = " SELECT token FROM my_games WHERE userid = {$this->uid}  ORDER BY datetimes DESC LIMIT 1";
		
		$lastToken = $this->apps->fetch($sql);
		if($lastToken){
			//fresh start
				// pr($sql);
				// pr($lastToken);
			if($lastToken['token']==''){
				return $this->uid;
			}else{
			// has token old
				return $lastToken['token'];
			}
			
		}
			
		return false;	
		
	}
	
	
	function checkstatus()
	{
		$checkCode = false;
		$mytoken = false;

		/* parse win status of user games */
		$token = strip_tags($this->apps->_p('token'));
		$win = strip_tags($this->apps->_p('win'));
		$userid = strip_tags($this->apps->_p('userid'));
				
		$gamesid = strip_tags($this->apps->_p('gamesid'));
		if(!in_array($gamesid,$this->gamesarrayid)) return false;
		$level = intval($this->apps->_p('level'));
		
		$point = intval($this->pointarr[$level]);
		
		if(!in_array($point,$this->pointarr)) return false;
		
		$getLastOldToken = $this->getLastOldToken();
		
		$salt = "gameapihelper";
			$this->logger->log('phase 1: check token '.$gamesid );
			$this->logger->log('phase 1b: check level '.$level );
		if(!$token) return false;
			$this->logger->log('phase 1: OK ');
		/* token matching with erwin */
		$mytoken = sha1($this->uid.date("YmdHi").$getLastOldToken."true{".$salt."}");
		$mytokentolerance = sha1($this->uid.date("YmdHi",strtotime(date("YmdHi")." -1 minute ")).$getLastOldToken."true{".$salt."}"); /* tolerance 1 minute */
			$this->logger->log('phase 2: check param information ');
		if($this->uid==0) return false;
			$this->logger->log('phase 2: id OK ');
		if($this->uid!=$userid) return false;
			$this->logger->log('phase 2: all OK ');
		/* check user dont have code in publicity code where code not exists in their inventory */
		$checkuserplaygames = $this->checkuserplaygames();
		$checkuserwinthislevel = $this->checkuserwinthislevel();
		
		
		
			$this->logger->log('phase 2b: token '.$token.' '.$mytoken.', tolerance: '.$mytokentolerance.' using token concat this = '.$this->uid.date("YmdHi").$getLastOldToken."true{".$salt."}");
		if($token!=$mytoken) {
			if($token!=$mytokentolerance) return false;
		}
		
		/* give user log playing games */
			$this->logger->log('phase 3: log user current games ');
			$playinggamesid = intval($this->playinggames($mytoken));
			$this->logger->log('phase 3: OK ');
			
			$this->logger->log('phase 2b: token OK ');
		
			$this->logger->log('phase 4: check win ');
		if($win!="true") return false;
			$this->logger->log('phase 4: OK ');
		
			$this->logger->log('phase 5: check user play games ');
		if(!$checkuserplaygames) return false;
			$this->logger->log('phase 5: OK ');
		
		$this->logger->log('phase 6: check code public for games in inventory');
		$checkCode = $this->checkpublicexistsinventory();
		if(!$checkCode) return false;
			$this->logger->log('phase 6: OK ');
		
		if($checkCode['result']){
					$this->logger->log('phase 6: result OK ');
					/* save to inventory user if win */
					$this->logger->log('phase 7: save to inventory ');
				if($win=="true"){
					$this->logger->log('phase 7: OK ');
					if(!$checkuserwinthislevel)  return false;
					
					if(in_array($level,$this->level)) {
						$checkuserplaygames = $this->checkuserplaygames();
						if(!$checkuserplaygames) return false;
						
							
							if(($level==3) and (!in_array($gamesid,$this->singlelevelgame))){
							$this->logger->log('phase 10: level = 3 and id games not 4 ');
							$gametask = $this->getgametask();
								if($gametask) {
									$this->logger->log('phase 11: game task available ');
									sleep(1);
									// $this->apps->log( 'account', $this->apps->user->name.' Accomplished task '.$gametask['title'] );
									$this->apps->log( 'account', ' You have accomplished "'.strtoupper($gametask['title']).'"' );
									sleep(1);
									$this->apps->log( 'accompalished', $gametask['title'] );
									sleep(1);
									$this->apps->contentHelper->completetask($gametask['id']);
									$this->logger->log('phase 12: send notif ');
								}else{
									$this->logger->log('phase 12: not send notif ');
								
								}
							}else if (in_array($gamesid,$this->singlelevelgame)){
								$gametask = $this->getgametask();
								if($gametask) {					
									$this->logger->log('phase 11: game task available ');
									sleep(1);
									// $this->apps->log( 'account', $this->apps->user->name.' Accomplished task '.$gametask['title'] );
									$this->apps->log( 'account', ' You have accomplished "'.strtoupper($gametask['title']).'"' );
									sleep(1);
									$this->apps->log( 'accompalished', $gametask['title'] );
									sleep(1);
									$this->apps->contentHelper->completetask($gametask['id']);
									$this->logger->log('phase 12: send notif ');
								}else{
									$this->logger->log('phase 12: not send notif ');
								}
							}							
						
						
						$saved = $this->savetoinventory($win,$checkCode['data'],$playinggamesid);
					}else $saved = true;
					if($saved) return $checkCode['data'];
				}
				
		}else{
				$this->logger->log('phase 6: result NOT OK ');
			$checkCode = false;
			/* if not found code in publicity code, create 1 code for this user */
				$this->logger->log('phase 7b: generate code ');
			$firstcreatecode = $this->generateCode();
			if(!$firstcreatecode) return false;		
				$this->logger->log('phase 7b: OK ');
			
				$this->logger->log('phase 8: check code in this inventory again ');
			$checkCode = $this->checkpublicexistsinventory();
			if(!$checkCode) return false;
				$this->logger->log('phase 8: OK ');
			if($checkCode['result']){
					$this->logger->log('phase 8: result OK ');
					/* save to inventory user if win */
				if($win=="true"){
					$this->logger->log('phase 9: save to inventory ');
					if(!$checkuserwinthislevel) return false;
					
					if(in_array($level,$this->level)) {	
						$checkuserplaygames = $this->checkuserplaygames();
						if(!$checkuserplaygames) return false;
						if(($level==3) and (!in_array($gamesid,$this->singlelevelgame))){
							$this->logger->log('phase 10: level = 3 and id games not 4 ');
							$gametask = $this->getgametask();
							if($gametask) {
								$this->logger->log('phase 11a: game task available ');
								sleep(1);
								// $this->apps->log( 'account', $this->apps->user->name.' Accomplished task '.$gametask['title'] );
								$this->apps->log( 'account', ' You have accomplished "'.strtoupper($gametask['title']).'"' );
								// $this->apps->log( 'account',$gametask['title'] );
								sleep(1);
								// $this->apps->log( 'accompalished', $this->apps->user->name.' Accomplished task '.$gametask['title'] );
								$this->apps->log( 'accompalished', $gametask['title'] );
								sleep(1);
								$this->apps->contentHelper->completetask($gametask['id']);
								$this->logger->log('phase 12: send notif ');
							}else{
								$this->logger->log('phase 11b: not send notif ');
							}
						}else if (in_array($gamesid,$this->singlelevelgame)){
							$gametask = $this->getgametask();
							if($gametask) {
								$this->logger->log('phase 11: game task available ');
								sleep(1);
								// $this->apps->log( 'account', $this->apps->user->name.' Accomplished task '.$gametask['title'] );
								$this->apps->log( 'account', ' You have accomplished "'.strtoupper($gametask['title']).'"' );
								// $this->apps->log( 'account',$gametask['title'] );
								sleep(1);
								// $this->apps->log( 'accompalished', $this->apps->user->name.' Accomplished task '.$gametask['title'] );
								$this->apps->log( 'accompalished', $gametask['title'] );
								sleep(1);
								$this->apps->contentHelper->completetask($gametask['id']);
								$this->logger->log('phase 12: send notif ');
							}else{
								$this->logger->log('phase 12: not send notif ');
							}
						}						
						$saved = $this->savetoinventory($win,$checkCode['data'],$playinggamesid);
					}else $saved = true;
					$this->logger->log('phase 9: '.$saved);
					if($saved) return $checkCode['data'];
				}
			}else return false;
		}
		
		return false;
	}
	
	function getgametask(){
		
		$gamesid = intval($this->apps->_p('gamesid'));
		if(!in_array($gamesid,$this->gamesarrayid)) return false;
		$typeofgames[1] = 6;
		$typeofgames[2] = 22;
		$typeofgames[4] = 25;
		$typeofgames[5] = 24;
		$typeofgames[8] = 26;//move forward
		$typeofgames[7] = 27;//word tap
		$sql =" SELECT * FROM {$this->dbshema}_news_content WHERE articleType={$typeofgames[$gamesid]} AND n_status=1 LIMIT 1";
		$qData = $this->apps->fetch($sql);
		// pr($qData); 
		$this->logger->log($sql);
		if(!$qData) return false;
		return $qData;
		
	}
	
	function playinggames($token=false){
		
		$datetime = date("Y-m-d H:i:s");
		
		$gamesid = intval($this->apps->_p('gamesid'));
		if(!in_array($gamesid,$this->gamesarrayid)) return false;
		
		$level = intval($this->apps->_p('level'));
		$point = intval($this->pointarr[$level]);
		
		if(!in_array($point,$this->pointarr)) return false;
		if($token==false)  return false;
		
		$sql = " INSERT INTO my_games 
		( 	gamesid ,	userid 	,point 	,datetimes, 	n_status ,token,win) 
		VALUES ({$gamesid},{$this->uid},{$point},'{$datetime}',1,'{$token}',0)";		
		
		$this->apps->query($sql);
		// pr($sql);
		$lastid = intval($this->apps->getLastInsertId());
		if($lastid){
			return $lastid;
		}else return false;
		
	
	}
	
	function checkuserwinthislevel(){
	
		$datetime = date("Y-m-d H:i:s");
		
		$gamesid = intval($this->apps->_p('gamesid'));
		if(!in_array($gamesid,$this->gamesarrayid)) return false;
		
		$level = intval($this->apps->_p('level'));
		$point = intval($this->pointarr[$level]);
		if(!in_array($point,$this->pointarr)) return false;
		
		//check user has win the game at this level
		$sql = " 
		SELECT COUNT(*) total 
		FROM my_games 
		WHERE 
		gamesid={$gamesid}  
		AND point={$point} 
		AND win=1 
		AND userid={$this->uid} 
		AND DATE(datetimes)=DATE('{$datetime}') 
		LIMIT 1 ";
		
		$this->logger->log($sql);
		$qData = $this->apps->fetch($sql);
		if(!$qData) return false;
		if(!array_key_exists('total',$qData)) return false;
		if (in_array($gamesid,$this->singlelevelgame)){
			if($qData['total']<1) return true;
		}else{
			if($qData['total']<3) return true;
			// if($qData['total']<=14) return true; /* changes 3 times per games point*/
		}
		
		return false;
	}
	
	
	function checkuserplaygames(){
	
		$datetime = date("Y-m-d H:i:s");
		
		$gamesid = intval($this->apps->_p('gamesid'));
		if(!in_array($gamesid,$this->gamesarrayid)) return false;
		
		$level = intval($this->apps->_p('level'));
		$point = intval($this->pointarr[$level]);
		if(!in_array($point,$this->pointarr)) return false;
		
		$sql ="
			SELECT COUNT(*) total 
			FROM tbl_code_inventory 
			WHERE 
			userid={$this->uid} 
			AND DATE(datetimes)=DATE('{$datetime}')  
			AND histories like '%get from games {$gamesid}%' 
			LIMIT 1";
		
		$this->logger->log($sql);
		$qData = $this->apps->fetch($sql);
		if(!$qData) return false;
		if(!array_key_exists('total',$qData)) return false;
		if (!in_array($gamesid,$this->singlelevelgame)){
			if($qData['total']<3) return true;
			// if($qData['total']<=14) return true; /* changes 3 times per games point*/
		}else{
			if($qData['total']<1) return true;
		}
		
		return false;
	}
	
	
	function checkuserplaygames_old(){
	
		$datetime = date("Y-m-d H:i:s");
		$gamesid = intval($this->apps->_p('gamesid'));
		$qData['total'] = 0;
		
		$sql ="SELECT count(*) total FROM my_games WHERE DATE(datetimes)=DATE('{$datetime}') AND userid={$this->uid} AND gamesid={$gamesid} AND point<>0 LIMIT 1";
		$data = $this->apps->fetch($sql);
		// if(!$qData) return false;
		if($data) $qData = $data;
		// pr($qData);
		if(intval($qData['total']) >= 3){
			return false;
		}else{
			return true;
		}
		return false;
	}
	
	function checkuserplayingthislevel(){
		
		$datetime = date("Y-m-d H:i:s");
		$gamesid = intval($this->apps->_p('gamesid'));
		$level = intval($this->apps->_p('level'));
		$point = intval($this->pointarr[$level]);
		if(!in_array($point,$this->pointarr)) return false;
		
		$sql ="SELECT win FROM my_games WHERE DATE(datetimes)=DATE('{$datetime}') AND userid={$this->uid} AND gamesid={$gamesid} AND point={$point} AND win=1 LIMIT 1";
		$this->logger->log(" check level games : ".$sql);
		$data = $this->apps->fetch($sql);
		$this->logger->log(" check level games result : ".json_encode($data));
		if($data)  return false;
		else return true;
	}
	
	function savetoinventory($win=false,$code=false,$playinggamesid=0){	
		
		if(!$win) return false;
		if(!$code) return false;
		if($playinggamesid==0) return false;
		if(!$this->checkuserplayingthislevel()) return false;
		
		$point = 1;
		$gamesid = intval($this->apps->_p('gamesid'));
		$level = intval($this->apps->_p('level'));
		$gamepoint = intval($this->pointarr[$level]);
		if(!in_array($gamepoint,$this->pointarr)) return false;
				 
		$win = strip_tags($this->apps->_p('win'));
		$this->logger->log(" win : ".$win);
		if($win!="true") return false;
		$this->logger->log($gamesid);
		if(!in_array($gamesid,$this->gamesarrayid)) return false;
		$gamenames =$gamesid;
		 /* userid 	codeid 	codepublicityid 	n_status 	histories */
	
		$datetimes = date("Y-m-d H:i:s");
		
		$sql = " INSERT INTO tbl_code_inventory 
		(userid, codeid, codepublicityid, n_status, histories,datetimes,point) 
		VALUES ({$this->uid},{$code['codeid']},{$code['id']},0,' get from games {$gamenames} ','{$datetimes}','{$point}')";	
		
		$this->logger->log($sql);
		
		$this->apps->query($sql);
		if($this->apps->getLastInsertId()){
			
			$sql = " UPDATE my_games SET win  = 1 WHERE id = {$playinggamesid} AND point = {$gamepoint} AND gamesid ={$gamesid} LIMIT 1";
			$this->apps->query($sql);
			
			
			return true;
		}else return false;
		return false;
	}
	
	function checkpublicexistsinventory(){
		
		
		$data['result'] = false;
		$data['data'] = false;
	
		$gamesid = intval($this->apps->_p('gamesid'));
		if(!in_array($gamesid,$this->gamesarrayid)) return false;
		
		$sql ="
			SELECT * FROM tbl_code_publicity public
			WHERE NOT EXISTS 
			(SELECT * FROM tbl_code_inventory WHERE codepublicityid = public.id AND userid={$this->uid} ) 
			AND channel='games_{$gamesid}' 
			AND n_status = 1 
			LIMIT 1";
		
		$qData = $this->apps->fetch($sql);
					
		if($qData) {
			/* randcode , add proba in here if want to use of fontend */
			$getMasterCodeForRandom = $this->getMasterCodeForRandom(false);
			$randcodeidmekans = $this->apps->contentHelper->randomcodegen($getMasterCodeForRandom);
			$this->logger->log("before : ".$qData['codeid']);
			if($randcodeidmekans!=false) $qData['codeid']=$randcodeidmekans;
			$this->logger->log("after : ".$qData['codeid']);
			$data['result'] = true;
			$data['data'] = $qData;
		
		}
		return $data;
		
	
	}
	
	function generateCode()
	{
	
		$gamesid = intval($this->apps->_p('gamesid'));
		
		if(!in_array($gamesid,$this->gamesarrayid)) return false;
		$location = 'GAMES CODES';
		$channel = "games_{$gamesid}";
		$posteddate = date('Y-m-d H:i:s');
		$expireddate =date('Y-m-d H:i:s');
		$group =  "games_{$gamesid}";

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
			$this->logger->log($sql);
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
	
	function leaderboard_wallbreaker(){
		
		$sql = "SELECT SUM(point) totalpoint, mg.userid, sm.name, sm.last_name FROM my_games AS mg
				LEFT JOIN social_member AS sm ON mg.userid = sm.id
				WHERE mg.gamesid = 2 AND sm.name IS NOT NULL AND sm.name <> ''
				GROUP BY mg.userid
				ORDER BY totalpoint DESC  LIMIT 10";
		$qData = $this->apps->fetch($sql,1);
		
		if($qData){
			$no = 1;
			foreach($qData as $key => $val){
				$qData[$key]['no'] = $no++;
			}
		}
		return $qData;
	}
	
	function leaderboard_maybeninja(){
		
		$sql = "SELECT SUM(point) totalpoint, mg.userid, sm.name, sm.last_name FROM my_games AS mg
				LEFT JOIN social_member AS sm ON mg.userid = sm.id
				WHERE mg.gamesid = 1 AND sm.name IS NOT NULL AND sm.name <> ''
				GROUP BY mg.userid
				ORDER BY totalpoint DESC LIMIT 10";
		$qData = $this->apps->fetch($sql,1);
		// pr($qData);
		if($qData){
			$no = 1;
			foreach($qData as $key => $val){
				$qData[$key]['no'] = $no++;
			}
		}
		return $qData;
	}
	
	function leaderboardgames($gamesid=1){
		
		$sql = "SELECT SUM(point) totalpoint, mg.userid, sm.name, sm.last_name FROM my_games AS mg
				LEFT JOIN social_member AS sm ON mg.userid = sm.id
				WHERE mg.gamesid = {$gamesid} AND sm.name IS NOT NULL AND sm.name <> ''
				GROUP BY mg.userid
				ORDER BY totalpoint DESC LIMIT 10";
		$qData = $this->apps->fetch($sql,1);
		// pr($qData);
		if($qData){
			$no = 1;
			foreach($qData as $key => $val){
				$qData[$key]['no'] = $no++;
			}
		}
		return $qData;
	}
	
	
	function getTodayHiddenCode(){
		
		global $CONFIG;
		
		$user = $this->apps->user;
		$totalPerDayCode = intval($CONFIG['HIDDENCODELIMIT']);
		$datetimes = date("Y-m-d H:i:s");
		// gamesid 3  : is hidden code games
		// get user has been win this games
		$sql = "SELECT COUNT(*) total FROM my_games WHERE gamesid=3 AND userid={$this->uid} AND DATE(datetimes)=DATE('{$datetimes}') GROUP BY gamesid ";
		$qData = $this->apps->fetch($sql);
		// $this->logger->log($sql);
		$this->logger->log($sql);
		$this->logger->log(" get today hidden code: ".json_encode($qData));
		if(!$qData) {
			//get history data user has got hidden code > 50 , must less than 50 times per day
			$sql = "SELECT COUNT(*) total FROM my_games WHERE gamesid=3 AND DATE(datetimes)=DATE('{$datetimes}') GROUP BY gamesid ";
			$qData = $this->apps->fetch($sql);
			// pr($qData);
			$total = 0;
			if($qData)$total = intval($qData['total']);
			
			$this->logger->log(" get hidden code 50 times : ".$sql);
			$this->logger->log(" get hidden code 50 times : ".json_encode($qData));
			if($total<$totalPerDayCode) {
				
				$totalwingames = intval($qData['total']);
				$this->logger->log(" check : ".$totalwingames);
				if($totalwingames<=$totalPerDayCode){
					//get letter hidden code this day
					$sql = " 
					SELECT id, codeid, maskcode FROM  tbl_code_publicity public
					WHERE 
					channel='games hidden code' 
					AND DATE(posted_date)<=DATE('{$datetimes}') 
					AND DATE(expired_date) >= DATE('{$datetimes}') 
					AND n_status = 1 
					AND NOT EXISTS ( SELECT codepublicityid FROM tbl_code_inventory inv WHERE  inv.codepublicityid = public.id AND inv.userid = {$this->uid} )
					ORDER BY rand() 
					LIMIT 1" ;
					// pr($sql);
					$this->logger->log(" query get code public : ".$sql);
					$qData = $this->apps->fetch($sql);
					
						if($qData){
							$this->logger->log($qData['id']);
							// cek apakah user sudah memiliki kode ini sebelumnya
							$sql = "SELECT id FROM  tbl_code_inventory WHERE userid = {$user->id} AND codepublicityid = {$qData['id']} LIMIT 1";
							// pr($sql);
							$result = $this->apps->fetch($sql);
							$this->logger->log($sql);
							$this->logger->log(json_encode($result));
							if(!$result){
								return $qData;
							}
						}
				}
			}
			
		}
		return false;
		
	}
	
	function ValidateHiddenCode_old_flow()
	{
		$date = date('Y-m-d H');
		$dateTime = date('Y-m-d H:i:s');
		
		$getUrl = $this->apps->_request('param');
		// cek tabel log apakah user sudah pernah dapat hiddencode hari ini atau belum
		$parseUrl = unserialize(urldecode64($getUrl));
		// pr($parseUrl)
		$sql = "SELECT id, user_id FROM tbl_activity_log WHERE date_time LIKE '{$date}%' AND action_id = 26 AND user_id = {$this->apps->user->id}";
		$res = $this->apps->fetch($sql);
		// pr($sql);
		if ($res){
			
			if (count($res) > 50){
				if (is_array($parseUrl)){
			
					if ($parseUrl['userid'] == $this->apps->user->id){
					
						if ($parseUrl['token']){
						
							$getMasterCode = $this->getMasterCode();
							if ($getMasterCode){
							
								foreach ($getMasterCode as $value){
									$data[$value['id']] = $value['codename'];
									
								}
							}
							/*
							$sql = "INSERT INTO  tbl_code_inventory (userid, codeid, codepublicityid, n_status, histories)
									VALUES ({$this->apps->user->id}, {$parseUrl['code']['codeid']}, {$parseUrl['code']['id']}, 0, 'hidden code')";
							$res = $this->apps->query($sql);
							
							if ($res){
							
								
								$sql = "INSERT INTO  my_games (gamesid, userid, point, datetimes, n_status)
									VALUES (3, {$this->apps->user->id}, 100, {$dateTime}, 1)";
								$res = $this->apps->query($sql);
								
								$this->apps->log('hiddencode', $this->apps->user->name. ' get a hidden code');
								return $data[$parseUrl['code']['codeid']];
							}else{
								
								return false;
							}
							
							$this->apps->log('hiddencode', $this->apps->user->name. ' get a hidden code');*/
							return $parseUrl['code']['maskcode'];
						}else{
							return false;
						}
						
					}else{
						return false;
					}
					
				}else{
					return false;
				}
				
			}else{
			
				// sudah 50 
				
				return false;
			}
			
		}else{
			
			
			if (is_array($parseUrl)){
			
				if ($parseUrl['userid'] == $this->apps->user->id){
				
					if ($parseUrl['token']){
					
						$getMasterCode = $this->getMasterCode();
						if ($getMasterCode){
						
							foreach ($getMasterCode as $value){
								$data[$value['id']] = $value['codename'];
								
							}
						}
						
						/*
						$sql = "INSERT INTO  tbl_code_inventory (userid, codeid, codepublicityid, n_status, histories)
								VALUES ({$this->apps->user->id}, {$parseUrl['code']['codeid']}, {$parseUrl['code']['id']}, 0, 'hidden code')";
						$res = $this->apps->query($sql);
						// pr($sql);
						
						
						if ($res){
							$sql = "INSERT INTO  my_games (gamesid, userid, point, datetimes, n_status)
									VALUES (3, {$this->apps->user->id}, 100, '{$dateTime}', 1)";
							$res = $this->apps->query($sql);
							// pr($sql);	exit;
							$this->apps->log('hiddencode', $this->apps->user->name. ' get a hidden code');
							return $data[$parseUrl['code']['codeid']];
						}else{
							// echo '4';
							return false;
						}
						
						$this->apps->log('hiddencode', $this->apps->user->name. ' get a hidden code');*/
						return $parseUrl['code']['maskcode'];
					}else{
						// echo '3';
						return false;
					}
					
				}else{
					// echo '2';
					return false;
				}
				
			}else{
				// echo '1';
				return false;
			}
			
		}
	}
	
	
	function ValidateHiddenCode()
	{	
		global $CONFIG, $LOCALE;
			 
		$totalPerDayCode = intval($CONFIG['HIDDENCODELIMIT']);
		$date = date('Y-m-d H');
		$dateTime = date('Y-m-d H:i:s');
		$datetimes =  date('Y-m-d H:i:s');
		$getUrl = $this->apps->_request('param');
		// cek tabel log apakah user sudah pernah dapat hiddencode hari ini atau belum
		$parseUrl = unserialize(urldecode64($getUrl));
		
		// pr($parseUrl)
		$sql = "SELECT id, user_id FROM tbl_activity_log WHERE date_time LIKE '{$date}%' AND action_id = 26 AND user_id = {$this->apps->user->id}";
		$res = $this->apps->fetch($sql);
		// pr($sql);
		// exit;
		// get user has been win this games
		$sql = "SELECT COUNT(*) total FROM my_games WHERE gamesid=3 AND userid={$this->apps->user->id} AND DATE(datetimes)=DATE('{$datetimes}') GROUP BY gamesid ";
		$qData = $this->apps->fetch($sql);
		 
		$this->logger->log($sql);
		 
		if(!$qData) {
		
			//get history data user has got hidden code > 50 , must less than 50 times per day
			$sql = "SELECT COUNT(*) total FROM my_games WHERE gamesid=3 AND DATE(datetimes)=DATE('{$datetimes}') GROUP BY gamesid ";
			$qData = $this->apps->fetch($sql);
			// pr($sql);
			$total = 0;
			if($qData)$total = intval($qData['total']);
			if($total<$totalPerDayCode) {
			
				if ($res){
					// echo 'ada';
					if (count($res) > 50){
						if (is_array($parseUrl)){
					
							if ($parseUrl['userid'] == $this->apps->user->id){
							
								if ($parseUrl['token']){
								
									$getMasterCode = $this->getMasterCode();
									if ($getMasterCode){
									
										foreach ($getMasterCode as $value){
											$data[$value['id']] = $value['codename'];
											$point[$value['id']] = $value['codevalue'];
											
										}
									}
									
									// cek apakah user sudah memiliki kode ini sebelumnya
									$sql = "SELECT id FROM  tbl_code_inventory WHERE userid = {$this->apps->user->id} AND codepublicityid = {$parseUrl['code']['id']} LIMIT 1";
									$result = $this->apps->fetch($sql);
									$this->logger->log($sql);
									if ($result){
										// echo '1';
										return false;
									}
									
									// $saved = $this->savetoinventory($win,$checkCode['data']);
									// if ($saved)
									$sql = "INSERT INTO  tbl_code_inventory (userid, codeid, codepublicityid, n_status, point, histories,datetimes)
											VALUES ({$this->apps->user->id}, {$parseUrl['code']['codeid']}, {$parseUrl['code']['id']}, 0, 1, 'hidden code','{$dateTime}')";
									$res = $this->apps->query($sql);
									
									if($this->apps->getLastInsertId()!=0){
									// if($saved){
									
										
										$sql = "INSERT INTO  my_games (gamesid, userid, point, datetimes, n_status)
											VALUES (3, {$this->apps->user->id}, 1, {$dateTime}, 1)";
										$res = $this->apps->query($sql);
										
										usleep(500);
										
										// $this->apps->log('hiddencode', $this->apps->user->name. ' get a hidden code');
										$this->apps->log('hiddencode', $LOCALE[1]['hiddenpacktextheader']);
										
										// $typeDouble = array('9','11','12');
										// if (in_array($parseUrl['code']['codeid'], $typeDouble)){
											// $letter = $data[$parseUrl['code']['codeid']].'1';
										// }else{
											// $letter = $data[$parseUrl['code']['codeid']];
										// }
										
										$letter = $point[$parseUrl['code']['codeid']];
										return $letter;
										
									}else{
										
										return false;
									}
									
									// $this->apps->log('hiddencode', $this->apps->user->name. ' get a hidden code');
									// return $parseUrl['code']['maskcode'];
								}else{
									return false;
								}
								
							}else{
								return false;
							}
							
						}else{
							return false;
						}
						
					}else{
					
						// sudah 50 
						
						return false;
					}
					
				}else{
					
					
					if (is_array($parseUrl)){
					
						if ($parseUrl['userid'] == $this->apps->user->id){
						
							if ($parseUrl['token']){
							
								$getMasterCode = $this->getMasterCode();
								if ($getMasterCode){
								
									foreach ($getMasterCode as $value){
										$data[$value['id']] = $value['codename'];
										$point[$value['id']] = $value['codevalue'];
									}
								}
								
								// cek apakah user sudah memiliki kode ini sebelumnya
								$sql = "SELECT id FROM  tbl_code_inventory WHERE userid = {$this->apps->user->id} AND codepublicityid = {$parseUrl['code']['id']} LIMIT 1";
								$result = $this->apps->fetch($sql);
								$this->logger->log($sql);
								if ($result){
									// echo '1';
									return false;
								}
								
								
								$sql = "INSERT INTO  tbl_code_inventory (userid, codeid, codepublicityid, n_status, point, histories,datetimes)
										VALUES ({$this->apps->user->id}, {$parseUrl['code']['codeid']}, {$parseUrl['code']['id']}, 0, 1, 'hidden code','{$dateTime}')";
								$res = $this->apps->query($sql);
								// pr($sql);
								
								
								if($this->apps->getLastInsertId()!=0){
									$sql = "INSERT INTO  my_games (gamesid, userid, point, datetimes, n_status)
											VALUES (3, {$this->apps->user->id}, 1, '{$dateTime}', 1)";
									$res = $this->apps->query($sql);
									// pr($sql);	exit;
									
									usleep(500);
									// $this->apps->log('hiddencode', $this->apps->user->name. ' get a hidden code');
									$this->apps->log('hiddencode', $LOCALE[1]['hiddenpacktextheader']);
									// $typeDouble = array('9','11','12');
									// if (in_array($parseUrl['code']['codeid'], $typeDouble)){
										// $letter = $data[$parseUrl['code']['codeid']].'1';
									// }else{
										// $letter = $data[$parseUrl['code']['codeid']];
									// }
									
									$letter = $point[$parseUrl['code']['codeid']];
									
									return $letter;
									
								}else{
									// echo '4';
									return false;
								}
								
								// $this->apps->log('hiddencode', $this->apps->user->name. ' get a hidden code');
								// return $parseUrl['code']['maskcode'];
							}else{
								// echo '3';
								return false;
							}
							
						}else{
							// echo '2';
							return false;
						}
						
					}else{
						// echo '1';
						return false;
					}
					
				}
			}
		}
	}
	
	
	function getMasterCode()
	{
		$sql = "SELECT id, codename, codevalue FROM tbl_code_detail WHERE n_status = 1";
		$result = $this->apps->fetch($sql,1);
		if ($result){
			foreach ($result as $value){
				$data[$value['id']] = $value['codename'];
				// $dataID[] = $value['id'];
				// $codeName[] = $value['codename'];
			}
			
			return $result;
		}else{
			return false;
		}
	}
	
	function getMasterCodeForRandom($usingdefinition=false)
	{
		global $CONFIG;
		if($usingdefinition) {
			$qidcode = " AND id IN  (1,2,3,6,9,10,11,12,4) ";
			$qselect = "  id, 0 codetype,	codename 	,codevalue, 	prob, 	n_status  ";
		}else { 		
			if($CONFIG['usingelusive']) $qidcode = "";
			else $qidcode = " AND codetype = 0 ";
		 	$qselect = " * ";
		}
		$sql = "
		SELECT {$qselect}
		FROM tbl_code_detail 
		WHERE 
		n_status = 1 
		{$qidcode}
		 ";
		
		$result = $this->apps->fetch($sql,1);
		if ($result){
		 $this->logger->log(json_encode($result));
			return $result;
		}else{
			return false;
		}
	}
	
	
	
}

?>

