<?php

global $ENGINE_PATH;
include_once $ENGINE_PATH."Utility/Paginate.php";
		
class arcLightOutbound extends Admin{
	var $category;
	var $type;
	function __construct(){	
		parent::__construct();	
		
		$this->type = "1,3,4,5,6";
		$this->contentType = "0";
		$this->folder =  'arcLightOutbound';
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
		
	
		$start = intval($this->_g('st'));
		
		/* Hitung banyak record data */
		$sql ="
			SELECT count(*) total
			FROM social_member
			WHERE n_status<>3
			{$filter}";
		$totalList = $this->fetch($sql);	
		// pr($totalList);
		if($totalList){
		$total = intval($totalList['total']);
		}else $total = 0;
		
		/* list article */
		$sql = "
			SELECT *
			FROM social_member
			WHERE n_status<>3
			{$filter}
			ORDER BY register_date
			LIMIT {$start},{$this->total_per_page}
		";
	
		$list = $this->fetch($sql,1);
		
		if($list){				
			$n=$start+1;
			foreach($list as $key => $val){
					$list[$key]['no'] = $n++;
				
			}			
		}
		
			
		
		$this->View->assign('list',$list);

		$this->Paging = new Paginate();
	
		$this->View->assign("paging",$this->Paging->getAdminPaging($start, $this->total_per_page, $total, "?s={$this->folder}&article_type={$article_type}&startdate={$startdate}&enddate={$enddate}"));	
	// pr("application/admin/{$this->folder}/{$this->folder}_list.html");
		return $this->View->toString("application/admin/{$this->folder}/{$this->folder}_list.html");
	}
	
	
	
	function generate(){	
		/* Hitung banyak record data */
		$sql ="
			SELECT count(*) total
			FROM {$this->dbclass}_news_content con
			WHERE n_status<>3
			AND con.articleType IN ({$this->type})		
			";
		$totalList = $this->fetch($sql);	
		// pr($totalList);
		if($totalList){
		$total = intval($totalList['total']);
		}else $total = 0;
		
		/* list article */
		 if( !$xml = simplexml_load_file('http://preview.kanadigital.com/repository_pursuit/Distribution/Hippodrome/ArcLight%20Outbound/KANA/pick_up/PMA_PH_A12_PmiPhFileDm_PH09000424M17_2009072720130727_064540.xml') ) //using simplexml_load_file function to load xml file
		{
			echo 'load XML failed !';
		}
		else
		// {
		//pr($xml);exit;
		//parse the xml file into object
		// $dataxml = simplexml_load_file('http://preview.kanadigital.com/repository_pursuit/Distribution/Hippodrome/ArcLight%20Outbound/KANA/pick_up/PMA_PH_A12_PmiPhFileDm_PH09000424M17_2009072720130727_064540.xml');
		// foreach($dataxml->record as $value)
		// {
		   // $registerid 			 = $value->ConsumerId;
		   // $name				 = $value->FirstName; 
		   // $nickname			 = $value->ThirdName;			
		   // $register_date		 = $value->AudienceDropDate;   
		   // $phone_number 		 = $value->MobilePhoneNumber;
		   // $sex			 		 = $value->Gender; 
		   // $birthday 			 = $value->DateOfBirth;
		   // $sql = "INSERT INTO social_member (registerid,name,nickname,register_date,phone_number,sex,birthday) VALUES ('{$registerid}','{$name}','{$nickname}','{$register_date}','{$phone_number}','{$sex}','{$birthday}')";
		   // $type = $this->query($sql);
				foreach( $xml as $record ) 
				{
					foreach( $record as $value ) 
					
					{
					//backup full record ada di full.php
					$registerid 		 = $value->ConsumerId;
					$name				 = $value->FirstName; 
					$nickname			 = $value->ThirdName;			
					$register_date		 = $value->AudienceDropDate;  
					$city			 	 = $value->HouseNumber->City; 
					$phone_number 		 = $value->MobilePhoneNumber;
					$sex			 	 = $value->Gender; 
					$birthday 			 = $value->DateOfBirth;
			
				//	pr($value);

				//	save to database
					$sql = "INSERT INTO social_member (registerid,name,nickname,register_date,phone_number,sex,birthday) VALUES ('{$registerid}','{$name}','{$nickname}','{$register_date}','{$phone_number}','{$sex}','{$birthday}')";
					$type = $this->query($sql);
							
				//	pr($type);
							
				}
				if ($type) {
					echo '<h2>Success Save to Database </h2>';
					}
					else echo '<h2>Failed Save to Databaase</h2>';
	
		return $this->View->toString("application/admin/{$this->folder}/{$this->folder}_new.html");
	    }
	  
	}
	
	function sendMail(){
		
		global $CONFIG;
		$id 		= $this->_g('id');
		$authorid				= intval($this->_p("authorid"));
	

		return $this->View->toString("application/admin/arcLightOutbound/arcLightOutbound_edit.html");
	}								  			//admin/index.php?s=arcLightOutbound&act
	
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
	
	// function savecrop(){
		// global $CONFIG;
		// $files['source_file'] = $this->_p('imageFilename');
		// $files['url'] = $CONFIG['LOCAL_PUBLIC_ASSET']."{$this->folder}/";
		// $files['real_url'] = $CONFIG['LOCAL_PUBLIC_ASSET']."{$this->folder}/";
		// $arrFilename = explode('.',$files['source_file']);
		// if($files==null) return false;
		// $targ_w = $this->_p('w');
		// $targ_h = $this->_p('h');
		// $targ_scale = floatval($this->_p('scale'));
		// $jpeg_quality = 90;
		
		// $src = 	$files['real_url'].$files['source_file'];
		//pr($src);exit;
		// $file_ext = strtolower($arrFilename[sizeof($arrFilename)-1]);
		
		// if($file_ext=='jpg' || $file_ext=='jpeg' ){
			// $img_r = imagecreatefromjpeg($src);
		// }
		// if($file_ext=='png' ) {
			// $img_r = imagecreatefrompng($src);
			// imagealphablending($img_r, true);
		// }
		// if($file_ext=='gif' ) $img_r = imagecreatefromgif($src);
		
		// $dst_r = ImageCreateTrueColor( $targ_w, $targ_h ) or die('Cannot Initialize new GD image stream');
		
		// if($file_ext=='png'){
			// imagesavealpha($dst_r, true);
			// imagealphablending($dst_r, false);
			// $transparent = imagecolorallocatealpha($dst_r, 0, 0, 0, 127);
			// imagefill($dst_r, 0, 0, $transparent);

		// }
		
		// imagecopyresampled($dst_r,$img_r,0,0,$this->_p('x'),$this->_p('y'),$targ_w,$targ_h, $this->_p('w'),$this->_p('h'));		
		
		//header('Content-type: image/jpeg');
		// if($file_ext=='jpg' || $file_ext=='jpeg' ) imagejpeg($dst_r,$files['url'].'thumb_'.$files['source_file'],$jpeg_quality);
		// if($file_ext=='png')imagepng($dst_r,$files['url'].'thumb_'.$files['source_file']);
		// if($file_ext=='gif') imagegif($dst_r,$files['url'].'thumb_'.$files['source_file']);
		
		// if($targ_scale>0){
			// $info = getimagesize($src);
			// $this->resize_image($src,$files['url'].'resized_'.$files['source_file'],$files,$file_ext,0,0,($info[0]*($targ_scale/100)),($info[1]*($targ_scale/100)),$info[0],$info[1]);
			// $src = $files['url'].'resized_'.$files['source_file'];
		// }
		
		// $this->resize_image($src,$files['url'].'thumb_'.$files['source_file'],$files,$file_ext,$this->_p('x'),$this->_p('y'),$targ_w,$targ_h,$this->_p('w'),$this->_p('h'));		
		
		// header('Cache-Control: no-cache, must-revalidate');
		// header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
		// header('Content-type: application/json');		
		// print json_encode(array('image'=>$CONFIG['BASE_DOMAIN']."public_assets/{$this->folder}/thumb_".$files['source_file']));
		// exit;
	// }
	
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
