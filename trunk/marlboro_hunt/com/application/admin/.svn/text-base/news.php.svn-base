<?php

global $ENGINE_PATH;
include_once $ENGINE_PATH."Utility/Paginate.php";
include_once "botManager.php";
		
class news extends Admin{
	var $category;
	var $type;
	function __construct(){	
		parent::__construct();	
		
		$this->type = "1";
		$this->contentType = "0";
		$this->folder =  'news';
		$this->dbclass = 'marlborohunt';
		$this->fromwho = 0; // 0 is admin/backend
		$this->total_per_page = 10;
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
	
	

	function home(){
		
		//filter box
		$filter = "";
		$search = $this->_g("search") == NULL ? '' : $this->_g("search");
		$article_type = $this->_g("article_type") == NULL ? '' : $this->_g("article_type");
		$startdate = $this->_g("startdate") == NULL ? '' : $this->_g("startdate");
		$enddate = $this->_g("enddate") == NULL ? '' : $this->_g("enddate");
		$filter .= $startdate=='' ? "" : "AND con.posted_date >= '{$startdate}' ";
		$filter .= $enddate=='' ? "" : "AND con.posted_date < '{$enddate}' ";		
		$filter .= $search=='' ? "" : "AND (con.title LIKE '%{$search}%' OR con.brief LIKE '%{$search}%' OR con.content LIKE '%{$search}%') ";
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
		$sql ="
			SELECT count(*) total
			FROM {$this->dbclass}_news_content con
			LEFT JOIN {$this->dbclass}_news_content_type typ ON con.articleType = typ.id
			LEFT JOIN (SELECT contentid,COUNT(contentid) AS total_comment FROM {$this->dbclass}_news_content_comment GROUP BY contentid) tot ON con.id = tot.contentid WHERE con.n_status<>3
			AND con.articleType IN ({$this->type})		
			{$filter}";
		$totalList = $this->fetch($sql);

			
		/* Hitung total  article*/
		$list_totalComment = $this->fetch("SELECT contentid,COUNT(contentid) AS total_comment FROM {$this->dbclass}_news_content_comment GROUP BY contentid",1);	
		foreach ($list_totalComment as $key => $val) {
			$arrtotalcomment[$val['contentid']] = $val['total_comment'];
		}
		//pr($arrtotalcomment);
		// pr ($sql);
		if($totalList){
		$total = intval($totalList['total']);
		}else $total = 0;
		
		/* list article */
		$sql = "
			SELECT con.*,typ.type as type_name,tot.total_comment
			FROM {$this->dbclass}_news_content con
			LEFT JOIN {$this->dbclass}_news_content_type typ ON con.articleType = typ.id
			LEFT JOIN (SELECT contentid,COUNT(contentid) AS total_comment FROM {$this->dbclass}_news_content_comment GROUP BY contentid) tot ON con.id = tot.contentid
			WHERE con.n_status<>3
			AND con.articleType IN ({$this->type})
			{$filter}
			ORDER BY con.created_date DESC
			LIMIT {$start},{$this->total_per_page}
		";
	// pr($sql);
		$list = $this->fetch($sql,1);
						
			if($list){
			$n = $start+1;
			foreach($list as $key => $val){
				$list[$key]['no'] = $n++;
				// list($register_date,$register_time) = explode(" ",$val['datetime']);
				// $list[$key]['datetime'] = $register_date;
				//$list[$key]['datetime'] = $register_time;			
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
		// pr($list);
			
		
		$this->View->assign('list',$list);
		$this->Paging = new Paginate();	
		$this->View->assign("paging",$this->Paging->getAdminPaging($start, $this->total_per_page, $total, "?s={$this->folder}&article_type={$article_type}&startdate={$startdate}&enddate={$enddate}"));	
		// pr("application/admin/{$this->folder}/{$this->folder}_list.html");
		
		$totalComment=$this->totalComment();
		//pr($totalComment);
		$this->View->assign("total_comment",$totalComment);

		return $this->View->toString("application/admin/{$this->folder}/{$this->folder}_list.html");
	}
	
	function totalComment(){
	$id = $this->_g('id');
	$sql = "SELECT COUNT(*) AS total_comment FROM {$this->dbclass}_news_content_comment WHERE id={$id};";
	$type = $this->fetch($sql);
	return $type;
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
			 //pr($sql);
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
			$fromwho	= intval($this->_p('fromwho'));
			if($fromwho==0)  $fromwho = $this->fromwho;
		
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
														fromwho='{$fromwho}',
														authorid='{$authorid}',
														sourceurl='{$sourceurl}',														
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
	
	function hapus(){
		$id = $this->_g('id');
		if( !$this->query("UPDATE {$this->dbclass}_news_content SET n_status=3 WHERE id={$id}")){
			return $this->View->showMessage('Failed',"index.php?s={$this->folder}");
		}else{
			return $this->View->showMessage('Success',"index.php?s={$this->folder}");
		}
	}
	
	function comment(){
		global $CONFIG;
		
		$start 	= intval($this->_g('st'));
		$flag 	= strip_tags($this->_g('flag'));
		$search = $this->_g("search");
		$startdate = $this->_g("startdate");
		$enddate = $this->_g("enddate");
		$filter = $search ? "AND (cm.comment LIKE '%{$search}%' OR sm.name LIKE '%{$search}%' OR sm.last_name LIKE '%{$search}%') " : "";
		$filter .= $startdate ? "AND cm.date >= '{$startdate}' " : "";
		$filter .= $enddate ? "AND cm.date < '{$enddate}' " : "";
		$id 	= $this->_g('id');
		
		/* Hitung banyak record data */
		$sql ="SELECT COUNT(cm.id) AS total
			FROM {$this->dbclass}_news_content_comment cm
			LEFT JOIN social_member sm ON cm.userid = sm.id
			LEFT JOIN tbl_code_inventory tci ON CONCAT('commentpoint_',cm.id,'_gift') = tci.histories AND cm.userid=tci.userid
			WHERE cm.contentid = {$id} AND cm.n_status IN (1,2) {$filter}
			ORDER BY cm.date DESC
		";
		// pr($sql);
		$totalList = $this->fetch($sql);
		if($totalList){
			$total = intval($totalList['total']);
		} else $total = 0;

		//var_dump($totalList);
		$sql = "
			SELECT cm.id,cm.userid,cm.contentid,cm.comment,cm.date,cm.n_status,cm.parentid, cm.gid, sm.name,sm.last_name,IF(tci.n_status IS NULL,'-', tci.n_status ) as status_inventory,tci.histories
			FROM {$this->dbclass}_news_content_comment cm
			LEFT JOIN social_member sm ON cm.userid = sm.id
			LEFT JOIN tbl_code_inventory tci ON CONCAT('commentpoint_',cm.id,'_gift') = tci.histories AND cm.userid=tci.userid
			WHERE cm.contentid = {$id} AND cm.n_status IN (1,2) {$filter}
			ORDER BY cm.date DESC
			LIMIT {$start},{$this->total_per_page}
		";
		// pr($sql);
		$list = $this->fetch($sql,1);
		
		$n = $start+1;
		if ($list) {
		
			foreach($list as $key => $val){
				$sql = "
					SELECT comment
					FROM {$this->dbclass}_news_content_comment cm
					WHERE id = {$val['parentid']}
					LIMIT 1
				";
				$res = $this->fetch($sql);
				if ($res)$list[$key]['reply'] = $res['comment'];
				else $list[$key]['reply'] = '-';
				
				$sql1 = "
					SELECT title
					FROM {$this->dbclass}_news_content
					WHERE id = {$val['contentid']}
					LIMIT 1
				";
				$res1 = $this->fetch($sql1);
				if ($res1)$list[$key]['title'] = $res1['title'];
				else $list[$key]['title'] = '-';
				
				$sql2 = "
					SELECT files 
					FROM {$this->dbclass}_news_content_repo
					WHERE id = {$val['gid']}
					LIMIT 1
				";
				$res2 = $this->fetch($sql2);
				if ($res2)$list[$key]['asset'] = $res2['files'];
				else $list[$key]['asset'] = '-';
			}
			
			foreach($list as $key => $val){
				$val['no'] = $n++;
				$list[$key] = $val;
			}
		}
		
		// pr($list);
		$this->View->assign('st',$start);
		$this->View->assign('cid',$id);
		$this->View->assign('search',$search);
		$this->View->assign('startdate',$startdate);
		$this->View->assign('enddate',$enddate);
		$this->View->assign('list',$list);
		$this->View->assign('flag',$flag);
		$this->Paging = new Paginate();	
		$this->View->assign("paging",$this->Paging->getAdminPaging($start, $this->total_per_page, $total, "?s={$this->folder}&act=comment&id={$id}&flag={$flag}&search={$search}&startdate={$startdate}&enddate={$enddate}"));
		return $this->View->toString("application/admin/{$this->folder}/{$this->folder}_comment.html");
	}
	
	function ajaxCommentOld()
	{
		global $LOCALE;
		global $CONFIG;
		$n_status = $this->_p('n_status');
		$id = $this->_p('id');
		$userid = $this->_p('userid');
		$date = date("Y-m-d H:i:s");
		
		$sql = "UPDATE marlborohunt_news_content_comment SET n_status = '{$n_status}' WHERE id = {$id} AND userid = {$userid} LIMIT 1 ";
		$res = $this->query($sql);
			if ($res){
				$this->botManager->setPointByUser('NEWS', 'news',2,'day', $userid);
				print json_encode(array('status'=>true));
			}else{
				print json_encode(array('status'=>false));
			}		
		
		exit;
	
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
	
	function ajaxComment() {
		global $CONFIG;
		
		$id_comment = $this->_p('id');
		$userid = $this->_p('userid');
		$histories = $this->_p('histories');
		$datetimes = $this->_p('datetimes');
		
		$update	= false;
		
		$n_status = $this->_p('n_status');
	 	if ($n_status==2)$status_inventory = 6;
	 	if ($n_status==1)$status_inventory = 0;
		
		if ($n_status==2)$val_status = 0;
		if ($n_status==1)$val_status = 6;
		
			$sql = "
				SELECT id 
				FROM tbl_code_inventory 
				WHERE histories = '{$histories}' AND userid = {$userid} AND n_status = {$status_inventory} AND DATE(datetimes) = DATE('{$datetimes}') LIMIT 1
			";
			$qData = $this->fetch($sql);
			 
			if ($qData){
			// pr($qData);
				$id_inventory = $qData['id'];
				if (!$this->commentFreeDate($id_comment)){
					// if($this->isPost($id_comment,$userid)) {
					
						// $sql_inventory = "UPDATE tbl_code_inventory SET n_status = {$val_status} WHERE id = {$id_inventory}  LIMIT 1";
					
						// $res = $this->query($sql_inventory);
						
						
					// }
					$update = true;
				}
				
			}
				$sql_comment = "UPDATE {$this->dbclass}_news_content_comment SET n_status = {$n_status} WHERE id = {$id_comment}  LIMIT 1";
				$res2 = $this->query($sql_comment);
				// $res2 = true;
				if ($res2){
					// echo '1';
					if ($n_status==2){
						// echo '2';
						if ($update){
							// echo '3';
							$givePoint = $this->ifCommentVerified($id_comment, $userid);
						
							if ($givePoint){
								// pr($givePoint);
								
								$myPost = 0;
								foreach ($givePoint as $val){
									if (!$this->commentFreeDate($id_comment)){
										
										if($this->commentCaps(10,$val)) {
											// $this->botManager->setPointByUser('EVENTCOMMAND', 'movefwdpost',1,'second', $val);
											if ($myPost==0){
												$sql_inventory = "UPDATE tbl_code_inventory SET n_status = {$val_status} 
													WHERE userid = {$userid} AND n_status = 6 AND id = {$id_inventory} LIMIT 1";
											}else{
												$sql_inventory = "UPDATE tbl_code_inventory SET 
													n_status = {$val_status}, point = 1 
													WHERE userid = {$userid} AND n_status = 6 LIMIT 1";

											}
											
											// pr($sql_inventory);
											$res = $this->query($sql_inventory);
											sleep(1);
										}
									}
									
									$myPost++;
									
								}
							}
							
						}
						
						
						/*
						
						*/
					}else{
						$givePoint = $this->ifCommentVerified($id_comment, $userid);
						
						if ($givePoint){
							
							foreach ($givePoint as $val){
							
								if (!$this->commentFreeDate($id_comment)){
									
									// if($this->commentCaps(10,$val)) {
										$this->botManager->getPointByUser('movefwdpost',1,$val);
										sleep(1);
									// }
								}
								
								
							}
						}
					}
					// exisst;
					print json_encode(array('status'=>true));
				} else {
					print json_encode(array('status'=>FALSE));
				}
				 
		 
		exit;
	}
	
	function isPost($id=false,$userid=false)
	{
		if (!$id) return false;
		if (!$userid) return false;
		
		$sql = "SELECT parentid FROM {$this->dbclass}_news_content_comment
				WHERE id = {$id} AND userid = {$userid}  LIMIT 1 ";
		$res = $this->fetch($sql);
		if ($res['parentid']==0) return true;
		return false;
	}
	
	function ifCommentVerified($id=false, $userid=false,$n_status=2)
	{
		
		if (!$id) return false;
		if (!$userid) return false;
		
		$useridlist = array();
		$checkParent = "SELECT parentid FROM {$this->dbclass}_news_content_comment
						WHERE id = {$id} AND userid = {$userid}  LIMIT 1 ";
		// pr($checkParent);
		$resCheck = $this->fetch($checkParent);
		if ($resCheck){
			
			$useridlist[] = $userid;
			
			if ($resCheck['parentid']){
				
				// got parent (this a comment)
				
				$checkSubParent = "SELECT userid FROM {$this->dbclass}_news_content_comment
									WHERE id = {$resCheck['parentid']}  LIMIT 1 ";
				// pr($checkSubParent);
				$ressubCheck = $this->fetch($checkSubParent);
				if ($ressubCheck){
					
					$useridlist[] = $ressubCheck['userid'];
					
					// if ($ressubCheck['parent']){
					
						// $checkSubsubParent = "SELECT userid FROM my_post WHERE id = {$ressubCheck['parent']} AND n_status = 1  LIMIT 1 ";
						// $ressubsubCheck = $this->fetch($checkSubsubParent);
						// if ($ressubsubCheck){
						
							// $useridlist[] = $ressubsubCheck['userid'];
						// }
					// }
					
					
				}
				
			}
		}
		
		return $useridlist;
	}
	
	function commentFreeDate($id)
	{
		
		if(!$id) return true;
		$sql = "SELECT date FROM {$this->dbclass}_news_content_comment WHERE id = {$id} LIMIT 1";
		$qData = $this->fetch($sql);
		if ($qData){
			
			// $saturday = date('Y-m-d',strtotime('friday this week'));
			// $sunday = date('Y-m-d',strtotime('sunday this week'));
			
			$date = strtotime($qData['date']);
			$saturday =  date('l', $date) == 'Saturday';
			$sunday = date('l', $date) == 'Sunday';
			
			if ($saturday or $sunday) return true;
			return false;
		}
		
		return true;
			
		/* for debug mode 
		
		if (($today <= $sunday) and ($today >=$saturday)){
			print json_encode(array('status'=>true,'start'=>$saturday,'end'=>$sunday));
		}else{
			print json_encode(array('status'=>false,'start'=>$saturday,'end'=>$sunday));
		}
		exit;
		*/
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
							return $this->View->showMessage(" {$this->folder}  Failed to Upload", "index.php?s=banner");
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
		if($this->query("UPDATE {$this->dbclass}_news_content SET image='{$img}' WHERE id={$id}")){
			$this->newsImageUpload($id,$img);
		}
		
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
	
	function downloadreport_old(){
		$this->total_per_page = 10;
		$sql = "SELECT * FROM {$this->dbclass}_news_content con";
		$this->open(0);
		$list = $this->fetch($sql,1);
		$this->close();	
		
		$export_file = "Article_".date('Y-m-d').".xls";
		ob_end_clean();
		ini_set('zlib.output_compression','Off');
	   
		header('Pragma: public');
		header("Expires: Sat, 26 Jul 1997 05:00:00 GMT");                  // Date in the past   
		header('Last-Modified: '.gmdate('D, d M Y H:i:s') . ' GMT');
		header('Cache-Control: no-store, no-cache, must-revalidate');     // HTTP/1.1
		header('Cache-Control: pre-check=0, post-check=0, max-age=0');    // HTTP/1.1
		header ("Pragma: no-cache");
		header("Expires: 0");
		header('Content-Transfer-Encoding: none');
		header('Content-Type: application/vnd.ms-excel;');                 // This should work for IE & Opera
		header("Content-type: application/x-msexcel");                    // This should work for the rest
		header('Content-Disposition: attachment; filename="'.basename($export_file).'"'); 
		$this->View->assign('list',$list);
		print $this->View->toString("application/admin/{$this->folder}/{$this->folder}_list.html");
		exit;
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
	function newsImageUpload($newsID=null,$img=null){
		//frame-news-photo.png
		global $CONFIG;

		if($newsID!=null&&$img!=null){
			$dest = imagecreatefrompng($CONFIG['UPLOAD_ASSET'].'frame-news-photo.png');
			$src = imagecreatefromjpeg($CONFIG['UPLOAD_ASSET'].'news/'.$img);
			$src_link = $CONFIG['UPLOAD_ASSET'].'news/'.$img;

			$image_info = getimagesize($src_link);

			list($width,$height)=getimagesize($src_link);

			imagealphablending($dest, false);
			imagesavealpha($dest, true);


			
	        if ($height > $width) {   // If Height Is Greater Than Width 
	           $zoom = 300 / $height;   // Length Ratio For Width 
	           $newheight = 300;   // Height Is Equal To Max Height 
	           $newwidth = $width * $zoom;   // Creates The New Width 
	           $cheight = ($newheight/2);
	           $cwidth = 0;
	        } else {    // Otherwise, Assume Width Is Greater Than Height (Will Produce Same Result If Width Is Equal To Height) 
		          $zoom = 300 / $width;   // Length Ratio For Height 
		          $newwidth = 300;   // Width Is Equal To Max Width 
		          $newheight = $height * $zoom;   // Creates The New Height 
		          $cheight = 0;
	           $cwidth = ($newwidth/2);
	        }         

	       
			

			$new_image = imagecreatetruecolor(178, 178);
			//imagecopy($new_image, $src, 0, 0, $newwidth, $newheight, $width, $height);
			imagecopyresampled($new_image, $src, 0, 0, $cwidth, $cheight, $newwidth, $newheight, $width, $height);
			imagecopymerge($dest, $new_image, 15, 20, 0, 0, 178, 178, 100); //have to play with these numbers for it to work for you, etc.
			
			$savePath = $CONFIG['UPLOAD_ASSET'].'customCrop/news_'.$newsID.'.png';

			imagepng($dest,$savePath);
			//imagejpeg($dest,$savePath,100);
			imagedestroy($dest);
			imagedestroy($src);
			imagedestroy($new_image);
		}
	}
	
		
	function commentCaps($caps=10,$uid=false){
			if($uid==false)return false;
			$datetimes = date("Y-m-d");
			
			$total = 0;
			// count comment in news_comment
			
			$sql = "SELECT id,date,count(*) total FROM {$this->dbclass}_news_content_comment WHERE userid={$uid} AND DATE(date) = '{$datetimes}' AND n_status = 2 ORDER BY id DESC LIMIT 1";
			// pr($sql);
			$qData = $this->fetch($sql);
			$totalNews = 0;
			if($qData){
				$totalNews = intval($qData['total']);
			 
			}
			
			// count comment in my_post
			
			$sql = "SELECT id,datetime,count(*) total FROM my_post WHERE userid={$uid} AND DATE(datetime) = '{$datetimes}' AND n_status = 1 ORDER BY id DESC LIMIT 1";
			// pr($sql);
			$qData = $this->fetch($sql);
			$totalPost = 0;
			if($qData){
				$totalPost = intval($qData['total']);
			 
			}
			
			$total = intval($totalNews + $totalPost);
			if($caps>=$total)	return true;
			else return false;
	}

	function hacknewscomment(){
		$sql = "SELECT cc.id,cc.contentid, cc.date, cc.gid, cc.comment
				FROM {$this->dbclass}_news_content_comment cc
				WHERE cc.gid = 0
				ORDER BY cc.id";
		// pr($sql);
		$qData = $this->fetch($sql,1);
		//pr($qData);
		foreach ($qData as $key => $value) {

			$sql="SELECT *, {$value['contentid']} AS cid, '{$value['comment']}' AS comment
					FROM {$this->dbclass}_news_content_repo
					WHERE otherid = {$value['contentid']} AND DATE(created_date) <= DATE('{$value['date']}')
					AND n_status IN (1,3)
					ORDER BY  created_date DESC LIMIT 1
				 ";
			$rs = $this->fetch($sql);
			if($rs){
				$sql = "UPDATE {$this->dbclass}_news_content_comment
						SET gid={$rs['id']} WHERE id={$value['id']}";
				$this->query($sql);
				//pr($sql);
			}
		}
		exit;
	}
	
}
