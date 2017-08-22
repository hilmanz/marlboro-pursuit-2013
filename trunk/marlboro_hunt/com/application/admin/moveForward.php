<?php

global $ENGINE_PATH;
include_once $ENGINE_PATH."Utility/Paginate.php";
		
class moveForward extends Admin{
	function __construct(){	
		parent::__construct();	
		
		$this->type = "30";
		$this->contentType = "5";
		$this->folder =  'moveForward';
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
		$this->View->assign('typelist',$this->typelist);		
		$this->catList = $this->getCategoryList();
		$this->View->assign('catList',$this->catList);
		
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
	

			
			
			/* list article */
			$sql = "
				SELECT con.*
				FROM {$this->dbclass}_news_content con
				WHERE con.n_status<>3
				AND con.articleType IN ({$this->type})
				{$filter}
				ORDER BY con.created_date DESC
				LIMIT {$start},{$this->total_per_page}
			";
			
			$list = $this->fetch($sql,1);
			// pr($sql);
			$parentidinbanner = false;
			if($list){
					
				$n=$start+1;
				foreach($list as $key => $val){
						$list[$key]['no'] = $n++;
						$arrContentId[] = $val['id'];
				}
				
			
				if($arrContentId){
					$strContentId =implode(',',$arrContentId);
					$sql =" SELECT * FROM {$this->dbclass}_news_content_banner WHERE parentid IN ({$strContentId}) ";
					// pr($sql);
					$bannerData = $this->fetch($sql,1);
					if($bannerData){
						foreach($bannerData as $val){
							$parentidinbanner[$val['parentid']] = true;				
						}
					}
				}
				
				//add misc join like comment and other field in here
				foreach($list as $key => $val){
					
					//status banner has been add or not
					if($parentidinbanner){
							if(array_key_exists($val['id'],$parentidinbanner)) $list[$key]['is_banner'] = true;
							else  $list[$key]['is_banner'] = false;
					}
					
					//other status in here
				}
			}
			// pr($list);
			/* Hitung banyak record data */
			$sql ="
				SELECT count(*) total
				FROM {$this->dbclass}_news_content con
				WHERE con.n_status<>3
				AND con.articleType IN ({$this->type})
				{$filter}
				ORDER BY con.created_date DESC ";
			$totalList = $this->fetch($sql);	
				// pr($totalList);
			if($totalList){
			$total = intval($totalList['total']);
			}else $total = 0;
			
			$this->View->assign('list',$list);

			$this->Paging = new Paginate();
		
			$this->View->assign("paging",$this->Paging->getAdminPaging($start, $this->total_per_page, $total, "?s={$this->folder}&article_type={$article_type}&startdate={$startdate}&enddate={$enddate}"));	
		
		return $this->View->toString("application/admin/{$this->folder}/{$this->folder}_list.html");
	}
	
	
	function add(){	
		global $CONFIG;
		
		// pr($_POST); 
		$data['authorid'] 		= $this->Session->getVariable("uid");
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
		$data['categoryid']	 = $this->_p('categoryid');
		$data['point']	= $this->_p('point');
		
		$data['question_type']	= $this->_p('question_type');
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
			$sql = "INSERT INTO {$this->dbclass}_news_content (title,brief,content,articleType,url,sourceurl,n_status,created_date,posted_date,expired_date,tags,topcontent,authorid,fromwho,point,categoryid) 
			VALUES ('{$title}','{$brief}',\"{$content}\",'{$articleType}','{$url}','{$sourceurl}','{$n_status}',NOW(),'{$posted_date}','{$expired_date}','{$tags}','{$topcontent}','{$authorid}','{$this->fromwho}','{$point}','{$categoryid}')";
			// pr($sql);exit;
			$this->query($sql);
			$task	= $_POST['task'];
			$embedLink	= @$_POST['embed'];
			$otherid = $this->getLastInsertId();
			$last_id = $this->getLastInsertId();
			
			$i = 0;
			foreach($task as $key => $val){
				
				$otherType = 0;
				if ($embedLink){
					if (in_array($i, $embedLink)){
						$otherType = 1;
					}
				}
				
				$sqlType = "INSERT INTO question(question, question_type, otherid, other_type, n_status) VALUES ('{$val}', '{$question_type}', '{$otherid}', {$otherType},'1')";
				$this->query($sqlType);
				// pr($sqlType);
				$i++;
			}
		
			
			if(!$last_id){
				$this->View->assign("msg","Add process failure");
				return $this->View->toString("application/admin/{$this->folder}/{$this->folder}_new.html");
			}else{
				//create Image function
				$this->createImage($last_id);
				$eventData['created_date'] =  $data['posted_date'];
				$eventData['closed_date'] =  $data['expired_date'];
				$eventData['n_status'] = $data['n_status'];
				$this->createeventcontest($last_id,$eventData);
				$this->log->sendActivity("add {$this->folder}",$last_id);
				return $this->View->showMessage("Success Create {$this->folder} ", "index.php?s={$this->folder}");
			}
		}
		
		$this->View->assign('looptask',$CONFIG['BUCKETLISTTASK']);
		
		return $this->View->toString("application/admin/{$this->folder}/{$this->folder}_new.html");
	}
	
	function edit(){
		global $CONFIG;
		
		// pr($_POST);
		$id 		= $this->_g('id');
		$authorid = $this->Session->getVariable("uid");
		if(! $this->_p('simpan')){
			$sql = "SELECT * FROM {$this->dbclass}_news_content WHERE id={$id} LIMIT 1";
		
			$sql2 = "SELECT * FROM question WHERE otherid={$id} ";
			 
			$qData = $this->fetch($sql);
			$qData2 = $this->fetch($sql2,1);
		 
			
			// if (!$qData2 and count($qData2<$CONFIG['BUCKETLISTTASK'])){
				// $index = 0;
				// for ($i = 1; $i<=$CONFIG['BUCKETLISTTASK']; $i++){
					// $qData2[$index]['question'] = "";
					// $index++;
				// }
				
			// }
			// pr($qData2);
			if($qData){
				if($qData['tags']!='')	$qData['tags'] = implode(',',unserialize($qData['tags']));
			
				foreach($qData as $key => $val){					
					$this->View->assign($key,$val);
				}
			}
			
			 
			
			if($qData2){
			 
				foreach($qData2 as $key => $val){					
					$this->View->assign($key,$val);
				}
			}
		// pr($qData2);
		
		$this->View->assign('taskloop',$CONFIG['BUCKETLISTTASK']);
		$this->View->assign('qData2',$qData2);
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
			$question_type	= $this->_p('question_type');
			$categoryid	= $this->_p('categoryid');
			$point	= $this->_p('point');
		
			if($this->type) {
				$arrType 	= explode(',',$this->type);				
				if(!in_array($articleType,$arrType)) {
					return $this->View->showMessage("you are not authorize for this type id", "index.php?s={$this->folder}");
				}
			}
			if(@$this->category) {
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
			$sql = "UPDATE {$this->dbclass}_news_content SET title='{$title}',
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
														categoryid='{$categoryid}',
														point='{$point}',
														topcontent='{$topcontent}'
														WHERE id={$id} LIMIT 1";
			
			
			$last_id = $id;
			
			$task	= $_POST['task'];
			$embedLink	= @$_POST['embed'];
			// pr($task);
			// pr($embedLink);
			
			foreach($task as $key => $val){
				if ($key !=0){
					
					foreach($val as $keys => $value){
						$otherType = 0;
						if ($embedLink){
							if (array_key_exists($key, $embedLink)){
								$otherType = 1;
							}
						}
						$sqlType = "UPDATE question SET question='{$value}', other_type = {$otherType} WHERE id={$key}";
						// pr($sqlType);
						$this->query($sqlType);
					}
					
				}else{
					
					
					$i = 0;
					foreach($val as $keys => $value){
						$otherType = 0;
						if ($embedLink){
							if (in_array($i, $embedLink)){
								$otherType = 1;
							}
						}
					
						$sqlType = "INSERT INTO question(question, question_type, otherid, other_type, n_status) 
									VALUES ('{$value}', '{$question_type}', '{$id}', {$otherType}, '1')";
						// pr($sqlType);
						$this->query($sqlType);
						$i++;
					}
					
				}
				
				// pr($sqlType);
				
			}
			// exit;
			if(!$this->query($sql)){
				$this->View->assign("msg","edit process failure");
				return $this->View->toString("application/admin/{$this->folder}/{$this->folder}_edit.html");
			}else{
				//create Image function
				$this->createImage($last_id);				
				$eventData['created_date'] = $posted_date;
				$eventData['closed_date'] = $expired_date;
				$eventData['n_status'] = $status;
				$this->createeventcontest($last_id,$eventData);
				return $this->View->showMessage('Berhasil', "index.php?s={$this->folder}");
			}
		}
		
		return $this->View->toString("application/admin/{$this->folder}/{$this->folder}_edit.html");
	}
	
	function hapus(){
		$id = $this->_g('id');
		if( !$this->query("UPDATE {$this->dbclass}_news_content SET n_status=3 WHERE id={$id}")){
			return $this->View->showMessage('Gagal',"index.php?s={$this->folder}");
		}else{
			return $this->View->showMessage('Berhasil',"index.php?s={$this->folder}");
		}
	}
	
	function createeventcontest($last_id=null,$eventData=null){
		$sql = "SELECT count(*) total FROM {$this->dbclass}_event_contest WHERE contentid={$last_id} LIMIT 1 ";
		
		$qData = $this->fetch($sql);
		if($qData['total']>0){
				
					$sql = "UPDATE {$this->dbclass}_event_contest SET 
					created_date='{$eventData['created_date']}' , 
					closed_date='{$eventData['closed_date']}',
					n_status={$eventData['n_status']}					
					WHERE contentid={$last_id} LIMIT 1";
					// pr($sql);exit;
					$this->query($sql);
					
				}else{
				
					if($last_id){
						$sql = "
						INSERT INTO {$this->dbclass}_event_contest (contentid,created_date,closed_date,n_status) 
						VALUES ({$last_id},'{$eventData['created_date']}','{$eventData['closed_date']}',{$eventData['n_status']})
						";
						// pr($sql);exit;
						$this->query($sql);
						if(!$this->getLastInsertId()){
							return $this->View->showMessage(" {$this->folder}  gagal di upload", "index.php?s={$this->folder}");
						}
					}
				}
			return true;
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
					}
					$this->fwdImageUpload($img);
					
					$this->newsImageUpload($img);
					
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
		// pr($sql);
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
	
	function getCategoryList(){
		$sql = "SELECT * FROM {$this->dbclass}_news_content_category  ";
		$cat = $this->fetch($sql,1);
		// pr($sql);
		return $cat;
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
	
	
	function fwdImageUpload($image){
		//gallerybox.png
		global $CONFIG;


		$dest = imagecreatefrompng($CONFIG['UPLOAD_ASSET'].'gallerybox.png');
		$src = imagecreatefromjpeg($CONFIG['UPLOAD_ASSET'].'moveForward/'.$image);
		$src_link = $CONFIG['UPLOAD_ASSET'].'moveForward/'.$image;

		$image_info = getimagesize($src_link);

		list($width,$height)=getimagesize($src_link);

		imagealphablending($dest, false);
		imagesavealpha($dest, true);


		
        if ($height > $width) {   // If Height Is Greater Than Width 
           $zoom = 300 / $height;   // Length Ratio For Width 
           $newheight = 300;   // Height Is Equal To Max Height 
           $newwidth = $width * $zoom;  // Creates The New Width 
           $cheight = ($newheight/2);
           $cwidth = 0;
        } else {    // Otherwise, Assume Width Is Greater Than Height (Will Produce Same Result If Width Is Equal To Height) 
	          $zoom = 300 / $width;   // Length Ratio For Height 
	          $newwidth = 300;   // Width Is Equal To Max Width 
	          $newheight = $height * $zoom ;   // Creates The New Height 
	          $cheight = 0;
           $cwidth = 50;
        }         

       
		

		$new_image = imagecreatetruecolor(183, 193);
		//imagecopy($new_image, $src, 0, 0, $newwidth, $newheight, $width, $height);
		imagecopyresampled($new_image, $src, 0, 0, $cwidth, $cheight, $newwidth, $newheight, $width, $height);
		imagecopymerge($dest, $new_image, 42, 20, 0, 0, 183, 193, 100); //have to play with these numbers for it to work for you, etc.
		
		//$name = explode('.',$image);
		$savePath = $CONFIG['UPLOAD_ASSET'].'moveForward/frame_'.$image.'.png';

		imagepng($dest,$savePath);
		//imagejpeg($dest,$savePath,100);
		imagedestroy($dest);
		imagedestroy($src);
		imagedestroy($new_image);
		
	}
	
	function newsImageUpload($image){
		//frame-news-photo.png
		global $CONFIG;
 
	
		$dest = imagecreatefrompng($CONFIG['UPLOAD_ASSET'].'frame-news-photo.png');
		$src = imagecreatefromjpeg($CONFIG['UPLOAD_ASSET'].'moveForward/'.$image);
		$src_link = $CONFIG['UPLOAD_ASSET'].'moveForward/'.$image;

		$image_info = getimagesize($src_link);

		list($width,$height)=getimagesize($src_link);

		imagealphablending($dest, false);
		imagesavealpha($dest, true);


		
        if ($height > $width) {   // If Height Is Greater Than Width 
           $zoom = 300 / $height;   // Length Ratio For Width 
           $newheight = 300;   // Height Is Equal To Max Height 
           $newwidth = $width * $zoom; // Creates The New Width 
           $cheight = ($newheight/2);
           $cwidth = 0;
        } else {    // Otherwise, Assume Width Is Greater Than Height (Will Produce Same Result If Width Is Equal To Height) 
	          $zoom = 300 / $width;   // Length Ratio For Height 
	          $newwidth = 300;   // Width Is Equal To Max Width 
	          $newheight = $height *  $zoom; // Creates The New Height 
	          $cheight = 0;
           $cwidth = ($newwidth/2);
        }         

       
		

		$new_image = imagecreatetruecolor(178, 178);
		//imagecopy($new_image, $src, 0, 0, $newwidth, $newheight, $width, $height);
		imagecopyresampled($new_image, $src, 0, 0, $cwidth, $cheight, $newwidth, $newheight, $width, $height);
		imagecopymerge($dest, $new_image, 15, 20, 0, 0, 178, 178, 100); //have to play with these numbers for it to work for you, etc.
		
		//$name = explode('.',$image);
		$savePath = $CONFIG['UPLOAD_ASSET'].'moveForward/framethumb_'.$image.'.png';

		imagepng($dest,$savePath);
		//imagejpeg($dest,$savePath,100);
		imagedestroy($dest);
		imagedestroy($src);
		imagedestroy($new_image);
		
	}
	
}