<?php 


class uploadHelper {
	function __construct($apps){
		global $logger;
		$this->logger = $logger;
		$this->apps = $apps;
		if(is_object($this->apps->user)) $this->uid = intval($this->apps->user->id);	
		$this->fileType = array("image/jpeg","image/jpg","image/pjpeg","image/png","image/gif","application/pdf");
		$this->extfileType = array("jpeg","jpg","png","gif","pdf");
	}
	
	function newsImageUpload(){
		//frame-news-photo.png
		global $CONFIG;


		$dest = imagecreatefrompng($CONFIG['UPLOAD_ASSET'].'frame-news-photo.png');
		$src = imagecreatefromjpeg($CONFIG['UPLOAD_ASSET'].'news_sample7.jpg');
		$src_link = $CONFIG['UPLOAD_ASSET'].'news_sample7.jpg';

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
		
		$savePath = $CONFIG['UPLOAD_ASSET'].'contoh2.png';

		imagepng($dest,$savePath);
		//imagejpeg($dest,$savePath,100);
		imagedestroy($dest);
		imagedestroy($src);
		imagedestroy($new_image);
		
	}
	function fwdImageUpload($image){
		//gallerybox.png
		global $CONFIG;


		$dest = imagecreatefrompng($CONFIG['UPLOAD_ASSET'].'gallerybox.png');
		$src = imagecreatefromjpeg($CONFIG['UPLOAD_ASSET'].'post/photo/'.$image);
		$src_link = $CONFIG['UPLOAD_ASSET'].'post/photo/'.$image;

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
           $cwidth = 50;
        }         

       
		

		$new_image = imagecreatetruecolor(183, 193);
		//imagecopy($new_image, $src, 0, 0, $newwidth, $newheight, $width, $height);
		imagecopyresampled($new_image, $src, 0, 0, $cwidth, $cheight, $newwidth, $newheight, $width, $height);
		imagecopymerge($dest, $new_image, 42, 20, 0, 0, 183, 193, 100); //have to play with these numbers for it to work for you, etc.
		
		//$name = explode('.',$image);
		$savePath = $CONFIG['UPLOAD_ASSET'].'customCrop/'.$image.'.png';

		imagepng($dest,$savePath);
		//imagejpeg($dest,$savePath,100);
		imagedestroy($dest);
		imagedestroy($src);
		imagedestroy($new_image);
		
	}
	function eventImageUpload($eventID){
		//photo_frame.png 445x114
		global $CONFIG;

		$sql="SELECT * FROM marlborohunt_news_content_repo 
				WHERE otherid={$eventID} AND n_status IN (1,3)
				ORDER BY created_date DESC LIMIT 3";
		$rs = $this->apps->fetch($sql,1);
		$totalImg = sizeof($rs);
		if($rs){

			$dest = imagecreatefrompng($CONFIG['UPLOAD_ASSET'].'photo_frame.png');
			$src = imagecreatefromjpeg($CONFIG['UPLOAD_ASSET'].'gallery/'.$rs[0]['files']);
			$src_link = $CONFIG['UPLOAD_ASSET'].'gallery/'.$rs[0]['files'];
			
			// $dest = imagecreatefrompng($CONFIG['UPLOAD_ASSET'].'photo_frame.png');
			// $src = imagecreatefromjpeg($CONFIG['UPLOAD_ASSET'].'news_sample7.jpg');
			// $src_link = $CONFIG['UPLOAD_ASSET'].'news_sample7.jpg';

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

	function uploadThisImage($files=NULL,$path=NULL,$maxSize=1000,$resizeOriginal=false,$page=null){
		global $CONFIG;
		include_once '../engines/Utility/phpthumb/ThumbLib.inc.php';
		$arrImageData['filename'] ="";
		
		// pr($files);exit;
		if($files==NULL || $path==NULL) return false;
		//$filename = sha1(date('ymdhis').$files['name']);
		// $type = explode('/',$files['type']);
		$type = explode('.',$files['name']);
		$idxextentions = count($type)-1;
		$extention = $type[$idxextentions];
		$filename = md5($files['name'].rand(1000,9999)).".".$extention;
		
		// pdf handle
		if ($files['type']!="application/pdf"){
			try{
				$thumb = PhpThumbFactory::create( $files['tmp_name']);
			}catch (Exception $e){
				// handle error here however you'd like
				return false;
			}
		}
		
		
		
		$typeAccepted = $this->fileType;
		$extfileType = $this->extfileType;
		
        if(!in_array(strtolower($extention),$extfileType))  return false;
		
        if(in_array(strtolower($files['type']),$typeAccepted)) {
			$this->logger->log($path.$filename);
			if(move_uploaded_file($files['tmp_name'],$path.$filename)){
				chmod($path.$filename,0644);
				
				
				if ($files['type']=="application/pdf"){
					$arrImageData['filename'] =$filename;
					$arrImageData['filename_ori'] =$files['name'];
				}else{
				
					list($width, $height, $type, $attr) = getimagesize("{$path}{$filename}");
					$maxSize = $maxSize;
						if($resizeOriginal){
								if($width>=$maxSize){
								
									$subs = $width - $maxSize;
									$percentageSubs = $subs/$width;
											
								}else{
										$subs = $maxSize;
										$percentageSubs = $subs/$width;
								}
								
								if(isset($percentageSubs)) {
									if($width>=$maxSize){
										$width = $width - ($width * $percentageSubs);
										$height =  $height - ($height * $percentageSubs);
									}else{
										$width = $width * $percentageSubs;
										$height =  $height * $percentageSubs;
									}
								}
								
						}else{
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
						}	
							$w_small = $width - ($width * 0.5);
							$h_small = $height - ($height * 0.5);
							$w_tiny = $width - ($width * 0.7);
							$h_tiny = $height - ($height * 0.7);
						
					//resize the image
					if($resizeOriginal){		
						$thumb->setOptions(array('resizeUp'=>true));
						$thumb->adaptiveResize($width,$height);
						$ori = $thumb->save($path.$filename);
						$original = $thumb->save($path."original_".$filename);
						
					}
					$thumb->adaptiveResize($width,$height);
					$big = $thumb->save("{$path}big_".$filename);
					$thumb->adaptiveResize($w_small,$h_small);
					$small = $thumb->save("{$path}small_".$filename);
					$thumb->adaptiveResize($w_tiny,$h_tiny);
					$tiny = $thumb->save("{$path}tiny_".$filename);
					
					$arrImageData['filename'] =$filename;
					$arrImageData['filename_ori'] =$files['name'];
					$this->autoCropCenterArea($filename,$path,$width,$height);

					if($page=='fwd'){
						$this->fwdImageUpload($filename);
					}
				}
				

				return array('result'=>true,'arrImage'=> $arrImageData);
			
			}
		}
		return array('result'=>false,'arrImage'=> false);
	}
	
	function uploadThisMusic($files=NULL,$path=NULL){
		return false;
		$arrMusicData['filename'] ="";
		if($files==NULL || $path==NULL) return false;
		if ($files['error']==0) {
			// $type = explode('/',$files['type']);
			$type = explode('.',$files['name']);
			$filename = md5($files['name'].rand(1000,9999)).".".$type[1];
		} else return false;
		if(move_uploaded_file($files['tmp_name'],$path.$filename)){
			$arrMusicData['filename'] = $filename;
			return array('result'=>true,'arrMusic'=> $arrMusicData);
		}else{
			return array('result'=>false,'arrMusic'=> false);
		}
	}
	
	function uploadThisVideo($files=NULL,$path=NULL){
		$this->typeVideoAccepted = array("video/mpeg","video/mp4","video/quicktime");
		$arrVideoData['filename'] ="";
		if($files==NULL || $path==NULL) return false;
		
		$type = explode('.',$files['name']);
		$idxextentions = count($type)-1;
		$extention = $type[$idxextentions];
		$filename = md5($files['name'].rand(1000,9999)).".".$extention;		
		  
		
		$extfileVideoType = array("mpg","mpeg","mp4","avi","ogg","flv");
		if(!in_array(strtolower($extention),$extfileVideoType))  return false;
		
		if(in_array(strtolower($files['type']),$this->typeVideoAccepted)) {
	
			if(move_uploaded_file($files['tmp_name'],$path.$filename)){
				
				$arrVideoData['filename'] =$filename;
				return array('result'=>true,'arrVideo'=> $arrVideoData);
			}else{
				return array('result'=>false,'arrVideo'=> false);
			}
		}
	}
	
	function saveCropImage(){
		global $CONFIG;
		$files['source_file'] = strip_tags($this->apps->_p("imageFilename"));
		$files['url'] = strip_tags($this->apps->_p("imageUrl"));
		$files['real_url'] = $CONFIG['LOCAL_PUBLIC_ASSET'].'user/photo/';
		$arrFilename = explode('.',$files['source_file']);
		if($files==null) return false;
		$targ_w = intval($this->apps->_p('w'));
		$targ_h =intval($this->apps->_p('h'));
		$jpeg_quality = 90;
		
		if($files['source_file']=='') return false;
		
		$src = 	$files['url'].$files['source_file'];
		try{
			$img_r = false;
			if($arrFilename[1]=='jpg' || $arrFilename[1]=='jpeg' ) $img_r = imagecreatefromjpeg($src);
			if($arrFilename[1]=='png' ) $img_r = imagecreatefrompng($src);
			if($arrFilename[1]=='gif' ) $img_r = imagecreatefromgif($src);
			if(!$img_r) return false;
			$dst_r = ImageCreateTrueColor( $targ_w, $targ_h );

			imagecopyresampled($dst_r,$img_r,0,0,intval($this->apps->_p('x')),$this->apps->_p('y'),	$targ_w,$targ_h,$this->apps->_p('w'),$this->apps->_p('h'));

			// header('Content-type: image/jpeg');
			if($arrFilename[1]=='jpg' || $arrFilename[1]=='jpeg' ) imagejpeg($dst_r,$files['url'].$files['source_file'],$jpeg_quality);
			if($arrFilename[1]=='png' ) imagepng($dst_r,$files['url'].$files['source_file']);
			if($arrFilename[1]=='gif' ) imagegif($dst_r,$files['url'].$files['source_file']);
			
		}catch (Exception $e){
			return false;
		}
		include_once '../engines/Utility/phpthumb/ThumbLib.inc.php';
			
		try{
			$thumb = PhpThumbFactory::create($files['url'].$files['source_file']);
		}catch (Exception $e){
			// handle error here however you'd like
		}
		list($width, $height, $type, $attr) = getimagesize($files['url'].$files['source_file']);
		$maxSize = 400;
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
		$big = $thumb->save(  "{$files['url']}".$files['source_file']);
		$thumb->adaptiveResize($width,$height);
		$prev = $thumb->save(  "{$files['url']}prev_".$files['source_file']);
		$thumb->adaptiveResize($w_small,$h_small);
		$small = $thumb->save( "{$files['url']}small_".$files['source_file'] );
		$thumb->adaptiveResize($w_tiny,$h_tiny);
		$tiny = $thumb->save( "{$files['url']}tiny_".$files['source_file']);
		
		if(file_exists($files['url'].$files['source_file'])){
			//saveit
			$sql = "
			UPDATE social_member 
			SET  img = '{$files['source_file']}'
			WHERE id={$this->uid} LIMIT 1
			";
			$this->logger->log($sql);
			
			$qData = $this->apps->query($sql);
			if($qData){
					$sql = "
					SELECT id,name,nickname,email,register_date,sex,birthday,phone_number,fb_id,twitter_id,gplus_id,img
					FROM social_member 
					WHERE 
					n_status=1 AND id={$this->uid} LIMIT 1 ";
				$rs = $this->apps->fetch($sql);	
				if(!$rs)return false;
				$rs['img'] = $files['source_file'];
				$this->apps->session->set('user',urlencode64(json_encode($rs)));
				return "prev_".$files['source_file'];
			}else return false;			
		}else return false;
	}
	
	function autoCropCenterArea($imageFilename=null,$imageUrl=null,$width=0,$height=0){
		
		if($imageFilename==null||$imageUrl==null) return false;
		if($width==0||$height==0) return false;
		
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
		include_once '../engines/Utility/phpthumb/ThumbLib.inc.php';
			
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
		$big = $thumb->save(  "{$files['url']}"."square_big_".$files['source_file']);
		$thumb->adaptiveResize($width,$height);
		$prev = $thumb->save(  "{$files['url']}prev_".$files['source_file']);
		$thumb->adaptiveResize($w_small,$h_small);
		$small = $thumb->save( "{$files['url']}small_".$files['source_file'] );
		$thumb->adaptiveResize($w_tiny,$h_tiny);
		$tiny = $thumb->save( "{$files['url']}tiny_".$files['source_file']);
		
		return $files['source_file'];
	}
	
	
	function putcontent($path="user/gid/"){
		global $CONFIG;
		$filename = md5(date('YmdHis').rand(1000,9999)).".". 'jpg';
		
		$files['real_url'] = $CONFIG['LOCAL_PUBLIC_ASSET'].$path;
		$files['source_file'] = $filename;				
		$result = file_put_contents( $files['real_url'].$files['source_file'], file_get_contents('php://input') );
		if (!$result) {
			return array('msg' => "ERROR: Failed to write data to {$filename}, check permissions\n" ,'filename' => $files['source_file'], 'result' => false );
		}
		return array('msg' => 'success' , 'filename' => $files['source_file'], 'result' => true );
	
	}
	
	function httprawpostdata($path="user/gid/"){
		if ( isset ( $GLOBALS["HTTP_RAW_POST_DATA"] )) {
			global $CONFIG;
			$filename = md5(date('YmdHis').rand(1000,9999)).".". 'jpg';
			$files['real_url'] = $CONFIG['LOCAL_PUBLIC_ASSET'].$path;
			$files['source_file'] = $filename;				
			
			$im = strip_tags($GLOBALS["HTTP_RAW_POST_DATA"]);
			$fp = fopen($filename, 'wb');
			fwrite($fp, $im);
			fclose($fp);
			return array('msg' => 'success' , 'filename' => $files['source_file'], 'result' => true );
		}
		return array('msg' => 'success' , 'filename' => $files['source_file'], 'result' => true );
	}
}
?>