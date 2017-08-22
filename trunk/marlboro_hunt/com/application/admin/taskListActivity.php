<?php

global $ENGINE_PATH;  
include_once $ENGINE_PATH."Utility/Paginate.php";
		
class taskListActivity extends Admin{
	var $category;
	var $type;
	function __construct(){	
		parent::__construct();	
		
		$this->type = "3, 6, 17, 18, 19, 20, 21,22";
		$this->contentType = "1";
		$this->folder =  'taskListActivity';
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
		$this->typelist = $this->getTypeList();
		// $this->contributor = $this->getContributor();
		// $this->View->assign('contributor',$this->contributor);
		$this->View->assign('typelist',$this->typelist);
		$this->View->assign('folder',$this->folder);
		
		$this->View->assign('baseurl',$CONFIG['BASE_DOMAIN_PATH']);
		$act = $this->_g('act');
		if($act){
			return $this->$act();
		} else {
			return $this->home();
		}
	}

	function home(){
		global $CONFIG;
		//filter box
		$filter = "";
		$filtersub = "";
		$filtercode = "";
		$search = $this->_g("search") == NULL ? '' : $this->_g("search");
		$article_type = $this->_g("article_type") == NULL ? '0' : $this->_g("article_type");
		$startdate = $this->_g("startdate") == NULL ? '' : $this->_g("startdate");
		$enddate = $this->_g("enddate") == NULL ? '' : $this->_g("enddate");
		$nstatus = (string) $this->_g("nstatus") == NULL ? '0' : (string) $this->_g("nstatus");
		
		$filter .= $startdate=='' ? "" : "AND DATE(ncr.created_date) >= DATE('{$startdate}') ";
		$filtersub .= $startdate=='' ? "" : "AND DATE(games.datetimes) >= DATE('{$startdate}') ";
		$filtercode .= $startdate=='' ? "" : "AND DATE(inv.datetimes) >= DATE('{$startdate}') ";
		
		$filter .= $enddate=='' ? "" : "AND DATE(ncr.created_date) <= DATE('{$enddate}') ";		
		$filtersub .= $enddate=='' ? "" : "AND DATE(games.datetimes) <= DATE('{$enddate}') ";		
		$filtercode .= $enddate=='' ? "" : "AND DATE(inv.datetimes) <= DATE('{$enddate}') ";		
		
		$filter .= $search=='' ? "" : "AND (mnc.title LIKE '%{$search}%' OR mnc.brief LIKE '%{$search}%' OR mnc.content LIKE '%{$search}%') ";
		$filtersub .= $search=='' ? "" : "AND (sm.name LIKE '%{$search}%' OR sm.last_name LIKE '%{$search}%' OR sm.email LIKE '%{$search}%') ";
		$filtercode .= $search=='' ? "" : "AND (sm.name LIKE '%{$search}%' OR sm.last_name LIKE '%{$search}%' OR sm.email LIKE '%{$search}%') ";
		
		$filter .= $nstatus=='0' ? "" : "AND ncr.n_status='{$nstatus}' ";
		$filtersub .= $nstatus=='0' ? "" : "AND  games.n_status='100' ";
		$filtercode .= $nstatus=='0' ? "" : "AND  inv.n_status='100'  ";
		
		$this->View->assign('search',$search);
		$this->View->assign('article_type',$article_type);
		$this->View->assign('nstatus',$nstatus);
		$this->View->assign('startdate',$startdate);
		$this->View->assign('enddate',$enddate);
		
		$artType = explode(',',$this->type);
		if ($article_type!='0') {
			if(in_array($article_type,$artType)){ 
				$filter .= $article_type=='0' ? "" : "AND mnc.articleType='{$article_type}'";
					if($article_type==22||$article_type==6){
						$gamesid = 1;
						if($article_type==22) $gamesid = 2;
						$filtersub .= $article_type=='0' ? "" : "AND games.gamesid='{$gamesid}'";
					}else $filtersub .=" AND games.gamesid='10' ";
					
					if($article_type!=18)$filtercode .=" AND cp.n_status='100' ";
			}
			else $filter .= "AND mnc.articleType IN ({$article_type}) ";
		}
		
		$start = intval($this->_g('st'));
		
		/* Hitung banyak record data */
		$sql ="
				(
				SELECT ncr.created_date , sm.name, sm.last_name, sm.email, mnc.title,mnc.articleType
				FROM {$this->dbclass}_news_content_repo ncr
				LEFT JOIN {$this->dbclass}_news_content mnc ON ncr.otherid = mnc.id
				LEFT JOIN social_member sm ON ncr.userid = sm.id
				WHERE mnc.articleType  IN ( {$this->type} ) {$filter}
				)
				UNION
				(
				SELECT  games.datetimes created_date, sm.name, sm.last_name, sm.email, IF (games.gamesid=1,'CROSS OUT','WALL BREAKER') title, IF (games.gamesid=1,6,22) articleType
				FROM my_games games
				LEFT JOIN social_member sm ON games.userid = sm.id
				WHERE games.point = 20   {$filtersub}
				GROUP BY games.userid,games.gamesid
				)
				UNION
				(
				SELECT  inv.datetimes created_date, sm.name, sm.last_name, sm.email, cp.channel title, 18 articleType
				FROM tbl_code_inventory  inv
				LEFT JOIN tbl_code_publicity cp  ON cp.id = inv.codepublicityid 
				LEFT JOIN social_member sm ON inv.userid = sm.id
				WHERE  cp.channel='games hidden code' {$filtercode}
				)
				
			";
		$totalList = $this->fetch($sql,1);	
		// pr($sql);
		if($totalList){
		$total = count($totalList);
		}else $total = 0;
		
		/* list article */

		$sql = "
				(
				SELECT ncr.id as id, ncr.created_date , sm.name, sm.last_name, sm.email, mnc.title,mnc.articleType, ncr.n_status, ncr.userid, mnc.id as taskid, 1 usedverified, ncr.files img
				FROM {$this->dbclass}_news_content_repo ncr
				LEFT JOIN {$this->dbclass}_news_content mnc ON ncr.otherid = mnc.id
				LEFT JOIN social_member sm ON ncr.userid = sm.id
				WHERE mnc.articleType  IN ( {$this->type} ) {$filter}
				)
				UNION
				(
				SELECT games.id as id,  games.datetimes created_date, sm.name, sm.last_name, sm.email, IF (games.gamesid=1,'CROSS OUT','WALL BREAKER') title, IF (games.gamesid=1,6,22)  articleType,games.n_status, games.userid, games.id AS taskid, 0 usedverified, '' img
				FROM my_games games
				LEFT JOIN social_member sm ON games.userid = sm.id
				WHERE games.point = 20  {$filtersub}
				GROUP BY games.userid,games.gamesid
				)
				UNION
				(
				SELECT  inv.id as id, inv.datetimes created_date, sm.name, sm.last_name, sm.email, cp.channel title, 18 articleType,inv.n_status, inv.userid, cp.id AS taskid, 0 usedverified, '' img
				FROM tbl_code_inventory  inv
				LEFT JOIN tbl_code_publicity cp  ON cp.id = inv.codepublicityid 
				LEFT JOIN social_member sm ON inv.userid = sm.id
				WHERE cp.channel='games hidden code' {$filtercode}
				)
				ORDER BY created_date DESC LIMIT {$start},{$this->total_per_page}
		";
/*
		$sql = "(
				SELECT con.id AS id, con.title AS title, con.posted_date AS date, repo.files AS img, con.n_status AS n_status, con.articleType AS articleType, sm.id AS userid, sm.name AS name, sm.last_name AS last_name, sm.email AS email, mt.id AS taskid
				FROM my_task mt
				LEFT JOIN {$this->dbclass}_news_content con ON con.id = mt.taskid
				LEFT JOIN social_member sm ON mt.userid = sm.id
				LEFT JOIN {$this->dbclass}_news_content_repo repo ON con.id = repo.otherid
				WHERE con.articleType
				IN ( 3, 6, 17, 18, 19, 20, 21 )
				)
				UNION (

				SELECT log.id AS id, log.action_value AS title, log.date_time AS date, ''img, ''n_status, ''articleType, user.id AS userid, user.name AS name, user.last_name AS last_name, user.email AS email, ''taskid
				FROM tbl_activity_log AS log
				LEFT JOIN social_member AS user ON log.user_id = user.id
				WHERE log.action_id =26
				)
				ORDER BY date DESC
				LIMIT {$start},{$this->total_per_page}";
*/
		$list = $this->fetch($sql,1);
		// pr($sql);
		if($list){
				
			$n=$start+1;
			foreach($list as $key => $val){
					$list[$key]['paths'] = false;
					// pr($CONFIG['LOCAL_PUBLIC_ASSET']."thisorthat/".$val['img']);
					if(is_file($CONFIG['LOCAL_PUBLIC_ASSET']."thisorthat/".$val['img'])) $list[$key]['paths'] = "thisorthat";
					if(is_file($CONFIG['LOCAL_PUBLIC_ASSET']."taskListManagement/".$val['img'])) $list[$key]['paths'] = "taskListManagement";
					$list[$key]['no'] = $n++;
					
			}
			
		
			
		}
		
			
		// pr($list);
		$this->View->assign('list',$list);

		$this->Paging = new Paginate();
	
		$this->View->assign("paging",$this->Paging->getAdminPaging($start, $this->total_per_page, $total, "?s={$this->folder}&article_type={$article_type}&startdate={$startdate}&enddate={$enddate}&nstatus={$nstatus}"));	
		// pr("application/admin/{$this->folder}/{$this->folder}_list.html");
		return $this->View->toString("application/admin/{$this->folder}/{$this->folder}_list.html");
	}
	
	
	function add(){	
		global $CONFIG;
		
		$authorid				= intval($this->_p("authorid"));
		if($authorid==0)  $data['authorid'] 		= $this->Session->getVariable("uid");
		else $data['authorid'] = $authorid;
		
		$data['title'] 			= $this->_p('title');
		$data['tags'] 			= $this->_p('tags');
		$data['topcontent'] 	= $this->_p('topcontent');
		$data['brief'] 			= $this->_p('brief');
		$content				= $this->_p('content');
		$data['content'] 	  	= $this->fixTinyEditor( $content );
		$data['url'] 			= $this->_p('url');
		$data['sourceurl'] 		= $this->_p('sourceurl');
		if($this->roler['approver']) $data['n_status'] = $this->_p('n_status');
		else $data['n_status'] 	 = 0;
		$data['posted_date'] 	= $this->_p('posted_date');
		$data['expired_date'] 	= $this->_p('expired_date');
		$data['articleType']	= $this->_p('articleType');
		foreach($data as $key => $val){
			$this->View->assign($key,$val);
		}
		if($this->_p('simpan')){		
			foreach($data as $key => $val){
				$$key= $val;
			}
			if( $title=='' ){
				$this->View->assign('msg',"Please complete the form!");
				return $this->View->toString("application/admin/{$this->folder}/{$this->folder}_new.html");
			}
			if($tags){
				$tags = serialize(explode(',',$tags));
			}
			$sql = "INSERT INTO {$this->dbclass}_news_content (title,brief,content,articleType,url,sourceurl,n_status,created_date,posted_date,expired_date,tags,topcontent,authorid,fromwho) 
			VALUES ('{$title}','{$brief}',\"{$content}\",'{$articleType}','{$url}','{$sourceurl}','{$n_status}',NOW(),'{$posted_date}','{$expired_date}','{$tags}','{$topcontent}','{$authorid}','{$this->fromwho}')";
			$this->query($sql);
			// pr($sql);
			$last_id = $this->getLastInsertId();
			if(!$last_id){
				$this->View->assign("msg","Add process failure");
				return $this->View->toString("application/admin/{$this->folder}/{$this->folder}_new.html");
			}else{
				//create Image function
				$this->createImage($last_id);
				
				$this->log->sendActivity("add {$this->folder}",$last_id);
				return $this->View->showMessage("Success Create {$this->folder} ", "index.php?s={$this->folder}");
			}
		}
		return $this->View->toString("application/admin/{$this->folder}/{$this->folder}_new.html");
	}
	
	function edit(){
		
		global $CONFIG;
		$id 		= $this->_g('id');
		$authorid				= intval($this->_p("authorid"));
		if($authorid==0)  $authorid		= $this->Session->getVariable("uid");
	
		if(! $this->_p('simpan')){
		
			$sql = "SELECT * FROM {$this->dbclass}_news_content WHERE id={$id} LIMIT 1";
			$qData = $this->fetch($sql);
			// pr($qData);
			if($qData){
				if($qData['tags']!='')	$qData['tags'] = implode(',',unserialize($qData['tags']));
			
				foreach($qData as $key => $val){					
					$this->View->assign($key,$val);
				}
			}
		
		}else{
			$id 			= $this->_p('id');
			$title 			= $this->_p('title');
			$tags 			= $this->_p('tags');
			$topcontent 	= $this->_p('topcontent');
			$brief 			= $this->_p('brief');
			$content 		= $this->_p('content');
			$content 	  	= $this->fixTinyEditor( $content );
			$url 			= $this->_p('url');
			$sourceurl 		= $this->_p('sourceurl');
			if($this->roler['approver']) $status = $this->_p('n_status');
			else $status 	 = 0;
			$posted_date 	= $this->_p('posted_date');
			$expired_date 	= $this->_p('expired_date');
			$articleType	= $this->_p('articleType');
		
			if($this->type) {
				$arrType 	= explode(',',$this->type);				
				if(!in_array($articleType,$arrType)) {
					return $this->View->showMessage("you are not authorize for this type id", "index.php?s={$this->folder}");
				}
			}
			if($this->category) {
				$arrCategory 	= explode(',',$this->category);
				if(!in_array($categoryid,$arrCategory)) {
					return $this->View->showMessage('you are not authorize for this category id', "index.php?s={$this->folder}");
				}
			}
			
			if($title=='' ){
				$this->View->assign('msg',"Please complete the form!");
				return $this->View->toString("application/admin/{$this->folder}/{$this->folder}_edit.html");
			}
			
			if($tags){
				$tags = serialize(explode(',',$tags));
			}
			$sql = "UPDATE {$this->dbclass}_news_content SET 	title='{$title}',
														brief=\"{$brief}\",
														content=\"{$content}\",
														posted_date='{$posted_date}',
														expired_date='{$expired_date}',
														articleType='{$articleType}',
														n_status='{$status}',
														url='{$url}',
														tags='{$tags}',
														fromwho='{$this->fromwho}',
														sourceurl='{$sourceurl}',
														authorid='{$authorid}',
														topcontent='{$topcontent}'
														WHERE id={$id} LIMIT 1";
			
			
			$last_id = $id;
		
			// pr($sql);exit;
			if(!$this->query($sql)){
				$this->View->assign("msg","edit process failure");
				return $this->View->toString("application/admin/{$this->folder}/{$this->folder}_edit.html");
			}else{
				//create Image function
				$this->createImage($last_id);				
				
				return $this->View->showMessage('Berhasil', "index.php?s={$this->folder}");
			}
		}
		
		return $this->View->toString("application/admin/{$this->folder}/{$this->folder}_edit.html");
	}
	function havewinnerthisday(){
		$userid = $this->_p('userid');
		$id = $this->_p('id');
		$n_status = $this->_p('n_status');
		$taskid = $this->_p('taskid');
		$date = date("Y-m-d H:i:s");
		$sql = "SELECT COUNT(*) total FROM {$this->dbclass}_news_content_repo WHERE id='{$id}' AND DATE(winning_date)=DATE('{$date}') AND n_status = 2 LIMIT 1";
		
		$qData = $this->fetch($sql);
		if($qData){
			if($qData['total']>0) return true;
			return false;
		}
		return false;
	}
	
	function ajax(){
		global $LOCALE;	
		$userid = $this->_p('userid');
		$id = $this->_p('id');
		$n_status = $this->_p('n_status');
		$taskid = $this->_p('taskid');
		
		// pr($n_status);
			$date = date("Y-m-d H:i:s");
			
			$havewinnerthisday = $this->havewinnerthisday();
			
			if($havewinnerthisday) {
				print json_encode(array('status'=>false));
				exit;
			}
			
			$sql = "UPDATE {$this->dbclass}_news_content_repo SET n_status = {$n_status} ,winning_date='{$date}' WHERE userid = {$userid} AND otherid={$taskid} AND id='{$id}' LIMIT 1";
			
			$qData = $this->query($sql);
			// pr($qData);
			if($qData){
				
				if ($n_status == 2){
					// $this->completetask($taskid, true);
					$this->giveLetterWhenVerified($userid, $taskid, true);
					// exit;
					
				}
				if ($n_status == 3){
					$this->log->sendUserActivity($userid, 'account', $LOCALE[1]['thisorthatrejected']);
				}
				
				print json_encode(array('status'=>true));
			}else{
				print json_encode(array('status'=>false));
			}
			
			exit;
		
		
	}
	
	function completetask($taskID=false, $param=false, $letter=null, $userid){
			global $LOCALE;	
				if(!$taskID)$taskID = $this->_p('taskid');
				
				$id = $userid;
				
				$date = date('Y-m-d H:i:s');
				$sql = "INSERT INTO my_task (userid, taskid, taskdate, n_status)
						VALUES ({$id}, {$taskID}, '{$date}', 1)";
				$hasil = $this->query($sql);
				sleep(1);
				if ($param){
					// $this->log->sendActivity($LOCALE[1]['thisorthatverified'].' and you got a letter '.$letter);
					$this->log->sendUserActivity($id, 'account', $LOCALE[1]['thisorthatverified'].' and you got a letter "'.$letter.'"');
				}else{
					$this->log->sendUserActivity($id, 'account',$LOCALE[1]['thisorthatrejected']);
				}
				
				return true;
	}
	
	function giveLetterWhenVerified($userid=0, $taskid=0, $param=false)
	{
		global $LOCALE;
		
		if ($userid ==0) return false;
		if ($taskid ==0) return false;
		
		$result =  $this->setLetterWhenVerified($userid, $taskid, $param);
		// pr($result);
		if($result['status']){
			$this->completetask($taskid, true, $result['letter'], $userid);
			
			return true;
		}
		return false;
		
	}
	
	function setLetterWhenVerified($userid=0, $taskid=0, $param)
	{
		$checkCode = false;
		$mytoken = false;
		$usertype = array(0,1,2);
		$gamesarrayid = array(1,2);
		$usertypeletter ='thisorthatcode';
		
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
		$checkgotfirstlogincode  = $this->checkgotthisorthatverified($usertypeletter, $this->uid);		
		if($checkgotfirstlogincode) return false;
		
		$checkCode = $this->checkpublicexistsinventory($usertypeletter, $this->uid);
		
		if(!$checkCode) return false;
	
		if($checkCode['result']){
			
					/* save to inventory user if  */
					
					$saved = $this->savetoinventory(true,$checkCode['data'],$usertypeletter, $this->uid);
					
					if($saved) {
						
						return array('status'=>true);
					}
			
		}else{
		
			$checkCode = false;
			/* if not found code in publicity code, create 1 code for this user */
			
			$firstcreatecode = $this->generateCode("THIS OR THAT LETTER",$usertypeletter);
			if(!$firstcreatecode) return false;		
				
			$checkCode = $this->checkpublicexistsinventory($usertypeletter, $this->uid);
			if(!$checkCode) return false;
				
			if($checkCode['result']){
			
					/* save to inventory user   */
			
						
					$saved = $this->savetoinventory(true,$checkCode['data'],$usertypeletter, $this->uid);
					// pr($saved);
					if($saved['status']){
						
						return array('status'=>true, 'letter'=>$saved['letter']);
						
					}
					
					return array('status'=>false);
			
			}else {
			
			return false;
			}
		}
		
		
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
		
	
		$qData = $this->fetch($sql);
		
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
	
	function savetoinventory($win=false,$code=false,$gamenames="20th",$userid, $howmany=1){
		
		if(!$win) return false;
		if(!$code) return false;
		if(!$userid) return false;
		
		
		 /* userid 	codeid 	codepublicityid 	n_status 	histories */
		
		$sql = " INSERT INTO tbl_code_inventory 
		(userid, codeid, codepublicityid, n_status, histories) 
		VALUES ({$userid},{$code['codeid']},{$code['id']},0,' get from {$gamenames} ')";		
		
		$this->query($sql);
		if($this->getLastInsertId()){
		
			$getLetter = $this->checkmyletter($this->getLastInsertId());
			if ($getLetter) return array('status'=>true, 'letter'=>$getLetter);
			return false;
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
		$sql = "SELECT * FROM tbl_code_detail WHERE n_status = 1   ";
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
		$sql = "SELECT code.codename 
				FROM tbl_code_inventory inv 
				LEFT JOIN  tbl_code_detail code 
				ON inv.codeid = code.id WHERE inv.id = {$id}";
		// pr($sql);
		$res = $this->fetch($sql);
		if ($res) return $res['codename'];
		
		return false;
	}
	
	function hapus(){
		$id = $this->_g('id');
		if( !$this->query("UPDATE {$this->dbclass}_news_content SET n_status=3 WHERE id={$id}")){
			return $this->View->showMessage('Gagal',"index.php?s={$this->folder}");
		}else{
			return $this->View->showMessage('Berhasil',"index.php?s={$this->folder}");
		}
	}
	
	function createbanner($last_id=null,$arrBanner=null){
		if($last_id==null) return false;
		if(!$arrBanner) return false;
		
		$sql = "SELECT count(*) total FROM {$this->dbclass}_news_content_banner WHERE parentid={$last_id} LIMIT 1 ";
				$qData = $this->fetch($sql);
			
				if($qData['total']>0){
				
					$sql = "UPDATE {$this->dbclass}_news_content_banner SET 
					page='{$arrBanner['pages']}' , 
					type={$arrBanner['bannerType']}
					WHERE parentid={$last_id} LIMIT 1";
					// pr($sql);exit;
					$this->query($sql);
					
				}else{
					if($last_id){
						$sql = "
						INSERT INTO {$this->dbclass}_news_content_banner (parentid,page,type,n_status) 
						VALUES ({$last_id},'{$arrBanner['pages']}',{$arrBanner['bannerType']},1)
						";
						// pr($sql);exit;
						$this->query($sql);
						if(!$this->getLastInsertId()){
							return $this->View->showMessage(" {$this->folder}  gagal di upload", "index.php?s=banner");
						}
					}
				}
			return true;
	
	}
	
	function createImage($last_id=null){
				global $CONFIG;
				if($last_id==null) return false;
				if ($_FILES['image']['name']!=NULL) {
					include_once '../../engines/Utility/phpthumb/ThumbLib.inc.php';
					list($file_name,$ext) = explode('.',$_FILES['image']['name']);
					$img = md5($_FILES['image']['name'].rand(1000,9999)).".".$ext;
					try{
						$thumb = PhpThumbFactory::create( $_FILES['image']['tmp_name']);
					}catch (Exception $e){
						return false;
					}
			
					if(move_uploaded_file($_FILES['image']['tmp_name'],"{$CONFIG['LOCAL_PUBLIC_ASSET']}{$this->folder}/{$img}")){
					
						list($width, $height, $type, $attr) = getimagesize("{$CONFIG['LOCAL_PUBLIC_ASSET']}{$this->folder}/{$img}");
						$maxSize = 1000;
						if($width>=$maxSize){
							if($width>=$height) {
								$subs = $width - $maxSize;
								$percentageSubs = $subs/$width;
							}
						}
						if($height>=$maxSize) {
							if($height>=$width) {
								$subs = $height - $maxSize;
								$percentageSubs = $subs/$height;
							}
						}
						if(isset($percentageSubs)) {
						 $width = $width - ($width * $percentageSubs);
						 $height =  $height - ($height * $percentageSubs);
						}
						
						$w_small = $width - ($width * 0.5);
						$h_small = $height - ($height * 0.5);
						$w_tiny = $width - ($width * 0.7);
						$h_tiny = $height - ($height * 0.7);
						
						//resize the image
						$thumb->adaptiveResize($width,$height);
						$big = $thumb->save( "{$CONFIG['LOCAL_PUBLIC_ASSET']}{$this->folder}/big_".$img);
						$thumb->adaptiveResize($w_small,$h_small);
						$small = $thumb->save( "{$CONFIG['LOCAL_PUBLIC_ASSET']}{$this->folder}/small_".$img );
						$thumb->adaptiveResize($w_tiny,$h_tiny);
						$tiny = $thumb->save( "{$CONFIG['LOCAL_PUBLIC_ASSET']}{$this->folder}/tiny_".$img );
						
						$this->autoCropCenterArea($img,"{$CONFIG['LOCAL_PUBLIC_ASSET']}{$this->folder}/",$width,$height);
					
					}
					
					
					
					$this->inputImage($last_id,$img);
					
					
				}
				
				if ($_FILES['image_thumb']['name']!=NULL) {
					include_once '../../engines/Utility/phpthumb/ThumbLib.inc.php';
					list($file_nameThumb,$ext_thumb) = explode('.',$_FILES['image_thumb']['name']);
					$img_thumb = md5($_FILES['image_thumb']['name'].rand(1000,9999)).".".$ext_thumb;
					try{
						$thumb = PhpThumbFactory::create( $_FILES['image_thumb']['tmp_name']);
					}catch (Exception $e){
						return false;
					}
					
					if(move_uploaded_file($_FILES['image_thumb']['tmp_name'],"{$CONFIG['LOCAL_PUBLIC_ASSET']}{$this->folder}/".$img_thumb)){
						list($width, $height, $type, $attr) = getimagesize("{$CONFIG['LOCAL_PUBLIC_ASSET']}{$this->folder}/{$img_thumb}");
						$maxSize = 256;
						if($width>=$maxSize){
							if($width>=$height) {
								$subs = $width - $maxSize;
								$percentageSubs = $subs/$width;
							}
						}
						if($height>=$maxSize) {
							if($height>=$width) {
								$subs = $height - $maxSize;
								$percentageSubs = $subs/$height;
							}
						}
						if(isset($percentageSubs)) {
							$width = $width - ($width * $percentageSubs);
							$height =  $height - ($height * $percentageSubs);
						}
						
						$w_small = $width - ($width * 0.5);
						$h_small = $height - ($height * 0.5);
						$w_tiny = $width - ($width * 0.7);
						$h_tiny = $height - ($height * 0.7);
						
						//resize the image
						$thumb->adaptiveResize($width,$height);
						$big = $thumb->save( "{$CONFIG['LOCAL_PUBLIC_ASSET']}{$this->folder}/thumbnail_".$img_thumb);
						$thumb->adaptiveResize($w_small,$h_small);
					}
					$this->inputImageThumb($last_id,$img_thumb);
				}
	}
	
		
	
	function inputImage($id,$img){
		$this->query("UPDATE {$this->dbclass}_news_content SET image='{$img}' WHERE id={$id}");
	}
	
	function inputImageThumb($id,$img){
		$this->query("UPDATE {$this->dbclass}_news_content SET thumbnail_image='{$img}' WHERE id={$id} ");
	}
	function getTypeList(){
		$sql = "SELECT * FROM {$this->dbclass}_news_content_type WHERE id IN ({$this->type}) AND  content =  {$this->contentType} ";
		$type = $this->fetch($sql,1);
		// pr($type);exit;
		return $type;
	}
	function getBannerTypeList(){
		$type = $this->fetch("SELECT * FROM  {$this->dbclass}_news_content_banner_type WHERE n_status=1",1);
		return $type;
	}
	function getPageList(){
		$sql = "SELECT * FROM {$this->dbclass}_news_content_page WHERE n_status=1 ";
		$page = $this->fetch($sql,1);
		// pr($sql);
		return $page;
	}
	

	function getContributor(){
		$articleType = intval($this->_p("articleType"));
		
		$sql = "
			SELECT *
			FROM gm_member 
			WHERE n_status <> 3
			AND articleTypes like '%\"{$articleType}\"%'
			ORDER BY name DESC
			
		";	
		// pr($sql);
		$list = $this->fetch($sql,1);
		print json_encode($list);exit;
	}

	
	function fixTinyEditor($content){
		global $CONFIG;
		$content = str_replace("\\r\\n","",$content);
		$content = htmlspecialchars(stripslashes($content), ENT_QUOTES);
		$content = str_replace("../index.php", "index.php", $content);

		//$content = htmlspecialchars( stripslashes($content) );
		$content = str_replace("&lt;", "<", $content);
		$content = str_replace("&gt;", ">", $content);
		$content = str_replace("&quot;", "'", $content);
		$content = str_replace("&amp;", "&", $content);
		return $content;
	}
	
		function gettaskreport(){
		
		$filename = "task_report_".date('Ymd_gia').".xls";
		header("Content-Type:   application/vnd.ms-excel; charset=utf-8");
		header("Content-Disposition: attachment; filename=$filename");  //File name extension was wrong
		header("Expires: 0");
		header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
		header("Cache-Control: private",false);
		// echo "Some Text"; //no ending ; here
		$resReport = $this->downloadreport_old();
		$this->log->sendActivity("user printing task report");
		// pr($resReport);
		echo "<table border='1'>";
		echo "<tr>";
			echo "<th class='head0'>No</th>";
			echo "<th class='head1'>Name</th>";
			echo "<th class='head1'>Email</th>";
			echo "<th class='head1'>Date</th>";
			echo "<th class='head1'>Theme Name</th>";
			echo "<th class='head1'>Status</th>";
		echo "</tr>";
		foreach ($resReport as $key => $val){			
			echo "<tr>";
				echo "<td>$val[no]</td>";
				echo "<td>$val[name] $val[last_name]</td>";
				echo "<td>$val[email]</td>";
				echo "<td>$val[created_date]</td>";
				echo "<td>$val[title]</td>";

				if($val['n_status'] == 0) $status = '';
				if($val['n_status'] == 1) $status = 'UnVerified';
				if($val['n_status'] == 2) $status = 'Verified';
				if($val['n_status'] == 3) $status = 'Rejected';
				
				echo "<td>$status</td>";

			echo "</tr>";
		}
		echo "</table>";
		exit;
	}
	
	/*
	function downloadreport_old(){
		global $CONFIG;
		$filter = "";
		$filtersub = "";
		$filtercode = "";
		$search = $this->_g("search") == NULL ? '' : $this->_g("search");
		$article_type = $this->_g("article_type") == NULL ? '' : $this->_g("article_type");
		$startdate = $this->_g("startdate") == NULL ? '' : $this->_g("startdate");
		$enddate = $this->_g("enddate") == NULL ? '' : $this->_g("enddate");
		$nstatus = (string) $this->_g("nstatus") == NULL ? '' : (string) $this->_g("nstatus");
		
		$filter .= $startdate=='' ? "" : "AND DATE(ncr.created_date) >= DATE('{$startdate}') ";
		$filtersub .= $startdate=='' ? "" : "AND DATE(games.datetimes) >= DATE('{$startdate}') ";
		$filtercode .= $startdate=='' ? "" : "AND DATE(inv.datetimes) >= DATE('{$startdate}') ";
		
		$filter .= $enddate=='' ? "" : "AND DATE(ncr.created_date) <= DATE('{$enddate}') ";		
		$filtersub .= $enddate=='' ? "" : "AND DATE(games.datetimes) <= DATE('{$enddate}') ";		
		$filtercode .= $enddate=='' ? "" : "AND DATE(inv.datetimes) <= DATE('{$enddate}') ";		
		
		$filter .= $search=='' ? "" : "AND (mnc.title LIKE '%{$search}%' OR mnc.brief LIKE '%{$search}%' OR mnc.content LIKE '%{$search}%') ";
		$filtersub .= $search=='' ? "" : "AND (sm.name LIKE '%{$search}%' OR sm.last_name LIKE '%{$search}%' OR sm.email LIKE '%{$search}%') ";
		$filtercode .= $search=='' ? "" : "AND (sm.name LIKE '%{$search}%' OR sm.last_name LIKE '%{$search}%' OR sm.email LIKE '%{$search}%') ";
		
		$filter .= $nstatus=='' ? "" : "AND ncr.n_status='{$nstatus}' ";
		$filtersub .= $nstatus=='' ? "" : "AND  games.n_status='100' ";
		$filtercode .= $nstatus=='' ? "" : "AND  inv.n_status='100'  ";
		
				
		$artType = explode(',',$this->type);
		if ($article_type!='') {
			if(in_array($article_type,$artType)){ 
				$filter .= $article_type=='' ? "" : "AND mnc.articleType='{$article_type}'";
					if($article_type==22||$article_type==6){
						$gamesid = 1;
						if($article_type==22) $gamesid = 2;
						$filtersub .= $article_type=='' ? "" : "AND games.gamesid='{$gamesid}'";
					}else $filtersub .=" AND games.gamesid='10' ";
					
					if($article_type!=18)$filtercode .=" AND cp.n_status='100' ";
			}
			else $filter .= "AND mnc.articleType IN ({$article_type}) ";
		}		
		*/
		
		/* list article */

		/*
		$sql = "
				(
				SELECT ncr.id as id, ncr.created_date , sm.name, sm.last_name, sm.email, mnc.title,mnc.articleType, ncr.n_status, ncr.userid, mnc.id as taskid, 1 usedverified, ncr.files img
				FROM {$this->dbclass}_news_content_repo ncr
				LEFT JOIN {$this->dbclass}_news_content mnc ON ncr.otherid = mnc.id
				LEFT JOIN social_member sm ON ncr.userid = sm.id
				WHERE mnc.articleType  IN ( {$this->type} ) {$filter}
				)
				UNION
				(
				SELECT games.id as id,  games.datetimes created_date, sm.name, sm.last_name, sm.email, IF (games.gamesid=1,'CROSS OUT','WALL BREAKER') title, IF (games.gamesid=1,6,22)  articleType,games.n_status, games.userid, games.id AS taskid, 0 usedverified, '' img
				FROM my_games games
				LEFT JOIN social_member sm ON games.userid = sm.id
				WHERE games.point = 20  {$filtersub}
				GROUP BY games.userid,games.gamesid
				)
				UNION
				(
				SELECT  inv.id as id, inv.datetimes created_date, sm.name, sm.last_name, sm.email, cp.channel title, 18 articleType,inv.n_status, inv.userid, cp.id AS taskid, 0 usedverified, '' img
				FROM tbl_code_inventory  inv
				LEFT JOIN tbl_code_publicity cp  ON cp.id = inv.codepublicityid 
				LEFT JOIN social_member sm ON inv.userid = sm.id
				WHERE cp.channel='games hidden code' {$filtercode}
				)
				ORDER BY created_date DESC
		";
		// pr($sql);
		$list = $this->fetch($sql,1);
		
		if($list){				
			$n=$start+1;
			foreach($list as $key => $val){
				$list[$key]['no'] = $n++;	
			}
			
		}
		
		return $list;
		
	}	
	
	*/
	
	function downloadreport_old(){
		global $CONFIG;
		$filter = "";
		$filtersub = "";
		$filtercode = "";
		$search = $this->_g("search") == NULL ? '' : $this->_g("search");
		$article_type = $this->_g("article_type") == NULL ? '0' : $this->_g("article_type");
		$startdate = $this->_g("startdate") == NULL ? '' : $this->_g("startdate");
		$enddate = $this->_g("enddate") == NULL ? '' : $this->_g("enddate");
		$nstatus = (string) $this->_g("nstatus") == NULL ? '0' : (string) $this->_g("nstatus");
		
		$filter .= $startdate=='' ? "" : "AND DATE(ncr.created_date) >= DATE('{$startdate}') ";
		$filtersub .= $startdate=='' ? "" : "AND DATE(games.datetimes) >= DATE('{$startdate}') ";
		$filtercode .= $startdate=='' ? "" : "AND DATE(inv.datetimes) >= DATE('{$startdate}') ";
		
		$filter .= $enddate=='' ? "" : "AND DATE(ncr.created_date) <= DATE('{$enddate}') ";		
		$filtersub .= $enddate=='' ? "" : "AND DATE(games.datetimes) <= DATE('{$enddate}') ";		
		$filtercode .= $enddate=='' ? "" : "AND DATE(inv.datetimes) <= DATE('{$enddate}') ";		
		
		$filter .= $search=='' ? "" : "AND (mnc.title LIKE '%{$search}%' OR mnc.brief LIKE '%{$search}%' OR mnc.content LIKE '%{$search}%') ";
		$filtersub .= $search=='' ? "" : "AND (sm.name LIKE '%{$search}%' OR sm.last_name LIKE '%{$search}%' OR sm.email LIKE '%{$search}%') ";
		$filtercode .= $search=='' ? "" : "AND (sm.name LIKE '%{$search}%' OR sm.last_name LIKE '%{$search}%' OR sm.email LIKE '%{$search}%') ";
		
		$filter .= $nstatus=='0' ? "" : "AND ncr.n_status='{$nstatus}' ";
		$filtersub .= $nstatus=='0' ? "" : "AND  games.n_status='100' ";
		$filtercode .= $nstatus=='0' ? "" : "AND  inv.n_status='100'  ";
		
				
		$artType = explode(',',$this->type);
		if ($article_type!='0') {
			if(in_array($article_type,$artType)){ 
				$filter .= $article_type=='0' ? "" : "AND mnc.articleType='{$article_type}'";
					if($article_type==22||$article_type==6){
						$gamesid = 1;
						if($article_type==22) $gamesid = 2;
						$filtersub .= $article_type=='0' ? "" : "AND games.gamesid='{$gamesid}'";
					}else $filtersub .=" AND games.gamesid='10' ";
					
					if($article_type!=18)$filtercode .=" AND cp.n_status='100' ";
			}
			else $filter .= "AND mnc.articleType IN ({$article_type}) ";
		}		
		
		
		/* list article */

		$sql = "
				(
				SELECT ncr.id as id, ncr.created_date , sm.name, sm.last_name, sm.email, mnc.title,mnc.articleType, ncr.n_status, ncr.userid, mnc.id as taskid, 1 usedverified, ncr.files img
				FROM {$this->dbclass}_news_content_repo ncr
				LEFT JOIN {$this->dbclass}_news_content mnc ON ncr.otherid = mnc.id
				LEFT JOIN social_member sm ON ncr.userid = sm.id
				WHERE mnc.articleType  IN ( {$this->type} ) {$filter}
				)
				UNION
				(
				SELECT games.id as id,  games.datetimes created_date, sm.name, sm.last_name, sm.email, IF (games.gamesid=1,'CROSS OUT','WALL BREAKER') title, IF (games.gamesid=1,6,22)  articleType,IF (games.n_status=1,0,1) n_status, games.userid, games.id AS taskid, 0 usedverified, '' img
				FROM my_games games
				LEFT JOIN social_member sm ON games.userid = sm.id
				WHERE games.point = 20  {$filtersub}
				GROUP BY games.userid,games.gamesid
				)
				UNION
				(
				SELECT  inv.id as id, inv.datetimes created_date, sm.name, sm.last_name, sm.email, cp.channel title, 18 articleType,IF (inv.n_status=3,0,0) n_status, inv.userid, cp.id AS taskid, 0 usedverified, '' img
				FROM tbl_code_inventory  inv
				LEFT JOIN tbl_code_publicity cp  ON cp.id = inv.codepublicityid 
				LEFT JOIN social_member sm ON inv.userid = sm.id
				WHERE cp.channel='games hidden code' {$filtercode}
				)
				ORDER BY created_date DESC
		";
		// pr($sql);exit;
		$list = $this->fetch($sql,1);
		
		if($list){				
			$n=$start+1;
			foreach($list as $key => $val){
				$list[$key]['no'] = $n++;	
			}
			
		}
		
		return $list;
		
	}	
	
	function savecrop(){
		global $CONFIG;
		$files['source_file'] = $this->_p('imageFilename');
		$files['url'] = $CONFIG['LOCAL_PUBLIC_ASSET']."{$this->folder}/";
		$files['real_url'] = $CONFIG['LOCAL_PUBLIC_ASSET']."{$this->folder}/";
		$arrFilename = explode('.',$files['source_file']);
		if($files==null) return false;
		$targ_w = $this->_p('w');
		$targ_h = $this->_p('h');
		$targ_scale = floatval($this->_p('scale'));
		$jpeg_quality = 90;
		
		$src = 	$files['real_url'].$files['source_file'];
		// pr($src);exit;
		$file_ext = strtolower($arrFilename[sizeof($arrFilename)-1]);
		
		if($file_ext=='jpg' || $file_ext=='jpeg' ){
			$img_r = imagecreatefromjpeg($src);
		}
		if($file_ext=='png' ) {
			$img_r = imagecreatefrompng($src);
			imagealphablending($img_r, true);
		}
		if($file_ext=='gif' ) $img_r = imagecreatefromgif($src);
		
		$dst_r = ImageCreateTrueColor( $targ_w, $targ_h ) or die('Cannot Initialize new GD image stream');
		
		if($file_ext=='png'){
			imagesavealpha($dst_r, true);
			imagealphablending($dst_r, false);
			$transparent = imagecolorallocatealpha($dst_r, 0, 0, 0, 127);
			imagefill($dst_r, 0, 0, $transparent);

		}
		
		imagecopyresampled($dst_r,$img_r,0,0,$this->_p('x'),$this->_p('y'),$targ_w,$targ_h, $this->_p('w'),$this->_p('h'));		
		
		// header('Content-type: image/jpeg');
		if($file_ext=='jpg' || $file_ext=='jpeg' ) imagejpeg($dst_r,$files['url'].'thumb_'.$files['source_file'],$jpeg_quality);
		if($file_ext=='png')imagepng($dst_r,$files['url'].'thumb_'.$files['source_file']);
		if($file_ext=='gif') imagegif($dst_r,$files['url'].'thumb_'.$files['source_file']);
		
		if($targ_scale>0){
			$info = getimagesize($src);
			$this->resize_image($src,$files['url'].'resized_'.$files['source_file'],$files,$file_ext,0,0,($info[0]*($targ_scale/100)),($info[1]*($targ_scale/100)),$info[0],$info[1]);
			$src = $files['url'].'resized_'.$files['source_file'];
		}
		
		$this->resize_image($src,$files['url'].'thumb_'.$files['source_file'],$files,$file_ext,$this->_p('x'),$this->_p('y'),$targ_w,$targ_h,$this->_p('w'),$this->_p('h'));		
		
		header('Cache-Control: no-cache, must-revalidate');
		header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
		header('Content-type: application/json');		
		print json_encode(array('image'=>$CONFIG['BASE_DOMAIN']."public_assets/{$this->folder}/thumb_".$files['source_file']));
		exit;
	}
	
	function resize_image($src,$target,$files,$file_ext,$nx,$ny,$targ_w,$targ_h,$nw,$nh,$jpeg_quality = 90){
		if($file_ext=='jpg' || $file_ext=='jpeg' ){
			$img_r = imagecreatefromjpeg($src);
		}
		
		if($file_ext=='png' ) {
			$img_r = imagecreatefrompng($src);
			imagealphablending($img_r, true);
		}
		
		if($file_ext=='gif' ) $img_r = imagecreatefromgif($src);
		$dst_r = ImageCreateTrueColor( $targ_w, $targ_h ) or die('Cannot Initialize new GD image stream');
		
		if($file_ext=='png'){
			imagesavealpha($dst_r, true);
			imagealphablending($dst_r, false);
			$transparent = imagecolorallocatealpha($dst_r, 0, 0, 0, 127);
			imagefill($dst_r, 0, 0, $transparent);
		}
		
		imagecopyresampled($dst_r,$img_r,0,0,$nx,$ny,$targ_w,$targ_h, $nw,$nh);
		
		//$files['url'].'thumb_'.$files['source_file']
		
		// header('Content-type: image/jpeg');
		if($file_ext=='jpg' || $file_ext=='jpeg' ) imagejpeg($dst_r,$target,$jpeg_quality);
		if($file_ext=='png')imagepng($dst_r,$files['url'].'thumb_'.$files['source_file']);
		if($file_ext=='gif') imagegif($dst_r,$files['url'].'thumb_'.$files['source_file']);
	}
	
	function autoCropCenterArea($imageFilename=null,$imageUrl=null,$width=0,$height=0){
		
		
		if($imageFilename==null||$imageUrl==null) return false;
		if($width==0||$height==0) return false;
				// pr('masuk');exit;
		global $CONFIG;
		$files['source_file'] = $imageFilename;
		$files['url'] = $imageUrl;
		// $files['real_url'] = $CONFIG['LOCAL_PUBLIC_ASSET'];
		$arrFilename = explode('.',$files['source_file']);
		if($files==null) return false;
		
		$jpeg_quality = 90;
		
		//get x, y : phytagoras
		// to get center of view from image variants
		$phyt = sqrt($width*$width +  $height*$height);
		$x = ceil($phyt/4);
		$y = ceil($phyt/4);			
		//count view dimension, size same as x and y
		$targ_w = $x;
		$targ_h = $y;		
		//count image dimension, size progresize from targ_w
		$width  = $x;
		$height = $y;
			
		
		if($files['source_file']=='') return false;
		
		$src = 	$files['url'].$files['source_file'];
		try{
			$img_r = false;
			if($arrFilename[1]=='jpg' || $arrFilename[1]=='jpeg' ) $img_r = imagecreatefromjpeg($src);
			if($arrFilename[1]=='png' ) $img_r = imagecreatefrompng($src);
			if($arrFilename[1]=='gif' ) $img_r = imagecreatefromgif($src);
			if(!$img_r) return false;
			$dst_r = ImageCreateTrueColor( $targ_w, $targ_h );

			imagecopyresampled($dst_r,$img_r,0,0,$x,$y,	$targ_w,$targ_h,$width,$height);

			// header('Content-type: image/jpeg');
			if($arrFilename[1]=='jpg' || $arrFilename[1]=='jpeg' ) imagejpeg($dst_r,$files['url']."square".$files['source_file'],$jpeg_quality);
			if($arrFilename[1]=='png' ) imagepng($dst_r,$files['url']."square".$files['source_file']);
			if($arrFilename[1]=='gif' ) imagegif($dst_r,$files['url']."square".$files['source_file']);
			
		}catch (Exception $e){
			return false;
		}
		// include_once '../engines/Utility/phpthumb/ThumbLib.inc.php';
			
		try{
			$thumb = PhpThumbFactory::create($files['url']."square".$files['source_file']);
		}catch (Exception $e){
			// handle error here however you'd like
		}
		list($width, $height, $type, $attr) = getimagesize($files['url']."square".$files['source_file']);
		$maxSize = 600;
		if($width>=$maxSize){
			if($width>=$height) {
				$subs = $width - $maxSize;
				$percentageSubs = $subs/$width;
			}
		}
		if($height>=$maxSize) {
			if($height>=$width) {
				$subs = $height - $maxSize;
				$percentageSubs = $subs/$height;
			}
		}
		if(isset($percentageSubs)) {
		 $width = $width - ($width * $percentageSubs);
		 $height =  $height - ($height * $percentageSubs);
		}
		
		$w_small = $width - ($width * 0.5);
		$h_small = $height - ($height * 0.5);
		$w_tiny = $width - ($width * 0.7);
		$h_tiny = $height - ($height * 0.7);
		
		//resize the image
		$thumb->adaptiveResize($width,$height);
		$big = $thumb->save(  "{$files['url']}"."thumb_".$files['source_file']);
		$thumb->adaptiveResize($width,$height);
		$prev = $thumb->save(  "{$files['url']}thumb_prev_".$files['source_file']);
		$thumb->adaptiveResize($w_small,$h_small);
		$small = $thumb->save( "{$files['url']}thumb_small_".$files['source_file'] );
		$thumb->adaptiveResize($w_tiny,$h_tiny);
		$tiny = $thumb->save( "{$files['url']}thumb_tiny_".$files['source_file']);
		
	}
}