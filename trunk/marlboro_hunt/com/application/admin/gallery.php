<?php

global $ENGINE_PATH;
include_once $ENGINE_PATH."Utility/Paginate.php";
include_once "botManager.php";
		
class gallery extends Admin{
	function __construct(){	
		parent::__construct();	
		
		$this->type = "1,5";
		$this->contentType = "0,3";
		$this->folder =  'gallery';
		$this->dbclass = 'marlborohunt';
		$this->fromwho = 0; // 0 is admin/backend
		$this->total_per_page = 20;
		// $this->botHelper = $this->useHelper('botHelper');
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
		$contentid = $this->_g("contentid") == NULL ? '' : $this->_g("contentid");
		$startdate = $this->_g("startdate") == NULL ? '' : $this->_g("startdate");
		$enddate = $this->_g("enddate") == NULL ? '' : $this->_g("enddate");
		$filter .= $startdate=='' ? "" : "AND con.posted_date >= '{$startdate}' ";
		$filter .= $enddate=='' ? "" : "AND con.posted_date < '{$enddate}' ";		
		$filter .= $search=='' ? "" : "AND (con.title LIKE '%{$search}%' OR con.brief LIKE '%{$search}%' OR con.content LIKE '%{$search}%' OR sm.name LIKE '%{$search}%') ";
		$this->View->assign('search',$search);
		$this->View->assign('article_type',$article_type);
		$this->View->assign('contentid',$contentid);
		$this->View->assign('startdate',$startdate);
		$this->View->assign('enddate',$enddate);
		
		$artType = explode(',',$this->type);
		if ($article_type!='') {
			if(in_array($article_type,$artType)){ $filter .= $article_type=='' ? "" : "AND con.articleType='{$article_type}'";}
			else $filter .= "AND con.articleType IN ({$article_type}) ";
		}
		$qGroup = "";
		if (intval($contentid)!=0) {
			$filter .= $contentid=='' ? "" : "AND con.id='{$contentid}'";
			
		}else $qGroup = " GROUP BY con.id ";
	
		
	
		$start = intval($this->_g('st'));
	
		/* list theme */
		
		$sqlTheme = "SELECT con.id,con.title
			FROM {$this->dbclass}_news_content con
			LEFT JOIN (SELECT contentid,COUNT(contentid) AS total_comment FROM {$this->dbclass}_news_content_comment GROUP BY contentid) tot ON con.id = tot.contentid
			WHERE con.n_status<>3
			AND con.articleType IN (5)
			ORDER BY con.created_date DESC";
		$result = $this->fetch($sqlTheme,1);
		// pr($result);
		
		
		/* list gallery */
	
			$sql = "
				SELECT con.*,repo.id repoid, repo.title repotitle, repo.brief repobrief , repo.content repocontent, repo.files repofile,repo.thumbnail thumb, repo.created_date repocreateddate,
				repo.otherid, repo.typealbum, repo.userid, repo.n_status, sm.name, sm.last_name, repo.n_status repostat
				FROM 
				{$this->dbclass}_news_content con
				LEFT JOIN {$this->dbclass}_news_content_repo repo ON repo.otherid = con.id
				LEFT JOIN social_member sm ON repo.userid = sm.id
				WHERE 
				repo.n_status IN (1,2,3,4) AND
				con.n_status<>3
				
				AND con.articleType IN ({$this->type})
				{$filter}
				{$qGroup}
				ORDER BY repo.created_date DESC 
				LIMIT {$start},{$this->total_per_page}
			";
		
		// pr($sql);
		$list = $this->fetch($sql,1);
		if($list){
				
			$n=$start+1;
			foreach($list as $key => $val){
					$list[$key]['no'] = $n++;
					$arrContentId[] = $val['id'];
			}
			
		}
		
		/* Hitung banyak record data 
		$sql = "
				SELECT count(*) total
				FROM 
				{$this->dbclass}_news_content con
				LEFT JOIN {$this->dbclass}_news_content_repo repo ON repo.otherid = con.id
				WHERE 
				repo.n_status IN (1,2) AND repo.gallerytype = 0 AND
				con.n_status<>3
				AND con.articleType IN ({$this->type})
				{$filter}
				{$qGroup}
				
			";
		*/
		
		$sql = "SELECT count(*) total FROM 
				{$this->dbclass}_news_content con
				LEFT JOIN {$this->dbclass}_news_content_repo repo ON repo.otherid = con.id
				LEFT JOIN social_member sm ON repo.userid = sm.id
				WHERE 
				repo.n_status IN (1,2,3,4) AND
				con.n_status<>3
				AND con.articleType IN ({$this->type})
				{$filter}
				{$qGroup}
				";
		// pr($sql);		
		$totalList = $this->fetch($sql);	
		
		if($totalList){
		$total = intval($totalList['total']);
		}else $total = 0;
		
		// pr($total);
		$this->View->assign('list',$list);
		$this->View->assign('tema',$result);

		$this->Paging = new Paginate();
	
		$this->View->assign("paging",$this->Paging->getAdminPaging($start, $this->total_per_page, $total, "?s={$this->folder}&article_type={$article_type}&startdate={$startdate}&enddate={$enddate}&contentid={$contentid}"));	
	
		return $this->View->toString("application/admin/{$this->folder}/{$this->folder}_list.html");
	}
		
	function add(){	
		global $CONFIG;

		$data['title'] 			= $this->_p('title');
		$data['brief'] 			= $this->_p('brief');
		$content				= $this->_p('content');
		$data['content'] 	  	= $this->fixTinyEditor( $content );
		if($this->roler['approver']) $data['n_status'] = $this->_p('n_status');
		else $data['n_status'] 	 = 0;
		$data['gallerytype']	= 1;
		$data['typealbum']	= $this->_p('typealbum');
		$data['files']	= $this->_p('files');
		
		$contentid	= intval($this->_g('contentid'));
		$articleContent = false;
		if($contentid!=0) {
			$data['otherid']	= $contentid;
			$articleContent = $this->getArticleContent($contentid);
			
		}else $data['otherid']	= $this->_p('otherid');
		$this->View->assign('articleContent',$articleContent);
		
		foreach($data as $key => $val){
			$this->View->assign($key,$val);
		}
		
		if($this->_p('simpan')){
			
			// pr($data);
			foreach($data as $key => $val){
				$$key= $val;
			}
			if( $title=='' ){
				$this->View->assign('msg',"Please complete the form!");
				return $this->View->toString("application/admin/{$this->folder}/{$this->folder}_new.html");
			}
		
			$sql = "INSERT INTO {$this->dbclass}_news_content_repo (title,brief,content,gallerytype,n_status,created_date,fromwho,typealbum,files,otherid) 
			VALUES ('{$title}','{$brief}',\"{$content}\",'{$gallerytype}','{$n_status}',NOW(),'{$this->fromwho}','{$typealbum}','{$files}','{$otherid}')";
			$this->query($sql);
			
			$last_id = $this->getLastInsertId();
			if(!$last_id){
				$this->View->assign("msg","Add process failure");
				return $this->View->toString("application/admin/{$this->folder}/{$this->folder}_new.html");
			}else{
				//create Image function
				
				// pr($_FILES);
				// pr($typealbum);
				if ($typealbum == 3){
					
					$filesPhoto=$_FILES['video'];
					$path=$CONFIG['LOCAL_PUBLIC_ASSET'].$this->folder."/";
					$upload = $this->uploadThisVideo($filesPhoto, $path);
					// pr($upload);
					if ($upload){
						$this->inputVideo($last_id, $upload['arrVideo']['filename']);
						$this->uploadImagethumb($last_id);
					}
				}else{
					
					$this->createImage($last_id);
					$this->eventImageUpload($contentid);

					// $this->createImage($last_id);
				}
					
				
				
				$this->log->sendActivity("add {$this->folder}",$last_id);
				return $this->View->showMessage("Success Create {$this->folder} ", "index.php?s={$this->folder}&contentid={$otherid}");
			}
		}
		return $this->View->toString("application/admin/{$this->folder}/{$this->folder}_new.html");
	}
	
	function edit(){
		global $CONFIG;
		$id 		= $this->_g('id');
		$authorid = $this->Session->getVariable("uid");
		$contentid	= intval($this->_g('contentid'));
		$articleContent = false;
		if($contentid!=0) {
			$data['otherid']	= $contentid;
			$articleContent = $this->getArticleContent($contentid);
			
		}else $data['otherid']	= $this->_p('otherid');
		$this->View->assign('articleContent',$articleContent);
		
		if(! $this->_p('simpan')){
			$sql = "
			SELECT content.articleType, repo.* FROM {$this->dbclass}_news_content_repo repo
			LEFT JOIN {$this->dbclass}_news_content content ON repo.otherid=content.id
			WHERE repo.id={$id} LIMIT 1";
			$qData = $this->fetch($sql);
			
			if($qData){			
				foreach($qData as $key => $val){					
					$this->View->assign($key,$val);
				}
			}
		
		}else{
			$id 					= $this->_p('id');
			$data['title'] 			= $this->_p('title');
			$data['brief'] 			= $this->_p('brief');
			$content				= $this->_p('content');
			$data['content'] 	  	= $this->fixTinyEditor( $content );
			if($this->roler['approver']) $data['n_status'] = $this->_p('n_status');
			else $data['n_status'] 	 = 0;
			$data['gallerytype']	= 1;
			$data['typealbum']	= $this->_p('typealbum');
			$data['files']	= $this->_p('files');
			
			
			foreach($data as $key => $val){
				 if($val) $qUpdate[] = "{$key}='{$val}'";
			}
			if($qUpdate) $strQUpdate = implode(',',$qUpdate);
		
			$sql = "UPDATE {$this->dbclass}_news_content_repo SET 	{$strQUpdate}
														WHERE id={$id} LIMIT 1";
			
			
			$last_id = $id;
		
			// pr($sql);exit;
			if(!$this->query($sql)){
				$this->View->assign("msg","edit process failure");
				return $this->View->toString("application/admin/{$this->folder}/{$this->folder}_edit.html");
			}else{
				
				if ($typealbum == 3){
					$filesPhoto=$_FILES['video'];
					$path=$CONFIG['LOCAL_PUBLIC_ASSET'].$this->folder."/";
					$upload = $this->uploadThisVideo($filesPhoto, $path);
					
					if ($upload){
						$this->inputVideo($last_id, $upload['arrVideo']['filename']);
					}					
				}else{
					//create Image function
					$this->createImage($last_id);
					$this->eventImageUpload($contentid);
					// $this->createImage($last_id);
				}	
				
				$this->uploadImagethumb($last_id);
				return $this->View->showMessage('Berhasil', "index.php?s={$this->folder}&contentid={$contentid}");
			}
		}
		
		return $this->View->toString("application/admin/{$this->folder}/{$this->folder}_edit.html");
	}
	
	function hapus(){
	
		global $LOCALE;
		
		$userid = intval($this->_g('userid'));
		
		$id = intval($this->_g('id'));
		$otherid = $this->_g('otherid');
		
		$page = strip_tags($this->_g('page'));
		
		$n_status = 3;
		if ($page == 'photo') $n_status = 4; // status 4 = rejected
		
		$sql = "UPDATE {$this->dbclass}_news_content_repo SET n_status={$n_status} WHERE id={$id}";
		$res = $this->query($sql);
		if( !$res){
			return $this->View->showMessage('Gagal',"index.php?s={$this->folder}");
		}else{
			$this->log->sendUserActivity($userid, 'account', $LOCALE[1]['photorejected']);
			sleep(1);
			$this->eventImageUpload($otherid);
			return $this->View->showMessage('Berhasil',"index.php?s={$this->folder}");
		}
	}
	
	function ajax()
	{
		$idrepo = $this->_p('idrepo');
		$typealbum = $this->_p('typealbum');
		$userid = $this->_p('userid');
		$otherid = $this->_p('otherid');
		$repostat = $this->_p('repostat');
		if($repostat==3) $repostat = 1;
		else $repostat = 3;
		$sql = "UPDATE {$this->dbclass}_news_content_repo SET n_status = {$repostat} 
				WHERE id={$idrepo} AND typealbum = {$typealbum} AND gallerytype = 1 AND userid = {$userid}
				AND otherid = {$otherid} LIMIT 1";
		// pr($sql);
		$res = $this->query($sql);
		if ($res){
			if ($repostat==3){
				$this->botManager->setPointByUser('EVENTSUBMISSION', 'eventsubmissionpoint',2,'second', $userid);
				sleep(1);
				$this->eventImageUpload($otherid);
			}
			
			print json_encode(array('status'=>true));
		}else{
			print json_encode(array('status'=>false));
		}
		
		
		exit;
	}
	
	function ajaxTheme()
	{
		if ($this->_p('updateTheme')){
			
			$cid = $this->_p('cid');
			$repoid = $this->_p('rid');
			$userid = $this->_p('userid');
			if ($cid){
				$sql = "UPDATE {$this->dbclass}_news_content_repo SET otherid = {$cid} 
				WHERE id={$repoid} AND userid = {$userid} LIMIT 1";
				// pr($sql);
				$res = $this->query($sql);
				if ($res){
					print json_encode(array('status'=>true));
				}				
			}
			
		}
		
		exit;
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
	
	function getArticleList(){
		$articleType = intval($this->_p('articleType'));
		if($articleType!=0){
		
			$sql = "SELECT id, title FROM {$this->dbclass}_news_content WHERE articleType={$articleType} AND n_status <> 3 ORDER BY title ";
			
			$data = $this->fetch($sql,1);

		}else $data = false;
		
		
		print json_encode($data);exit;
	}
	function getArticleContent($contentid=0){
	
		if($contentid!=0){
		
			$sql = "SELECT id, title,brief,articleType FROM {$this->dbclass}_news_content WHERE id={$contentid} AND n_status <> 3 ORDER BY title LIMIT 1";
			
			$data = $this->fetch($sql);
			return $data;
		}
		return false;
		
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
					}
					
					$this->inputImage($last_id,$img);
					
					
				}
				
				/*
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
					$this->inputImageThumb($last_id,$img);
				}*/
	}
	
	function uploadImagethumb($last_id=null)
	{
		global $CONFIG;
				if($last_id==null) return false;
				
				if ($_FILES['image_thumb']['name']!=NULL) {
					include_once '../../engines/Utility/phpthumb/ThumbLib.inc.php';
					list($file_name,$ext) = explode('.',$_FILES['image_thumb']['name']);
					$img = md5($_FILES['image_thumb']['name'].rand(1000,9999)).".".$ext;
					try{
						$thumb = PhpThumbFactory::create( $_FILES['image_thumb']['tmp_name']);
					}catch (Exception $e){
						return false;
					}
			
					if(move_uploaded_file($_FILES['image_thumb']['tmp_name'],"{$CONFIG['LOCAL_PUBLIC_ASSET']}{$this->folder}/{$img}")){
					
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
					}
					
					$this->inputImageThumb($last_id,$img);
					
					
				}
	}
	
	function uploadThisVideo($files=NULL,$path=NULL){
		$arrVideoData['filename'] ="";
		
		if($files==NULL || $path==NULL) return false;
		list($name,$type) = explode('.',$files['name']);
		$filename = md5($files['name'].rand(1000,9999)).".".$type;
		
		if(move_uploaded_file($files['tmp_name'],$path.$filename)){
			$arrVideoData['filename'] =$filename;
			return array('result'=>true,'arrVideo'=> $arrVideoData);
		}else{
			return array('result'=>false,'arrVideo'=> false);
		}
	}
	
	function inputImage($id,$img){
		$this->query("UPDATE {$this->dbclass}_news_content_repo SET files='{$img}' WHERE id={$id}");
	}
	
	function inputVideo($id,$img){
		$this->query("UPDATE {$this->dbclass}_news_content_repo SET files='{$img}' WHERE id={$id}");
	}
	
	function inputImageThumb($id,$img){
		$sql = "UPDATE {$this->dbclass}_news_content_repo SET thumbnail='{$img}' WHERE id={$id} ";
		// pr($sql);exit;
		$this->query($sql);
	}
	function getTypeList(){
		$sql = "SELECT * FROM {$this->dbclass}_news_content_type WHERE id IN ({$this->type}) AND  content IN ({$this->contentType}) ";
		$type = $this->fetch($sql,1);
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

	function eventImageUpload($eventID){
		//photo_frame.png 445x114
		global $CONFIG;

		$sql="SELECT * FROM marlborohunt_news_content_repo 
				WHERE otherid={$eventID} AND n_status IN (1,3)
				ORDER BY created_date DESC LIMIT 3";
		$rs = $this->fetch($sql,1);
	
		$totalImg = sizeof($rs);
		if($rs){

			$dest = imagecreatefrompng($CONFIG['UPLOAD_ASSET'].'photo_frame.png');
			$src = imagecreatefromjpeg($CONFIG['UPLOAD_ASSET'].'gallery/'.$rs[0]['files']);
			$src_link = $CONFIG['UPLOAD_ASSET'].'gallery/'.$rs[0]['files'];
			
			$image_info = getimagesize($src_link);

			list($width,$height)=getimagesize($src_link);

			imagealphablending($dest, false);
			imagesavealpha($dest, true);


			
	        if ($height > $width) {   // If Height Is Greater Than Width 
	           $zoom = 100 / $height;   // Length Ratio For Width 
	           $newheight = 100;   // Height Is Equal To Max Height 
	           $newwidth = $width * $zoom;   // Creates The New Width 
	           $cheight = ($newheight/2);
	           $cwidth = 0;
	        } else {    // Otherwise, Assume Width Is Greater Than Height (Will Produce Same Result If Width Is Equal To Height) 
		          $zoom = 200 / $width;   // Length Ratio For Height 
		          $newwidth = 200;   // Width Is Equal To Max Width 
		          $newheight = $height * $zoom;   // Creates The New Height 
		          $cheight = 0;
	           $cwidth = ($newwidth/2);
	        }         

	       
			if($totalImg>1){
				foreach ($rs as $key => $value) {
					$new_image = imagecreatetruecolor(140,95);
					$savePath = $CONFIG['UPLOAD_ASSET'].'customCrop/event_id'.$eventID.'.png';
					if($key==0){
						
						
						imagecopyresampled($new_image, $src, 0, 0, $cwidth, $cheight, $newwidth, $newheight, $width, $height);
						imagecopymerge($dest, $new_image, 5, 23, 0, 0, 140, 95, 100); //have to play with these numbers for it to work for you, etc.
						imagepng($dest,$savePath);
					}
					
					if($key!=0){
						$src = imagecreatefromjpeg($CONFIG['UPLOAD_ASSET'].'gallery/'.$rs[$key]['files']);
						$src_link = $CONFIG['UPLOAD_ASSET'].'gallery/'.$rs[$key]['files'];

						$image_info = getimagesize($src_link);
						
						list($width,$height)=getimagesize($src_link);
				
				        if ($height > $width) {   // If Height Is Greater Than Width 
				           $zoom = 100 / $height;   // Length Ratio For Width 
				           $newheight = 100;   // Height Is Equal To Max Height 
				           $newwidth = $width * $zoom;   // Creates The New Width 
				           $cheight = ($newheight/2);
				           $cwidth = 0;
				        } else {    // Otherwise, Assume Width Is Greater Than Height (Will Produce Same Result If Width Is Equal To Height) 
					          $zoom = 200 / $width;   // Length Ratio For Height 
					          $newwidth = 200;   // Width Is Equal To Max Width 
					          $newheight = $height * $zoom;   // Creates The New Height 
					          $cheight = 0;
				           $cwidth = ($newwidth/2);
				        }
				        imagecopyresampled($new_image, $src, 0, 0, $cwidth, $cheight, $newwidth, $newheight, $width, $height);
        
					}

					if($key==1){
						imagecopymerge($dest, $new_image, 150, 23, 0, 0, 140, 95, 100);
						imagepng($dest,$savePath);
					}
					
					if($key==2){
						imagecopymerge($dest, $new_image, 295, 23, 0, 0, 140, 95, 100);
						imagepng($dest,$savePath);
					}
				}
			}
			//imagejpeg($dest,$savePath,100);
			imagedestroy($dest);
			imagedestroy($src);
			imagedestroy($new_image);
		}
	}
	
	
	
}