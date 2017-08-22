<?php

global $ENGINE_PATH;
include_once $ENGINE_PATH."Utility/Paginate.php";
include_once "botManager.php";

class activity extends Admin{
	var $category;
	var $type;
	function __construct(){	
		parent::__construct();	
		
		$this->type = "1,3,4,5,6";
		$this->contentType = "0";
		$this->folder =  'activity';
		$this->dbclass = 'marlborohunt';
		$this->fromwho = 0; // 0 is admin/backend
		$this->total_per_page = 20;
		$this->botManager = new botManager;
		

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
		$article_type = $this->_g("article_type") == NULL ? '' : $this->_g("article_type");
		$winStatus = $this->_g("winStatus") == NULL ? '' : $this->_g("winStatus");
		$startdate = $this->_g("startdate") == NULL ? '' : $this->_g("startdate");
		$enddate = $this->_g("enddate") == NULL ? '' : $this->_g("enddate");
		$filter .= $startdate=='' ? "" : "AND DATE(a.datetime) >= '{$startdate}' ";
		$filter .= $enddate=='' ? "" : "AND DATE(a.datetime) <= '{$enddate}' ";		
		$filter .= $search=='' ? "" : "AND (sm.name LIKE '%{$search}%' OR sm.last_name LIKE '%{$search}%')";
		$this->View->assign('search',$search);
		$this->View->assign('article_type',$article_type);
		$this->View->assign('winStatus',$winStatus);
		$this->View->assign('startdate',$startdate);
		$this->View->assign('enddate',$enddate);
					
		$artType = explode(',',$this->type);
		if ($article_type!='') {
			if(in_array($article_type,$artType)){ $filter .= $article_type=='' ? "" : "AND con.articleType='{$article_type}'";}
			else $filter .= "AND con.articleType IN ({$article_type}) ";
		}
		
		if($winStatus==2){
			$filter .=  " AND a.n_status='{$winStatus}' ";
		}
		
		if($winStatus==1){
			$filter .=  " AND a.n_status='{$winStatus}' ";
		}	
	
		$start = intval($this->_g('st'));
		
		/* Hitung banyak record data */
		$sql = "SELECT bucket.*, sm.name, sm.last_name, sm.email, bucket.datetime, que.question, repo.files, repo.otherid
				FROM my_bucket bucket
				LEFT JOIN social_member sm ON bucket.userid = sm.id
				LEFT JOIN question que ON bucket.taskid = que.id
				LEFT JOIN {$this->dbclass}_news_content con ON bucket.bucketid = con.id
				LEFT JOIN {$this->dbclass}_news_content_repo repo ON bucket.id = repo.otherid
				WHERE repo.gallerytype = 2 {$filter}   ORDER BY  bucket.datetime DESC ";
		// pr($sql);
		$totalList = $this->fetch($sql,1);	
		if($totalList){
		$total = count($totalList);
		}else $total = 0;
		// pr($total);
		/* list article */
		 // DATE_FORMAT(a.datetime,'%Y-%m-%d') 
		 
		/* $sql = "SELECT a.userid, a.image, a.datetime, a.n_status, con.name, con.id, con.email, con.last_name
				FROM my_dyo AS a LEFT JOIN social_member con ON a.userid = con.id
				WHERE con.name IS NOT NULL {$filter} GROUP BY a.userid
				ORDER BY a.id DESC LIMIT {$start},{$this->total_per_page}";		
		 */
		$sql = "SELECT bucket.*, sm.name, sm.last_name, sm.email, bucket.datetime, que.question, repo.files, repo.otherid,
				con.title, cat.category, repo.typealbum, repo.created_date, repo.link
				FROM my_bucket bucket
				LEFT JOIN social_member sm ON bucket.userid = sm.id
				LEFT JOIN question que ON bucket.taskid = que.id
				LEFT JOIN {$this->dbclass}_news_content con ON bucket.bucketid = con.id
				LEFT JOIN {$this->dbclass}_news_content_repo repo ON bucket.id = repo.otherid
				LEFT JOIN {$this->dbclass}_news_content_category cat ON con.categoryid = cat.id
				WHERE repo.gallerytype = 2 {$filter}  ORDER BY  bucket.datetime DESC LIMIT {$start},{$this->total_per_page}";
		$list = $this->fetch($sql,1);
		
		
		// $sqlGallery = "SELECT p.*, sm.name, sm.last_name, sm.email, bucket.datetime, repo.files, repo.otherid
				// FROM my_post p
				// LEFT JOIN social_member sm ON p.userid = sm.id
				// LEFT JOIN {$this->dbclass}_news_content con ON bucket.bucketid = con.id
				// LEFT JOIN {$this->dbclass}_news_content_repo repo ON bucket.id = repo.otherid
				// WHERE 1 {$filter} LIMIT {$start},{$this->total_per_page}";
				
		// pr($sql);
		
		if($list){
				
			$n=$start+1;
			foreach($list as $key => $val){
					$list[$key]['no'] = $n++;
				if ($val['link']){
					$list[$key]['video_thumbnail'] = $this->getVideoEmbededUrl(array('url'=>$val['link']));
				}				
			}
			
		}			
		// pr($list);
		$this->View->assign('list',$list);
		$this->Paging = new Paginate();
	
		$this->View->assign("paging",$this->Paging->getAdminPaging($start, $this->total_per_page, $total, "?s={$this->folder}&article_type={$article_type}&winStatus={$winStatus}&startdate={$startdate}&enddate={$enddate}"));	
		// pr("application/admin/{$this->folder}/{$this->folder}_list.html");
		return $this->View->toString("application/admin/{$this->folder}/{$this->folder}_list.html");
	}
	
	function getVideoEmbededUrl($val=null){

		if($val==null) return false;
		if(!is_array($val)) return false;
		$video_thumbnail = false;
		$link = false;
		
		if($val['url']!='') {
			//parser url and get param data
			$parseUrl = parse_url($val['url']);
			// pr($parseUrl);

			if(array_key_exists('host',$parseUrl)) {
				if(strtolower($parseUrl['host'])!="youtu.be") $link = true;
			}
			if($link){
				if(array_key_exists('query',$parseUrl)) parse_str($parseUrl['query'],$parseQuery);
				else $parseQuery = false;
				if($parseQuery) {
					if(array_key_exists('v',$parseQuery))$video_thumbnail = $parseQuery['v'];
				} 
			}else{
				if(array_key_exists('path',$parseUrl)){
					$arrVideoUrl = explode("/",$parseUrl['path']);
					if(is_array($arrVideoUrl)){
					$video_thumbnail = $arrVideoUrl[1];
					}
				}
			} 
		} 

		return $video_thumbnail;
	}
	
	function pledge()
	{
		
		//filter box
		$filter = "";
		$search = $this->_g("search") == NULL ? '' : $this->_g("search");
		$article_type = $this->_g("article_type") == NULL ? '' : $this->_g("article_type");
		$winStatus = $this->_g("winStatus") == NULL ? '' : $this->_g("winStatus");
		$startdate = $this->_g("startdate") == NULL ? '' : $this->_g("startdate");
		$enddate = $this->_g("enddate") == NULL ? '' : $this->_g("enddate");
		$filter .= $startdate=='' ? "" : "AND DATE(a.datetime) >= '{$startdate}' ";
		$filter .= $enddate=='' ? "" : "AND DATE(a.datetime) <= '{$enddate}' ";		
		$filter .= $search=='' ? "" : "AND (sm.name LIKE '%{$search}%' OR sm.last_name LIKE '%{$search}%')";
		$this->View->assign('search',$search);
		$this->View->assign('article_type',$article_type);
		$this->View->assign('winStatus',$winStatus);
		$this->View->assign('startdate',$startdate);
		$this->View->assign('enddate',$enddate);
					
		$artType = explode(',',$this->type);
		if ($article_type!='') {
			if(in_array($article_type,$artType)){ $filter .= $article_type=='' ? "" : "AND con.articleType='{$article_type}'";}
			else $filter .= "AND con.articleType IN ({$article_type}) ";
		}
		
		if($winStatus==2){
			$filter .=  " AND a.n_status='{$winStatus}' ";
		}
		
		if($winStatus==1){
			$filter .=  " AND a.n_status='{$winStatus}' ";
		}	
	
		$start = intval($this->_g('st'));
		
		/* Hitung banyak record data */
		$sql = "SELECT bucket.*, sm.name, sm.last_name, sm.email, bucket.datetime, que.question, repo.files, repo.otherid,
				con.title, cat.category, repo.typealbum
				FROM my_bucket bucket
				LEFT JOIN social_member sm ON bucket.userid = sm.id
				LEFT JOIN question que ON bucket.taskid = que.id
				LEFT JOIN {$this->dbclass}_news_content con ON bucket.bucketid = con.id
				LEFT JOIN {$this->dbclass}_news_content_repo repo ON bucket.id = repo.otherid
				LEFT JOIN {$this->dbclass}_news_content_category cat ON con.categoryid = cat.id
				WHERE bucket.dochallenge =1
				AND bucket.point <> 0 ORDER BY bucket.datetime DESC ";
		// pr($sql);
		$totalList = $this->fetch($sql,1);	
		if($totalList){
		$total = count($totalList);
		}else $total = 0;
		// pr($total);
		/* list article */
		 // DATE_FORMAT(a.datetime,'%Y-%m-%d') 
		 
		/* $sql = "SELECT a.userid, a.image, a.datetime, a.n_status, con.name, con.id, con.email, con.last_name
				FROM my_dyo AS a LEFT JOIN social_member con ON a.userid = con.id
				WHERE con.name IS NOT NULL {$filter} GROUP BY a.userid
				ORDER BY a.id DESC LIMIT {$start},{$this->total_per_page}";		
		 */
		$sql = "SELECT bucket.*, sm.name, sm.last_name, sm.email, bucket.datetime, que.question, repo.files, repo.otherid,
				con.title, cat.category, repo.typealbum
				FROM my_bucket bucket
				LEFT JOIN social_member sm ON bucket.userid = sm.id
				LEFT JOIN question que ON bucket.taskid = que.id
				LEFT JOIN {$this->dbclass}_news_content con ON bucket.bucketid = con.id
				LEFT JOIN {$this->dbclass}_news_content_repo repo ON bucket.id = repo.otherid
				LEFT JOIN {$this->dbclass}_news_content_category cat ON con.categoryid = cat.id
				WHERE bucket.dochallenge =1
				AND bucket.point <> 0 ORDER BY bucket.datetime DESC LIMIT {$start},{$this->total_per_page}";
		$list = $this->fetch($sql,1);
		
		
		// $sqlGallery = "SELECT p.*, sm.name, sm.last_name, sm.email, bucket.datetime, repo.files, repo.otherid
				// FROM my_post p
				// LEFT JOIN social_member sm ON p.userid = sm.id
				// LEFT JOIN {$this->dbclass}_news_content con ON bucket.bucketid = con.id
				// LEFT JOIN {$this->dbclass}_news_content_repo repo ON bucket.id = repo.otherid
				// WHERE 1 {$filter} LIMIT {$start},{$this->total_per_page}";
				
		// pr($sql);
		
		if($list){
				
			$n=$start+1;
			foreach($list as $key => $val){
					$list[$key]['no'] = $n++;
					
			}
			
		}			
		// pr($list);
		$this->View->assign('list',$list);
		$this->Paging = new Paginate();
	
		$this->View->assign("paging",$this->Paging->getAdminPaging($start, $this->total_per_page, $total, "?s={$this->folder}&article_type={$article_type}&winStatus={$winStatus}&startdate={$startdate}&enddate={$enddate}"));	
		// pr("application/admin/{$this->folder}/{$this->folder}_list.html");
		return $this->View->toString("application/admin/{$this->folder}/{$this->folder}_pledge.html");
	
	}
	
	
	function downloadvideo()
	{
		global $CONFIG;
		
		$path = $CONFIG['LOCAL_PUBLIC_ASSET']."moveForward/".$_GET['video'];
		header('Accept-Ranges: bytes');  // For download resume
		header('Cache-Control: must-revalidate, post-check=0, pre-check=0' );
		header('Content-Description: File Transfer' );
		header('Content-Disposition: attachment; filename="'.basename( $path ).'"' );
		header('Content-Length: ' . filesize($path));  // File size
		header('Content-Transfer-Encoding: binary');  // For Gecko browsers mainly
		header('Content-Type: application/octet-stream' );
		header('Expires: 0' );
		header('Last-Modified: ' . gmdate('D, d M Y H:i:s', filemtime($path)) . ' GMT');
		header('Pragma: no-cache' );
		ob_clean();
		flush();
		readfile($path);
		
		return $this->View->toString("application/admin/{$this->folder}/{$this->folder}_list.html");
			
	}
	
	function downloadActivity()
	{
		global $CONFIG;
		
		$filter = "";
		$search = $this->_g("search") == NULL ? '' : $this->_g("search");
		$article_type = $this->_g("article_type") == NULL ? '' : $this->_g("article_type");
		$winStatus = $this->_g("winStatus") == NULL ? '' : $this->_g("winStatus");
		$startdate = $this->_g("startdate") == NULL ? '' : $this->_g("startdate");
		$enddate = $this->_g("enddate") == NULL ? '' : $this->_g("enddate");
		$filter .= $startdate=='' ? "" : "AND DATE(a.datetime) >= '{$startdate}' ";
		$filter .= $enddate=='' ? "" : "AND DATE(a.datetime) <= '{$enddate}' ";		
		$filter .= $search=='' ? "" : "AND (sm.name LIKE '%{$search}%' OR sm.last_name LIKE '%{$search}%')";
		$this->View->assign('search',$search);
		$this->View->assign('article_type',$article_type);
		$this->View->assign('winStatus',$winStatus);
		$this->View->assign('startdate',$startdate);
		$this->View->assign('enddate',$enddate);
					
		$artType = explode(',',$this->type);
		if ($article_type!='') {
			if(in_array($article_type,$artType)){ $filter .= $article_type=='' ? "" : "AND con.articleType='{$article_type}'";}
			else $filter .= "AND con.articleType IN ({$article_type}) ";
		}
		
		if($winStatus==2){
			$filter .=  " AND a.n_status='{$winStatus}' ";
		}
		
		if($winStatus==1){
			$filter .=  " AND a.n_status='{$winStatus}' ";
		}	
	
		$start = intval($this->_g('st'));
		
		/* Hitung banyak record data */
		$sql = "SELECT bucket.*, sm.name, sm.last_name, sm.email, bucket.datetime, que.question, repo.files, repo.otherid
				FROM my_bucket bucket
				LEFT JOIN social_member sm ON bucket.userid = sm.id
				LEFT JOIN question que ON bucket.taskid = que.id
				LEFT JOIN {$this->dbclass}_news_content con ON bucket.bucketid = con.id
				LEFT JOIN {$this->dbclass}_news_content_repo repo ON bucket.id = repo.otherid
				WHERE repo.gallerytype = 2 {$filter}   ORDER BY  bucket.datetime DESC ";
		// pr($sql);
		$totalList = $this->fetch($sql,1);	
		if($totalList){
		$total = count($totalList);
		}else $total = 0;
		// pr($total);
		/* list article */
		 // DATE_FORMAT(a.datetime,'%Y-%m-%d') 
		 
		/* $sql = "SELECT a.userid, a.image, a.datetime, a.n_status, con.name, con.id, con.email, con.last_name
				FROM my_dyo AS a LEFT JOIN social_member con ON a.userid = con.id
				WHERE con.name IS NOT NULL {$filter} GROUP BY a.userid
				ORDER BY a.id DESC LIMIT {$start},{$this->total_per_page}";		
		 */
		$sql = "SELECT bucket.*, sm.name, sm.last_name, sm.email, bucket.datetime, que.question, repo.files, repo.otherid,
				con.title, cat.category, repo.typealbum, repo.created_date, repo.link
				FROM my_bucket bucket
				LEFT JOIN social_member sm ON bucket.userid = sm.id
				LEFT JOIN question que ON bucket.taskid = que.id
				LEFT JOIN {$this->dbclass}_news_content con ON bucket.bucketid = con.id
				LEFT JOIN {$this->dbclass}_news_content_repo repo ON bucket.id = repo.otherid
				LEFT JOIN {$this->dbclass}_news_content_category cat ON con.categoryid = cat.id
				WHERE repo.gallerytype = 2 {$filter}  ORDER BY  bucket.datetime DESC LIMIT {$start},{$this->total_per_page}";
		$list = $this->fetch($sql,1);
		if ($list){
			$n = 1;
			foreach ($list as $key=>$val){
				$list[$key]['no'] = $n++;
			}
		}
		
		return $list;
			
	}
	
	function getMoveForwardReport(){
		
		global $CONFIG;
		
		$filename = "MoveForward-activity-report".date('Ymd_gia').".xls";
		header("Content-Type:   application/vnd.ms-excel; charset=utf-8");
		header("Content-Disposition: attachment; filename=$filename");  //File name extension was wrong
		header("Expires: 0");
		header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
		header("Cache-Control: private",false);
		// echo "Some Text"; //no ending ; here
		$resReport = $this->downloadActivity();
		
		// pr($resReport);
		// exit;
		$this->log->sendActivity("user printing moveforward activity report");
		// pr($resReport);	 		
		echo "<table border='1'>";	 	 		
		echo "<tr>";
			echo "<th class='head0'>No</th>";
			echo "<th class='head0'>Name</th>";
			echo "<th class='head0'>Email</th>";
			echo "<th class='head1'>Category</th>";
			echo "<th class='head1'>Task</th>";
			echo "<th class='head1'>Files</th>";
			echo "<th class='head1'>Link</th>";
			echo "<th class='head1'>Date</th>";
			echo "<th class='head1'>Date Verified</th>";
			echo "<th class='head1'>Status</th>";
		echo "</tr>";
		foreach ($resReport as $key => $val){			
			echo "<tr>";
				echo "<td>$val[no]</td>";
				echo "<td>$val[name]</td>";
				echo "<td>$val[email]</td>";
				echo "<td>$val[category]</td>";
				echo "<td>$val[question]</td>";
				echo "<td>".$CONFIG['BASE_DOMAIN_PATH'].'public_assets/moveForward/'.$val['files']."</td>";
				echo "<td>$val[link]</td>";
				echo "<td>$val[datetime]</td>";
				echo "<td>$val[dateverified]</td>";
				
				/* Status */
					if($val['n_status'] == 0) $status = 'Unverified';
					if($val['n_status'] == 1) $status = 'Verified';
					if($val['n_status'] == 2) $status = 'Rejected';			
				echo "<td>$status</td>";			
			echo "</tr>";
		}
		echo "</table>";
		exit;
	}
	
	function ajax() {
		
		global $LOCALE;
		global $CONFIG;
		$n_status = $this->_p('n_status');
		$id = $this->_p('id');
		$bucketid = $this->_p('bucketid');
		$userid = $this->_p('userid');
		$date = date("Y-m-d H:i:s");
		
		$sql = "UPDATE my_bucket SET n_status = '{$n_status}',  dateverified='{$date}' WHERE id = {$id} AND userid = {$userid} AND bucketid = {$bucketid} LIMIT 1 ";
		$res = $this->query($sql);
			if ($res){
			
				$getTaskList = $this->getTaskList($bucketid);
				
				$isCompleteChallenge = $this->challengeTaskComplete($bucketid, $userid);
				
				
				if($n_status==0)  $this->botManager->getPointByUser('challenge',10,$userid);
				if($n_status==1) $this->botManager->setPointByUser('CHALLENGE', 'challenge',10,'second', $userid);
				
				if (intval(count($isCompleteChallenge)) == intval(count($getTaskList))){
					sleep(1);
					if($n_status==0)  $this->botManager->getPointByUser('challenge',20,$userid);
					if($n_status==1)  $this->botManager->setPointByUser('CHALLENGE', 'challenge',20,'second', $userid);
					sleep(1);
					if($n_status==1) $this->log->sendUserActivity($userid, 'account', $LOCALE[1]['bucketactivityverifiedall']);
					
					
					$isCompleteAll = $this->allChallengeComplete($userid);
					if ($isCompleteAll){
						sleep(2);
						if($n_status==0)  $this->botManager->getPointByUser('challenge',30,$userid);
						if($n_status==1)  $this->botManager->setPointByUser('CHALLENGE', 'challengecomplete',30,'second', $userid);
						sleep(1);
						if($n_status==1) $this->log->sendUserActivity($userid, 'account', $LOCALE[1]['challengecomplete']);
					}
					
				}else{
					
					sleep(1);
					$subject = $LOCALE[1]['photorejected'];
					$content = $LOCALE[1]['bucketactivityrejected'];
					$sendNotif = $subject.' | '.$content;
					
					if($n_status==2) $this->log->sendUserActivity($userid, 'account', $sendNotif);
					if($n_status==1) $this->log->sendUserActivity($userid, 'account', $LOCALE[1]['bucketactivityverified']);
				}
				
				print json_encode(array('status'=>true));
			}else{
				print json_encode(array('status'=>false));
			}		
		
		exit;
	}
	
	function challengeTaskComplete($bucketid=false,$userid=false)
	{
		
		if (!$bucketid) return false;
		if (!$userid) return false;
		
		$sql = "SELECT * FROM my_bucket WHERE userid = {$userid} AND bucketid = {$bucketid}
				AND dateverified <> '0000-00-00' AND n_status = 1 ORDER BY id";
		// pr($sql);
		$res = $this->fetch($sql,1);
		if ($res){
		
			return $res;
		}
		
		return false;
		
	}
	
	function getTaskList($bucketid=false)
	{
		if (!$bucketid) return false;
		
		$sql = "SELECT * FROM question where otherid = {$bucketid} and n_status =1";
		// pr($sql);
		$res = $this->fetch($sql,1);
		if ($res){
		
			return $res;
		}
		
		return false;
	}
	
	function allChallengeComplete($userid=false)
	{
		
		if (!$userid) return false;
		
		$movefwd = $this->getMovefwd();
		
		if ($movefwd){
			foreach ($movefwd as $key => $value){
				
				$taskForthis = $this->getTaskList($value['id']);
				$isComplete = $this->challengeTaskComplete($value['id'], $userid);
				
				if ($isComplete){
				
					if ($isComplete[0]['dochallenge']==1 and $isComplete[0]['point']>0){
						$movefwd[$key]['complete'] = true;
						
					}else{
						if (intval(count($isComplete)) == intval(count($taskForthis))){
							$movefwd[$key]['complete'] = true;
						}else{
							$movefwd[$key]['complete'] = false;
						}
					}
					
				}
			
				
			}
			
			foreach ($movefwd as $value){
				
				if ($value['complete']){
					$completeAll[] = $value['id'];
				}
			}
			
			// complete all challenge 
			if (count($completeAll) == count($movefwd)) return true;
			
			return false;
		}
		
		
		return false;
		
	}
	
	function getMovefwd($id=false) {
	
		if ($id) $filter = " AND nc.id = {$id}";
		else $filter = "";
		
 		$sql = "
		SELECT nc.*,IF(nc.categoryid=0,'Current',cate.category) categoryname
		FROM {$this->dbclass}_news_content nc
		LEFT JOIN {$this->dbclass}_news_content_category cate ON cate.id=nc.categoryid
		WHERE nc.n_status = 1 
		AND nc.articleType = 30 
		{$filter} 
		ORDER BY nc.id ASC LIMIT 4";
		// pr($sql);
		$res = $this->fetch($sql,1);
			// pr($res);
		if ($res) {
			
			return $res;
		}
		return false;
	}
	
}