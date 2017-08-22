<?php

global $ENGINE_PATH;
include_once $ENGINE_PATH."Utility/Paginate.php";
include_once "botManager.php";

class allComment extends Admin{
	var $category;
	var $type;
	function __construct(){	
		parent::__construct();	
		
		$this->type = "1,3,4,5,6";
		$this->contentType = "0,3";
		$this->folder =  'allComment';
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
	
	function home (){

		//filter box
		$filter = "";		
		$st = $this->_g('st');
		$search = $this->_g("search") == NULL ? '' : $this->_g("search");
		$article_type = $this->_g("article_type") == NULL ? '' : $this->_g("article_type");
		$startdate = $this->_g("startdate") == NULL ? '' : $this->_g("startdate");
		$enddate = $this->_g("enddate") == NULL ? '' : $this->_g("enddate");
		$filter .= $startdate=='' ? "" : "AND DATE(a.datetime) >= '{$startdate}' ";
		$filter .= $enddate=='' ? "" : "AND DATE(a.datetime) <= '{$enddate}' ";		
		$filter .= $search=='' ? "" : "AND (sm.name LIKE '%{$search}%' OR sm.last_name LIKE '%{$search}%')";
		$this->View->assign('search',$search);
		$this->View->assign('article_type',$article_type);
		$this->View->assign('startdate',$startdate);
		$this->View->assign('enddate',$enddate);
					
		$artType = explode(',',$this->type);
		if ($article_type!='') {
			if(in_array($article_type,$artType)){ $filter .= $article_type=='' ? "" : "AND con.articleType='{$article_type}'";}
			else $filter .= "AND con.articleType IN ({$article_type}) ";
		} 	
	
		$start = intval($this->_g('st'));
		
		/* Hitung banyak record data */
		$sql ="SELECT COUNT(*) total FROM {$this->dbclass}_news_content_comment cm 
			LEFT JOIN social_member sm ON cm.userid = sm.id
			LEFT JOIN tbl_code_inventory tci ON CONCAT('commentpoint_',cm.id,'_gift') = tci.histories AND cm.userid=tci.userid
			LEFT JOIN {$this->dbclass}_news_content con ON cm.contentid = con.id
			WHERE cm.n_status IN (1,2) {$filter} AND con.articleType IN ({$this->type})	
			ORDER BY cm.date DESC
		";
		// pr($sql);
		$totalList = $this->fetch($sql);	
		if($totalList){
		$total = intval($totalList['total']);
		}else $total = 0;
		// pr($total);
		/* list article */
		 // DATE_FORMAT(a.datetime,'%Y-%m-%d') 
		 
		/* $sql = "SELECT a.userid, a.image, a.datetime, a.n_status, con.name, con.id, con.email, con.last_name
				FROM my_dyo AS a LEFT JOIN social_member con ON a.userid = con.id
				WHERE con.name IS NOT NULL {$filter} GROUP BY a.userid
				ORDER BY a.id DESC LIMIT {$start},{$this->total_per_page}";		
		 */
		$sql = "
			SELECT cm.id,cm.userid,cm.contentid,cm.comment,cm.date,cm.n_status,sm.name,sm.last_name,IF(tci.n_status IS NULL,'-', tci.n_status ) as status_inventory,tci.histories
			FROM {$this->dbclass}_news_content_comment cm 
			LEFT JOIN social_member sm ON cm.userid = sm.id
			LEFT JOIN tbl_code_inventory tci ON CONCAT('commentpoint_',cm.id,'_gift') = tci.histories AND cm.userid=tci.userid
			LEFT JOIN {$this->dbclass}_news_content con ON cm.contentid = con.id
			WHERE cm.n_status IN (1,2) {$filter} AND con.articleType IN ({$this->type})	
			LIMIT {$start},{$this->total_per_page}
		";
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
		$this->View->assign('st',$start);
		$this->View->assign('list',$list);
		$this->Paging = new Paginate();
	
		$this->View->assign("paging",$this->Paging->getAdminPaging($start, $this->total_per_page, $total, "?s={$this->folder}&article_type={$article_type}&startdate={$startdate}&enddate={$enddate}"));	
		// pr("application/admin/{$this->folder}/{$this->folder}_list.html");
		return $this->View->toString("application/admin/{$this->folder}/{$this->folder}_list.html");
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
	
	function ajaxComment() {
		global $CONFIG;
		
		$id_comment = $this->_p('id');
		$userid = $this->_p('userid');
		$histories = $this->_p('histories');
		$status_inventory = $this->_p('status_inventory');
		$val_status = $this->_p('val_status');
		$n_status = $this->_p('n_status');
	 
			$sql = "
				SELECT id 
				FROM tbl_code_inventory 
				WHERE histories = '{$histories}' AND userid = {$userid} AND n_status = {$status_inventory} LIMIT 1
			";
			$qData = $this->fetch($sql);
			// pr($qData);

			if ($qData){
				$id_inventory = $qData['id'];
				
				$sql_inventory = "UPDATE tbl_code_inventory SET n_status = {$val_status} WHERE id = {$id_inventory}  LIMIT 1";
				$res = $this->query($sql_inventory);
			}
				$sql_comment = "UPDATE {$this->dbclass}_news_content_comment SET n_status = {$n_status} WHERE id = {$id_comment}  LIMIT 1";
				$res2 = $this->query($sql_comment);
				
				if ($res2){
					print json_encode(array('status'=>true));
				} else {
					print json_encode(array('status'=>FALSE));
				}
				 
		 
		exit;
	}
	
	function getTypeList(){
		$sql = "SELECT * FROM {$this->dbclass}_news_content_type WHERE id IN ({$this->type}) AND  content IN ({$this->contentType}) ";
		$type = $this->fetch($sql,1);
		// pr($type);exit;
		return $type;
	}
	
	function hapusComment(){
		$id = $this->_g('id');
		$cid = $this->_g('cid');
		$st = $this->_g('st');
		$search = $this->_g('search');
		$startdate = $this->_g('startdate');
		$enddate = $this->_g('enddate');
		
		$sql = "UPDATE {$this->dbclass}_news_content_comment SET n_status = 3 WHERE id={$id}";
		if($this->query($sql)){
			return $this->View->showMessage('Berhasil',"index.php?s={$this->folder}&act=comment&id={$cid}&search={$search}&startdate={$startdate}&enddate={$enddate}&st={$st}");
		} else {
			return $this->View->showMessage('Gagal',"index.php?s={$this->folder}&act=comment&id={$cid}&search={$search}&startdate={$startdate}&enddate={$enddate}&st={$st}");
		}
		return $this->View->toString("application/admin/{$this->folder}/{$this->folder}_comment.html");
	}
	
}