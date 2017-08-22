<?php

global $ENGINE_PATH;
include_once $ENGINE_PATH."Utility/Paginate.php";
		
class comment extends Admin{
	var $category;
	var $type;
	function __construct(){
		parent::__construct();

		$this->folder =  'comment';
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
		$filter .= $search=='' ? "" : "AND (sm.name LIKE '%{$search}%' OR sm.last_name LIKE '%{$search}%' OR gl.level LIKE '%{$search}%' OR mm.message LIKE '%{$search}%') OR mm.title LIKE '%{$search}%' ";
		
		$this->View->assign('search',$search);
		$this->View->assign('article_type',$article_type);
		$this->View->assign('startdate',$startdate);
		$this->View->assign('enddate',$enddate);
		
		$start = intval($this->_g('st'));
		
		/* HITUNG BANYAK RECORD DATA */
		$sql ="SELECT count(*) total FROM my_message AS mm
				LEFT JOIN social_member AS sm ON a.fromid = b.id
				LEFT JOIN gm_member AS gm ON a.recipientid = c.adminid
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
		$sql = "SELECT mm.*
				FROM my_message AS mm
				WHERE mm.message IS NOT NULL {$filter}
				GROUP BY mm.id
				ORDER BY mm.datetime DESC LIMIT {$start},{$this->total_per_page}";
		$list = $this->fetch($sql,1);
		// pr($list);
		
		if($list){
			$n = $start+1;
			$admindata=false;
			$socialdata=false;
			$arrsocial=false;
			$arradmin=false;
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
				
				if($val['fromwho']==0) $admindata[$val['fromid']] =$val['fromid'];
				else $socialdata[$val['fromid']] =$val['fromid'];
				
				 
				
			}
			
			
			if($socialdata){
				$strsocial = implode(',',$socialdata);
				$sql ="SELECT name,id FROM social_member WHERE id IN ({$strsocial}) LIMIT 20 ";
				$socialq = $this->fetch($sql,1);
				if($socialq){
					foreach($socialq as $val){
						$arrsocial[$val['id']] = $val;
					}
				}
			}
			
			if($admindata){
				$stradmin = implode(',',$admindata);
				$sql ="SELECT name,userID FROM gm_user WHERE userID IN ({$stradmin}) LIMIT 20 ";
				// pr($sql);
				$adminq = $this->fetch($sql,1);
				if($adminq){
					foreach($adminq as $val){
						$arradmin[$val['userID']] = $val;
					}
				}
			}
			
			
			foreach($list as $key => $val){
			
				$list[$key]['from'] = false;
				$list[$key]['recepient'] = false;
				
				if($val['fromwho']==0) 	{
					if($arradmin)if(array_key_exists($val['fromid'],$arradmin)) $list[$key]['from'] = $arradmin[$val['fromid']];	
				}else if($arrsocial) if(array_key_exists($val['fromid'],$arrsocial)) $list[$key]['from'] = $arrsocial[$val['fromid']];

				 		
				
			}
			
		}
		// pr($list);exit;
		
		$this->View->assign('list',$list);
		$this->Paging = new Paginate();	
		$this->View->assign("paging",$this->Paging->getAdminPaging($start, $this->total_per_page, $total, "?s={$this->folder}&startdate={$startdate}&enddate={$enddate}"));
		return $this->View->toString("application/admin/{$this->folder}/{$this->folder}_list.html");
	}
	
	function reply(){
		global $CONFIG;
		// pr($_POST);
		// exit;
		$tes = $this->_p('simpan');
		
		if(isset($tes)){
			$date = date("Y-m-d H:i:s");
			
			$data['title'] 			= $this->_p('title');
			$data['message'] 			= $this->_p('message');
			$data['inbox'] 			= $this->_p('topcontent0');
			$data['notif'] 			= $this->_p('topcontent1');
			$data['newsfeed'] 			= $this->_p('topcontent2');
		
			foreach($data as $key => $val){
					$$key= $val;
			}
			
			if($this->_p('simpan')){

				
				if ($inbox){
					// insert ke my message
					$sql = "INSERT INTO my_message (fromid, parentid, recipientid, fromwho, type, subject, message, datetime) VALUES ('0','0','0','0','0','{$title}','{$message}','{$date}' )";
					$res = $this->query($sql);
					// pr($sql);
					
				}				
				
				if ($notif){
					// insert ke log action id = 56
					$sql = "INSERT INTO my_message (fromid, parentid, recipientid, fromwho, type, subject, message, datetime) VALUES ('0','0','0','0','1','{$title}','{$message}','{$date}' )";
					$res = $this->query($sql);
					// pr($sql);
				}
				
				if ($newsfeed){
					// insert ke log action id = 55
					$sql = "INSERT INTO my_message (fromid, parentid, recipientid, fromwho, type, subject, message, datetime) VALUES ('0','0','0','0','2','{$title}','{$message}','{$date}' )";
					$res = $this->query($sql);
					// pr($sql);
				}
				// pr($sql);
				return $this->View->showMessage('Berhasil', "index.php?s={$this->folder}");
			}
		}
		
		return $this->View->toString("application/admin/{$this->folder}/{$this->folder}_new.html");
	}
	
	function hapus(){
		$id = $this->_g('id');
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