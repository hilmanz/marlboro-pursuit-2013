<?php

global $ENGINE_PATH;
include_once $ENGINE_PATH."Utility/Paginate.php";
include_once "botManager.php";

class dyo extends Admin{
	var $category;
	var $type;
	function __construct(){	
		parent::__construct();	
		
		$this->type = "1,3,4,5,6";
		$this->contentType = "0";
		$this->folder =  'dyo';
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
		$filter .= $search=='' ? "" : "AND (con.name LIKE '%{$search}%' OR con.last_name LIKE '%{$search}%')";
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
		$sql = "SELECT COUNT(*) total FROM my_dyo AS a LEFT JOIN social_member con ON a.userid = con.id
				WHERE con.name IS NOT NULL {$filter} GROUP BY a.userid
				ORDER BY a.id DESC";
		$totalList = $this->fetch($sql,1);	
		if($totalList){
		$total = count($totalList);
		}else $total = 0;
		// pr($total);
		/* list article */
		 // DATE_FORMAT(a.datetime,'%Y-%m-%d') 
		$sql = "SELECT a.userid, a.image, a.datetime, a.n_status, con.name, con.id, con.email, con.last_name
				FROM my_dyo AS a LEFT JOIN social_member con ON a.userid = con.id
				WHERE con.name IS NOT NULL {$filter} GROUP BY a.userid
				ORDER BY a.id DESC LIMIT {$start},{$this->total_per_page}";		
		$list = $this->fetch($sql,1);
		// pr($sql);
		if($list){
				
			$n=$start+1;
			foreach($list as $key => $val){
					$list[$key]['no'] = $n++;
					
			}
			
		}			
		
		$this->View->assign('list',$list);
		$this->Paging = new Paginate();
	
		$this->View->assign("paging",$this->Paging->getAdminPaging($start, $this->total_per_page, $total, "?s={$this->folder}&article_type={$article_type}&winStatus={$winStatus}&startdate={$startdate}&enddate={$enddate}"));	
		// pr("application/admin/{$this->folder}/{$this->folder}_list.html");
		return $this->View->toString("application/admin/{$this->folder}/{$this->folder}_list.html");
	}
	
	function getMyGallery(){
		
		$userid = intval($this->_p('userid'));
		$sql = "SELECT a.*,a.id as dyoids,b.email
				FROM my_dyo AS a
				LEFT JOIN social_member AS b ON a.userid = b.id
				WHERE userid={$userid} ORDER BY datetime DESC ";
		$qData = $this->fetch($sql,1);
		$sqlList = "SELECT COUNT( * ) totalVote, contentid dyoid
					FROM marlborohunt_news_content_favorite
					GROUP BY dyoid";
		$voteData = $this->fetch($sqlList,1);
		// pr($sql);
		if($qData){
			foreach($voteData as $val){
				$voteDatas[$val['dyoid']] = $val['totalVote'];
			}
			foreach($qData as $key => $val){
				if(array_key_exists($val['dyoids'],$voteDatas)) $qData[$key]['vote'] =$voteDatas[$val['dyoids']];
				else $qData[$key]['vote'] = 0;
			}
			$this->log->sendActivity("user select dyo profile",$userid);
			if($qData) print json_encode($qData);
			else print json_encode(false);
		}else print json_encode(false);
		// pr($qData);
		exit;
	
	}
	
	
	function ajax() {
		
		global $LOCALE;
		$n_status = $this->_p('n_status');
		$userid = $this->_p('userid');
		$id = $this->_p('id');
		$date = date("Y-m-d");
		if ($n_status == 0){
			
			// $checkWin = $this->checkDyoWinnerOnWeek();
			// pr($checkWin);
			// if ($checkWin['id'] == $id){
				// update data berdasarkan id ke nol
				$sql = "UPDATE my_dyo SET n_status = 1, datetimeupdate='{$date}' WHERE id = {$id} LIMIT 1 ";
				$qData = $this->query($sql);
				
				$this->log->sendActivity("dyo cancel winner",$id);
				print json_encode(array('status'=>1));
			// }
		}
		
		if($n_status == 2){
			
			$checkWin = $this->checkDyoWinnerOnWeek();
			// pr($checkWin);
			if($checkWin['total'] >= 2){
				$this->log->sendActivity("dyo already have winner",$id);
				print json_encode(array('status'=>2));
			}else{
				$sql = "UPDATE my_dyo SET n_status = 2, datetimeupdate='{$date}' WHERE id = {$id}";
				$qData = $this->query($sql);
				$this->botManager->setPointByUser('DYOWINNER', 'dyowinnerpoint',10, null, $userid);
				// $this->log->sendActivity("dyo add winner",$id);
				sleep(1);
				$this->log->sendUserActivity($userid, 'account', $LOCALE[1]['winnerdyo']);
				
				
				print json_encode(array('status'=>0));
			}
		
		} 
		
		/*
			0 = berhasil update n_status ke 2
			1 = berhasil update n_status = 2 ke n_status = 1
			2 = data pemenang sudah ada jadi gk bisa check lagi
		*/
		exit;
	}
	
	function checkDyoWinnerOnWeek()
	{
		$date = date("Y-m-d");
		$sql = "SELECT COUNT(*) total FROM my_dyo WHERE WEEK(datetimeupdate) = WEEK('{$date}') AND n_status = 2";
		
		$res = $this->fetch($sql);
		if ($res){
			return $res;
		}
		return false;
		
		
	}
	
	function getdyoreport(){
		
		$filename = "dyo_report_".date('Ymd_gia').".xls";
		header("Content-Type:   application/vnd.ms-excel; charset=utf-8");
		header("Content-Disposition: attachment; filename=$filename");  //File name extension was wrong
		header("Expires: 0");
		header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
		header("Cache-Control: private",false);
		// echo "Some Text"; //no ending ; here
		$resReport = $this->reportQuery();
		$this->log->sendActivity("user printing dyo report");
		// pr($resReport);
		echo "<table border='1'>";
		echo "<tr>";
			echo "<td>No</td>";
			echo "<td>Name</td>";
			echo "<td>Email</td>";
			echo "<td>Date</td>";
			echo "<td>Vote</td>";
			echo "<td>Image</td>";
		echo "</tr>";
		foreach ($resReport as $key => $val){			
			echo "<tr>";
				echo "<td>$val[no]</td>";
				echo "<td>$val[names] $val[last_name]</td>";
				echo "<td>$val[email]</td>";
				echo "<td>$val[dates]</td>";
				echo "<td>$val[votes]</td>";
				echo "<td>$val[images]</td>";
			echo "</tr>";
		}
		echo "</table>";
		exit;
	}
	
	function reportQuery() {
		
		//filter box
		$filter = "";
		$search = $this->_g("search") == NULL ? '' : $this->_g("search");
		$article_type = $this->_g("article_type") == NULL ? '' : $this->_g("article_type");
		$winStatus = $this->_g("winStatus") == NULL ? '' : $this->_g("winStatus");
		$startdate = $this->_g("startdate") == NULL ? '' : $this->_g("startdate");
		$enddate = $this->_g("enddate") == NULL ? '' : $this->_g("enddate");
		$filter .= $startdate=='' ? "" : "AND DATE(a.datetime) >= '{$startdate}' ";
		$filter .= $enddate=='' ? "" : "AND DATE(a.datetime) <= '{$enddate}' ";		
		$filter .= $search=='' ? "" : "AND (con.name LIKE '%{$search}%' OR con.last_name LIKE '%{$search}%')";
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
	
		$sql = "SELECT a.userid, a.image, a.datetime dates, a.n_status, con.name names, con.id, con.email, con.last_name , con.email,a.id dyoids,
				CONCAT('https://www.marlboro.ph/public_assets/user/dyo/',a.image) images
				FROM my_dyo AS a LEFT JOIN social_member con ON a.userid = con.id
				WHERE con.name IS NOT NULL {$filter} ORDER BY a.datetime DESC";		
		$list = $this->fetch($sql,1);
		// pr($sql);
		if($list){
			$sqlList = "SELECT COUNT( * ) totalVote, contentid dyoid
						FROM {$this->dbclass}_news_content_favorite
						GROUP BY dyoid";
			$voteData = $this->fetch($sqlList,1);
			// pr($sql);
			if($voteData){
				foreach($voteData as $val){
					$voteDatas[$val['dyoid']] = $val['totalVote'];
				}
				
			}
			
			$n=$start+1;
			foreach($list as $key => $val){
					$list[$key]['no'] = $n++;
					if(array_key_exists($val['dyoids'],$voteDatas)) $list[$key]['votes'] =$voteDatas[$val['dyoids']];
					else $list[$key]['votes'] = 0;
					
			}
			
		}	

		return $list;		
		
	}
	
}