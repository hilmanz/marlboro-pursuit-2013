<?php

global $ENGINE_PATH;
include_once $ENGINE_PATH."Utility/Paginate.php";
		
class userStatement extends Admin{
	var $category;
	var $type;
	function __construct(){
		parent::__construct();

		$this->folder =  'userStatement';
		$this->dbclass = 'marlborohunt';
		$this->total_per_page = 20;
	}
	
	function admin(){
		global $CONFIG;
	
		//GET ADMIN ROLE
		foreach($this->roler as $key => $val){
			$this->View->assign($key,$val);
		}
		
		//GET SPECIFIED ADMIN ROLE IF TRUE
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
		
		//HELPER
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
		//FILTER BOX
		$filter = "";
		$search = $this->_g("search") == NULL ? '' : $this->_g("search");
		$article_type = $this->_g("article_type") == NULL ? '' : $this->_g("article_type");
		$startdate = $this->_g("startdate") == NULL ? '' : $this->_g("startdate");
		$enddate = $this->_g("enddate") == NULL ? '' : $this->_g("enddate");
		$photoMod = (string) $this->_g("photoMod") == NULL ? '' : (string) $this->_g("photoMod");
		$filter .= $startdate=='' ? "" : "AND datetimes >= '{$startdate}' ";
		$filter .= $enddate=='' ? "" : "AND datetimes < '{$enddate}' ";		
		$filter .= $search=='' ? "" : "AND (description LIKE '%{$search}%' OR last_name LIKE '%{$search}%' OR username LIKE '%{$search}%' OR nickname LIKE '%{$search}%') ";
		
		$this->View->assign('search',$search);
		$this->View->assign('article_type',$article_type);
		$this->View->assign('photoMod',$photoMod);
		$this->View->assign('startdate',$startdate);
		$this->View->assign('enddate',$enddate);
		
		if($photoMod=="1"){
			$filter .=  " AND photo_moderation='{$photoMod}' ";
		}
		
		if($photoMod=="0"){
			$filter .=  " AND photo_moderation='{$photoMod}' ";
		}
		
		$start = intval($this->_g('st'));
		
		/* HITUNG BANYAK RECORD DATA */
		$sql = "SELECT count(*) total FROM social_statement WHERE 1 {$filter}";
		$totalList = $this->fetch($sql);
		
		if($totalList){
			$total = intval($totalList['total']);
		} else $total = 0;
		
		// GET DATA PAGES
		if ($this->getPages()) {
			foreach ($this->getPages() as $key =>$val) {
				list($pages,$ext) = explode(" ",$val['typepages']);
				$arrPages[$val['ownerid']] = $pages;
			}		
		}
		
		/* LIST USER MEMBER */
		$sql = "SELECT b.description, COUNT( * ) num
				FROM tbl_activity_log a, social_statement b
				WHERE a.action_value = b.id
				AND a.action_id = 47 {$filter}
				GROUP BY b.description
				ORDER BY b.id ASC LIMIT {$start},{$this->total_per_page}";
				
		/* $sql = "SELECT COUNT(*)num, ss.description, ms.datetimes
				FROM my_statement ms
				LEFT JOIN social_member sm ON ms.userid = sm.id
				LEFT JOIN social_statement ss ON ms.statementid = ss.id
				WHERE 1 {$filter} GROUP BY ms.statementid ORDER BY ms.datetimes
				DESC LIMIT {$start},{$this->total_per_page}"; */
		$list = $this->fetch($sql,1);
		// pr($sql);
		// pr($list);
		
		if($list){
			$n = $start+1;
			foreach($list as $key => $val){
				$list[$key]['no'] = $n++;
				// list($register_date,$register_time) = explode(" ",$val['register_date']);
				// $list[$key]['register_date'] = $register_date;
				// $list[$key]['register_time'] = $register_time;			
				/* if(is_file("{$CONFIG['BASE_DOMAIN']}public_assets/user/photo/{$val['img']}")) {
					$list[$key]['img'] = $val['img'];
				} else {
					$list[$key]['img'] = false;
				} */
				/* if($arrPages){
					if(array_key_exists($val['id'],$arrPages)) $list[$key]['typepages'] = $arrPages[$val['id']];
					else  $list[$key]['typepages'] = false;
				} */
			}
		}
		
		$this->View->assign('list',$list);
		$this->Paging = new Paginate();	
		$this->View->assign("paging",$this->Paging->getAdminPaging($start, $this->total_per_page, $total, "?s={$this->folder}&photoMod={$photoMod}&startdate={$startdate}&enddate={$enddate}"));
		return $this->View->toString("application/admin/{$this->folder}/{$this->folder}_list.html");
	}
	
	function hapus(){
		$id = intval($this->_g('id'));
		if (strip_tags($this->_g('do')=="photo")) {
			$set = 'SET img = ""';
		} else {
			$set = "SET n_status = 3";
		}
		
		$sql = "UPDATE social_member {$set} WHERE id={$id}";
		if(!$this->query($sql)){
			return $this->View->showMessage('Failed',"index.php?s={$this->folder}");
		}else{
			return $this->View->showMessage('Success',"index.php?s={$this->folder}");
		}
	}
	
	function publishAjax(){
		
		global $LOCALE;
		$id = $this->_p('id');
		$photo_moderation = $this->_p('photo_moderation');
			
		$sql = "UPDATE social_member SET photo_moderation = {$photo_moderation} WHERE id = {$id} LIMIT 1";
		// pr($sql);
		$qData = $this->query($sql);
		// pr($qData);
			if($qData){
				if($photo_moderation ==2){
					$messg = $LOCALE[1]['photouserrejected'];
					$this->log->sendUserActivity( $id , 'account' , $messg );
				}
				
				print json_encode(array('status'=>true));
			}else{
				print json_encode(array('status'=>false));
			}
			
			exit;
			
	}
	
	function getPages(){
		$sql = "SELECT mp.*,mpt.name as typepages 
			FROM my_pages mp
			LEFT JOIN my_pages_type mpt ON mp.type = mpt.id
			WHERE mp.n_status = 1
		";
		$pages = $this->fetch($sql,1);		
		return $pages;
	}
	
	
}