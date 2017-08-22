<?php

global $ENGINE_PATH;
include_once $ENGINE_PATH."Utility/Paginate.php";
		
class messages extends Admin{
	var $category;
	var $type;
	function __construct(){
		parent::__construct();

		$this->folder =  'messages';
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
		
		$filter .= $startdate=='' ? "" : "AND mm.datetime >= '{$startdate}' ";
		$filter .= $enddate=='' ? "" : "AND mm.datetime < '{$enddate}' ";		
		$filter .= $search=='' ? "" : "AND (sm.name LIKE '%{$search}%' OR sm.last_name LIKE '%{$search}%' OR sm.username LIKE '%{$search}%' OR sm.nickname LIKE '%{$search}%') ";
		
		$this->View->assign('search',$search);
		$this->View->assign('article_type',$article_type);
		$this->View->assign('startdate',$startdate);
		$this->View->assign('enddate',$enddate);
		
		$start = intval($this->_g('st'));
		
		/* HITUNG BANYAK RECORD DATA */
		$sql ="SELECT count(*) total FROM my_message as mm
				LEFT JOIN social_member sm ON mm.fromid = sm.id
				LEFT JOIN social_member smrecepient ON mm.recipientid = smrecepient.id
				WHERE mm.n_status = 1 {$filter}";
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
		$sql = "SELECT mm.id, sm.name fromname, sm.last_name fromnamelast, smrecepient.name recepientname, 
				smrecepient.last_name recepientnamelast ,mm.datetime, mm.message, mm.abuse
				FROM my_message AS mm
				LEFT JOIN social_member sm ON mm.fromid = sm.id
				LEFT JOIN social_member smrecepient ON mm.recipientid = smrecepient.id
				WHERE mm.n_status = 1 {$filter}
				ORDER BY mm.fromid, mm.recipientid, mm.datetime DESC 
				LIMIT {$start},{$this->total_per_page}";
		$list = $this->fetch($sql,1);
		// pr($sql);
		
		if($list){
			$n = $start+1;
			foreach($list as $key => $val){
				$list[$key]['no'] = $n++;
				list($register_date,$register_time) = explode(" ",$val['datetime']);
				$list[$key]['datetime'] = $register_date;
				//$list[$key]['datetime'] = $register_time;			
				/* if(is_file("{$CONFIG['BASE_DOMAIN']}public_assets/user/photo/{$val['img']}")) {
					$list[$key]['img'] = $val['img'];
				} else {
					$list[$key]['img'] = false;
				} */
				if($arrPages){
					if(array_key_exists($val['id'],$arrPages)) $list[$key]['typepages'] = $arrPages[$val['id']];
					else  $list[$key]['typepages'] = false;
				}
			}
		}
		// pr($list);
		$this->View->assign('list',$list);
		$this->Paging = new Paginate();	
		$this->View->assign("paging",$this->Paging->getAdminPaging($start, $this->total_per_page, $total, "?s={$this->folder}&startdate={$startdate}&enddate={$enddate}"));
		return $this->View->toString("application/admin/{$this->folder}/{$this->folder}_list.html");
	}
	
	function hapus(){
		$id = intval($this->_g('id'));
		if (strip_tags($this->_g('do')=="message")) {
			$set = 'SET abuse = 1';
		} 
		
		$sql = "UPDATE my_message {$set} WHERE id={$id}";
		// pr($sql);exit;
		if(!$this->query($sql)){
			return $this->View->showMessage('Failed',"index.php?s={$this->folder}");
		}else{
			return $this->View->showMessage('Success',"index.php?s={$this->folder}");
		}
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