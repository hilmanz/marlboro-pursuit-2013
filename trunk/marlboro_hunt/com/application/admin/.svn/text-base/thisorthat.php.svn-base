<?php

global $ENGINE_PATH;
include_once $ENGINE_PATH."Utility/Paginate.php";
		
class thisorthat extends Admin{
	var $category;
	var $type;
	function __construct(){	
		parent::__construct();	
		
		$this->type = "1,3,4,5,6";
		$this->contentType = "0";
		$this->articleType = "4";
		$this->folder =  'thisorthat';
		$this->dbclass = 'marlborohunt';
		$this->fromwho = 0; // 0 is admin/backend
		$this->total_per_page = 20;
		
	}
	
	function admin(){
		
		global $CONFIG;
	
		//get admin role
		foreach($this->roler as $key => $val){
		$this->View->assign($key,$val);
		}
		//get specified admin role if true
		if($this->specified_role){
			foreach($this->specified_role as $val){
				$type[] = $val['type'];
				$category[] = $val['category'];
			}
			if($type) $this->type = implode(',',$type);
			else return false;
			if($category) $this->category = implode(',',$category);
			else return false;
		}
		//helper
	
		$this->View->assign('folder',$this->folder);
		$this->View->assign('basedomain',$CONFIG['BASE_DOMAIN']);
		$this->View->assign('baseurl',$CONFIG['BASE_DOMAIN_PATH']);
		$act = $this->_g('act');
		if($act){
			return $this->$act();
		} else {
			return $this->home();
		}
	}
	
	function home (){

		//filter box
		$filter = "";
		$search = $this->_g("search") == NULL ? '' : $this->_g("search");
		$startdate = $this->_g("startdate") == NULL ? '' : $this->_g("startdate");
		$enddate = $this->_g("enddate") == NULL ? '' : $this->_g("enddate");
		$nstatus = (string) $this->_g("nstatus") == NULL ? '' : (string) $this->_g("nstatus");
		$filter .= $startdate=='' ? "" : "AND DATE(ncr.created_date) >= DATE('{$startdate}') ";
		$filter .= $enddate=='' ? "" : "AND DATE(ncr.created_date) <= DATE('{$enddate}') ";		
		$filter .= $search=='' ? "" : "AND (sm.name LIKE '%{$search}%' OR sm.last_name LIKE '%{$search}%' OR sm.email LIKE '%{$search}%')";
		$filter .= $nstatus=='' ? "" : "AND ncr.n_status='{$nstatus}' ";
		
		$this->View->assign('search',$search);
		$this->View->assign('nstatus',$nstatus);
		$this->View->assign('startdate',$startdate);
		$this->View->assign('enddate',$enddate);	
				
		$start = intval($this->_g('st'));
		
		/* Hitung banyak record data */
		$sql = "SELECT COUNT(*) total 
				FROM {$this->dbclass}_news_content_repo ncr
				LEFT JOIN {$this->dbclass}_news_content mnc ON ncr.otherid = mnc.id
				LEFT JOIN social_member sm ON ncr.userid = sm.id
				WHERE mnc.articleType = {$this->articleType} AND sm.name IS NOT NULL and sm.name <> '' {$filter}
				ORDER BY ncr.created_date DESC";
		$totalList = $this->fetch($sql);	
		if($totalList){
		$total = $totalList['total'];
		}else $total = 0;
		// pr($total);
		
		
		/* list article */
		 // DATE_FORMAT(a.datetime,'%Y-%m-%d') 
		$sql = "SELECT ncr . * , sm.name, sm.last_name, sm.email, mnc.tags,mnc.articleType
				FROM {$this->dbclass}_news_content_repo ncr
				LEFT JOIN {$this->dbclass}_news_content mnc ON ncr.otherid = mnc.id
				LEFT JOIN social_member sm ON ncr.userid = sm.id
				WHERE mnc.articleType = {$this->articleType} AND sm.name IS NOT NULL and sm.name <> '' {$filter}
				ORDER BY ncr.created_date DESC LIMIT {$start},{$this->total_per_page}";		
		$list = $this->fetch($sql,1);
		// pr($sql);
		if($list){
				
			$n=$start+1;
			foreach($list as $key => $val){
					$arrtags = unserialize($val['tags']);
					// pr($arrtags);exit;
					$list[$key]['themes'] = $arrtags[$val['typealbum']-2]['button'];
					$list[$key]['no'] = $n++;
					
			}
			
		}			
		// pr($list);
		$this->View->assign('list',$list);
		$this->Paging = new Paginate();
	
		$this->View->assign("paging",$this->Paging->getAdminPaging($start, $this->total_per_page, $total, "?s={$this->folder}&startdate={$startdate}&enddate={$enddate}&nstatus={$nstatus}"));	
		// pr("application/admin/{$this->folder}/{$this->folder}_list.html");
		return $this->View->toString("application/admin/{$this->folder}/{$this->folder}_list.html");
	}
	
	function ajax(){
		global $LOCALE;	
		$id = $this->_p('userid');
		$n_status = $this->_p('n_status');
		$taskid = $this->_p('taskid');
		$typealbum = $this->_p('typealbum');
		$idrepo = $this->_p('idrepo');
		
		// pr($n_status);
			
		// $sqlcheck = "SELECT COUNT(*) total FROM marlborohunt_news_content_repo 
						// WHERE MONTH(winning_date) = MONTH(NOW()) AND n_status = 2
						// AND userid = {$id} AND otherid = {$taskid} AND typealbum = {$typealbum}";
		// $result = $this->fetch($sqlcheck);
		// pr($sqlcheck);
		// if ($result['total']<=4){
		
		
			$sql = "UPDATE {$this->dbclass}_news_content_repo SET n_status = {$n_status} WHERE userid = {$id} AND otherid={$taskid} AND typealbum={$typealbum} AND id={$idrepo} LIMIT 1";
					
			$qData = $this->query($sql);
			// pr($qData);
			if($qData){
				
				if ($n_status == 2){
					// give 2 point to user
					
					
					$setData = $this->giveLetterWhenVerified($id, $taskid, 2, $typealbum, 'verified_'.date('YmdHis'));
					
					// if ($setData) 
					print json_encode(array('status'=>true, 'point'=>true));
					// else print json_encode(array('status'=>true, 'point'=>false));
					// sleep(1);
					// $this->log->sendUserActivity($id, 'account', $LOCALE[1]['thisorthatwinner']);
					// exit;
					
				}
				if ($n_status == 3){
					sleep(1);
					$this->log->sendUserActivity($id, 'account', $LOCALE[1]['thisorthatrejected']);
					
					print json_encode(array('status'=>true));
				}
				
				// print json_encode(array('status'=>true));
			}else{
				print json_encode(array('status'=>false));
			}
		// }else{
			// print json_encode(array('status'=>false));
		// }
			
			exit;
		
		
	}
	
	
	function ajaxWinnerThisorthat(){
		global $LOCALE;	
		$id = $this->_p('userid');
		$n_status = $this->_p('n_status');
		$taskid = $this->_p('taskid');
		$typealbum = $this->_p('typealbum');
		$idrepo = $this->_p('idrepo');
		$date = date('Y-m-d H:i:s');
		// pr($n_status);
		// check if verified
			$sql = "SELECT n_status FROM {$this->dbclass}_news_content_repo WHERE id = {$idrepo} LIMIT 1";
			$res = $this->fetch($sql);
			if ($res['n_status']==2){
				
				$sqlcheck = " SELECT COUNT(*) total FROM marlborohunt_news_content_repo 
							WHERE MONTH(winning_date) = MONTH(NOW()) AND n_status = 4 typealbum = {$typealbum} 
							AND otherid = {$taskid}";
				// pr($sqlcheck);
				$result = $this->fetch($sqlcheck);
				if ($result['total']>2){
				
					print json_encode(array('status'=>false));
				
				}else{
					
					$sql = "UPDATE {$this->dbclass}_news_content_repo SET winning_date = '{$date}', n_status = 4 
							WHERE userid = {$id} AND otherid={$taskid} AND typealbum={$typealbum} AND id={$idrepo} LIMIT 1";
						
					$qData = $this->query($sql);
					// pr($qData);
					if($qData){
						
						// give 10 point to user
						$setData = $this->giveLetterWhenVerified($id, $taskid, 10, $typealbum, 'winner this or that');
						
						if ($setData) print json_encode(array('status'=>true,'point'=>true));
						else print json_encode(array('status'=>false));
						// usleep(100);
						// $this->log->sendUserActivity($id, 'account', $LOCALE[1]['thisorthatwinner']);
					
						
					}else{
						print json_encode(array('status'=>false));
					}
				}
			}else{
				print json_encode(array('status'=>false));
			}
			
			
			
			exit;
		
		
	}
	
	
	function completetask($taskID=false, $param=false, $theme=null, $userid, $point){
			global $LOCALE;	
				if(!$taskID)$taskID = $this->_p('taskid');
				
				$id = $userid;
				
				$user = "SELECT name FROM social_member WHERE id = {$id}";
				 // pr($sql);
				$qDataUser = $this->fetch($user);
				
				$task = "SELECT title FROM {$this->dbclass}_news_content WHERE id = {$taskID}";
				 // pr($sql);
				$qData = $this->fetch($task);
		
				$date = date('Y-m-d H:i:s');
				
				if ($param){
				
					$sql = "INSERT INTO my_task (userid, taskid, taskdate, n_status)
							VALUES ({$id}, {$taskID}, '{$date}', 1)";
					$hasil = $this->query($sql);
					sleep(1);
					// echo 'pre log';
					// $this->log->sendActivity($LOCALE[1]['thisorthatverified'].' and you got a letter '.$letter);
					// $this->log->sendUserActivity($id, 'account', $LOCALE[1]['thisorthatverified'].' and you got a letter "'.$letter.'"');
					// $this->log->sendUserActivity($id, 'account', " Task ". $qData['title']." verified and ".$qDataUser['name'] ." got a letter \"$letter\"");
					if ($point==2)$text = $LOCALE[1]['taskverified'];
					if ($point==10)$text = $LOCALE[1]['thisorthatwinner'];
					$this->log->sendUserActivity($id, 'account', " {$text} {$theme}! you get {$point} points");
					// echo 'after log';
					sleep(1);
					// $this->log->sendUserActivity($id, 'accompalished', $LOCALE[1]['thisorthatverified'].' and you got a letter "'.$letter.'"');
					// $this->log->sendUserActivity($id, 'accompalished', $LOCALE[1]['thisorthatverified']);
					$this->log->sendUserActivity($id, 'accompalished', $qData['title']);
				}else{
					$this->log->sendUserActivity($id, 'account',$LOCALE[1]['thisorthatrejected']);
					sleep(1);
					// $this->log->sendUserActivity($id, 'accompalished', $LOCALE[1]['thisorthatverified'].' and you got a letter "'.$letter.'"');
					// $this->log->sendUserActivity($id, 'accompalished', $LOCALE[1]['thisorthatverified']);
					$this->log->sendUserActivity($id, 'accompalished', $qData['title']);
				
				}
				return true;
	}
	
	function giveLetterWhenVerified($userid=0, $taskid=0, $loop, $typealbum, $param=null)
	{
		global $LOCALE;
		
		if ($userid ==0) return false;
		if ($taskid ==0) return false;
		// echo 'ada';
		$result =  $this->setLetterWhenVerified($userid, $taskid, $loop, $typealbum, $param);
		// pr($result);
		
		if ($result){
			if($result['status']){
				// echo 'ada';
				$this->completetask($taskid, true, $result['theme'], $userid, $loop);
				
				return true;
			}
		}
		// echo 'false';
		return false;
		
	}
	
	function getThemeThisorthat($taskid)
	{
		if ($taskid==null) return false;
		
		$sql = "SELECT tags FROM marlborohunt_news_content WHERE articleType = 4 AND id = {$taskid} ORDER BY created_date DESC LIMIT 1";
		$res = $this->fetch($sql);
		if ($res) return $res;
		
		return false;
	}
	
	function setLetterWhenVerified($userid=0, $taskid=0, $loopcount=0, $typealbum, $param=null)
	{
		$checkCode = false;
		$mytoken = false;
		$usertype = array(0,1,2);
		$gamesarrayid = array(1,2);
		
		if($taskid==null) return false;
		
		$getTheme = $this->getThemeThisorthat($taskid);
		// pr($getTheme);
		// pr(unserialize($getTheme['tags']));
		
		if (!$getTheme) return false;
		
		$theme = unserialize($getTheme['tags']);
		// exit;
		
		$arrTypeAlbum = array('2'=>$theme[0]['button'], '3'=>$theme[1]['button']);
		
		
		$token = strip_tags($this->_p('token'));
		$salt = "gameapihelper";
		// if(!$token) return false;
		/* token matching with*/
		
		$this->uid = $userid;
		
		$mytoken = sha1($this->uid.date("YmdHi")."true{".$salt."}");
	
	
		/* validation */
	
		if($this->uid==0) return false;		
		// if($token!=$mytoken) return false;
		
		/* check this user has got existing codes */
		
		$loop = intval($loopcount);
		// echo 'before loop';
		if ($loop==0) return false;
		// echo 'after loop1';	
		// echo $loop;
		// for ($i=1; $i<=$loop; $i++){
			$chaneltext = $param; 
			$usertypeletter ="thisorthatcodepoint_$chaneltext".$arrTypeAlbum[$typealbum];
			// echo 'before first login code';	
			$checkgotfirstlogincode  = $this->checkgotthisorthatverified($usertypeletter, $this->uid);		
			if($checkgotfirstlogincode) return false;
			// echo 'after first login code';	
			$checkCode = $this->checkpublicexistsinventory($usertypeletter, $this->uid);
			// echo 'b';
			if(!$checkCode) return false;
		// echo 'after loop';	
			// pr($checkCode);
			if($checkCode['result']){
			
						/* save to inventory user if  */
						// echo 'save inv1';
						$saved = $this->savetoinventory(true,$checkCode['data'],$usertypeletter, $this->uid, 1, $loopcount);
						
						if($saved) {
							// echo 'ada';
								return array('status'=>true, 'theme'=>$arrTypeAlbum[$typealbum]);
							
						}
				
			}else{
			
				$checkCode = false;
				/* if not found code in publicity code, create 1 code for this user */
				// echo 'c';
				$firstcreatecode = $this->generateCode("THIS OR THAT LETTER ".$arrTypeAlbum[$typealbum],$usertypeletter);
				if(!$firstcreatecode) return false;		
				// echo 'd';	
				$checkCode = $this->checkpublicexistsinventory($usertypeletter, $this->uid);
				if(!$checkCode) return false;
					
				if($checkCode['result']){
				
						/* save to inventory user   */
				
								// echo 'save inv2';
						$saved = $this->savetoinventory(true,$checkCode['data'],$usertypeletter, $this->uid, 1, $loopcount);
						// pr($saved);
						if($saved){
						// echo 'ada1';
							return array('status'=>true, 'theme'=>$arrTypeAlbum[$typealbum]);
							
							
						}else{
							// echo 'g';
							return array('status'=>false);
						}
						
						
				
				}else {
					// echo 'h';
					return false;
				}
			}
		
		// }
		
		// echo 'i';
		return false;
	}
	
	function checkuserisexisting(){
		$sql ="SELECT usertype FROM social_member WHERE id={$this->uid} LIMIT 1";
		$qData = $this->fetch($sql);
		
	
		if($qData) {
			return $qData;
		
		}
		return false;
	
	}
	
	function checkgotthisorthatverified($channel='games20th', $userid=0){
	
		if ($userid ==0) return false;
		$sql ="
			SELECT COUNT(*) total FROM tbl_code_publicity public
			WHERE EXISTS 
			(SELECT * FROM tbl_code_inventory WHERE codepublicityid = public.id AND userid={$userid} ) 
			AND channel='{$channel}' 
			AND n_status = 1 
			LIMIT 1";
		
		// pr($sql);
		$qData = $this->fetch($sql);
		
		if($qData) {
			if($channel=='existinguserletter') $howmany = 1;
			else $howmany = 0;
			// pr($qData);
			// pr($channel);
			// pr($howmany);
			if($qData['total']>$howmany) 	{
			// echo 'ada';
				return true;
			}else return false;
		
		}
		return false;
	
	
	}
	
	function checkpublicexistsinventory($channel='games20th', $userid=0){
		
		if ($userid ==0) return false;
		$data['result'] = false;
		$data['data'] = false;
		
		$sql ="
			SELECT * FROM tbl_code_publicity public
			WHERE NOT EXISTS 
			(SELECT * FROM tbl_code_inventory WHERE codepublicityid = public.id AND userid={$userid} ) 
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
	
	function savetoinventory($win=false,$code=false,$gamenames="20th",$userid, $howmany=1, $loopcount=0){
		
		if(!$win) return false;
		if(!$code) return false;
		if(!$userid) return false;
		
		
		 /* userid 	codeid 	codepublicityid 	n_status 	histories */
		
		$sql = " INSERT INTO tbl_code_inventory 
		(userid, codeid, codepublicityid, n_status, point, histories) 
		VALUES ({$userid},{$code['codeid']},{$code['id']},0,{$loopcount}, ' get from {$gamenames} ')";		
		// pr($sql);
		$this->query($sql);
		if($this->getLastInsertId()){
		
			// $getLetter = $this->checkmyletter($code['codeid']);
			// if ($getLetter) 
			return true;
			// return false;
		}else return false;
		return false;
	}
	
	function generateCode($codename="20TH LOGIN CODE",$channel="games20th")
	{
	
	
		$location = $codename;
		$channel = $channel;
		$posteddate = date('Y-m-d H:i:s');
		$expireddate =date('Y-m-d H:i:s');
		$group = $channel;

		// $sql = "SELECT * FROM tbl_code_detail WHERE n_status = 1 AND  codetype <> 1 ";
		$sql = "SELECT * FROM tbl_code_detail WHERE n_status = 1  ";
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
	
	function checkmyletter($id=0)
	{
		$sql = "SELECT * FROM tbl_code_detail WHERE n_status = 1 AND  codetype <> 1 AND id={$id} ";
		
		$res = $this->fetch($sql);
		if ($res) return $res['codename'];
		
		return false;
	}
	
	function getreport(){
		
		$filename = "thisorthat_report_".date('Ymd_gia').".xls";
		header("Content-Type:   application/vnd.ms-excel; charset=utf-8");
		header("Content-Disposition: attachment; filename=$filename");  //File name extension was wrong
		header("Expires: 0");
		header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
		header("Cache-Control: private",false);
		// echo "Some Text"; //no ending ; here
		$resReport = $this->downloadreport_old();
		$this->log->sendActivity("user printing thisorthat report");
		// pr($resReport);
		echo "<table border='1'>";
		echo "<tr>";
			echo "<th class='head0'>No</th>";
			echo "<th class='head1'>Name</th>";
			echo "<th class='head1'>Email</th>";
			echo "<th class='head1'>Date</th>";
			echo "<th class='head1'>Theme Name</th>";
			// echo "<th class='head1'>Submission</th>";
			echo "<th class='head1'>Status</th>";
		echo "</tr>";
		foreach ($resReport as $key => $val){			
			echo "<tr>";
				echo "<td>$val[no]</td>";
				echo "<td>$val[name] $val[last_name]</td>";
				echo "<td>$val[email]</td>";
				echo "<td>$val[created_date]</td>";
				echo "<td>$val[themes]</td>";
				// echo "<td>$val[total]</td>";
				
				if($val['n_status'] == 1) $status = 'UnVerified';
				if($val['n_status'] == 2) $status = 'Verified';
				if($val['n_status'] == 3) $status = 'Rejected';
				
				echo "<td>$status</td>";
			echo "</tr>";
		}
		echo "</table>";
		exit;
	}
	
	function downloadreport_old(){
		//filter box
		$filter = "";
		$search = $this->_g("search") == NULL ? '' : $this->_g("search");
		$startdate = $this->_g("startdate") == NULL ? '' : $this->_g("startdate");
		$enddate = $this->_g("enddate") == NULL ? '' : $this->_g("enddate");
		$nstatus = (string) $this->_g("nstatus") == NULL ? '' : (string) $this->_g("nstatus");
		$filter .= $startdate=='' ? "" : "AND DATE(ncr.created_date) >= DATE('{$startdate}') ";
		$filter .= $enddate=='' ? "" : "AND DATE(ncr.created_date) <= DATE('{$enddate}') ";		
		$filter .= $search=='' ? "" : "AND (sm.name LIKE '%{$search}%' OR sm.last_name LIKE '%{$search}%' OR sm.email LIKE '%{$search}%')";
		if ($nstatus !== '0'){
			$filter .= $nstatus=='' ? "" : "AND ncr.n_status='{$nstatus}' ";
		}
		
				
		
		/* list article */
		 // DATE_FORMAT(a.datetime,'%Y-%m-%d') 
		$sql = "SELECT ncr . * , sm.name, sm.last_name, sm.email, mnc.tags,mnc.articleType
				FROM {$this->dbclass}_news_content_repo ncr
				LEFT JOIN {$this->dbclass}_news_content mnc ON ncr.otherid = mnc.id
				LEFT JOIN social_member sm ON ncr.userid = sm.id
				WHERE mnc.articleType = {$this->articleType} AND sm.name IS NOT NULL and sm.name <> '' {$filter}
				ORDER BY ncr.created_date DESC ";		
		$list = $this->fetch($sql,1);
		// pr($sql);exit;
		if($list){
				
			$n=$start+1;
			foreach($list as $key => $val){
					$arrtags = unserialize($val['tags']);
					// pr($arrtags);exit;
					$list[$key]['themes'] = $arrtags[$val['typealbum']-2]['button'];
					$list[$key]['no'] = $n++;
					
			}
			
		}
		
		return $list;
	}	
	
	
}