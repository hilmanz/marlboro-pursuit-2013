<?php 

class userHelper {

	function __construct($apps){
	
		global $logger;
		$this->logger = $logger;
		$this->apps = $apps;
		$this->uid = false;
		if(is_object($this->apps->user)) $this->uid = intval($this->apps->user->id);

		$this->dbshema = "marlborohunt";	
	}

	 
	function getUserProfile(){
		global $CONFIG;
		$uid = intval($this->apps->_g('uid'));
		if(!$uid) $uid = intval($this->uid);
		if($uid!=0 || $uid!=null) {
			$sql = "
			SELECT sm.*,cityref.city as cityname FROM social_member sm
			LEFT JOIN {$this->dbshema}_city_reference cityref ON sm.city = cityref.id
			WHERE sm.id = {$uid} LIMIT 1";
			//pr($sql);
			$this->logger->log($sql);
			$qData = $this->apps->fetch($sql);
			if(!$qData)return false;
			$sql ="
			SELECT ranktable.*
			FROM my_rank mrank
			LEFT JOIN {$this->dbshema}_rank_table ranktable ON ranktable.id = mrank.rank
			WHERE userid = {$uid} 
			AND n_status = 1 LIMIT 1		
			";
			
			$qRankData = $this->apps->fetch($sql);	
		
			if($qRankData){
						
						$qData['rank'] = $qRankData['rank'];
				
			}
			
			$arrphotomod = array(0,2);
			
			if(!in_array($qData['photo_moderation'],$arrphotomod)){
				if(!is_file("{$CONFIG['LOCAL_PUBLIC_ASSET']}user/photo/{$qData['image_profile']}"))  $qData['image_profile'] = 'default.jpg';
			}else  $qData['image_profile'] = 'default.jpg';
			
			if(is_file("{$CONFIG['LOCAL_PUBLIC_ASSET']}user/photo/original_{$qData['image_profile']}")) $qData['imgoriginal']= "original_{$qData['image_profile']}";
			else $qData['imgoriginal'] = false;
			if(!in_array($qData['photo_moderation'],$arrphotomod)){
				if(!is_file("{$CONFIG['LOCAL_PUBLIC_ASSET']}user/photo/{$qData['image_profile']}"))  $qData['image_full_path'] = $CONFIG['BASE_DOMAIN_PATH']."public_assets/user/photo/".$qData['image_profile'];
				else $qData['image_full_path'] = $CONFIG['BASE_DOMAIN_PATH']."public_assets/user/photo/default.jpg";
			}else $qData['image_full_path'] = $CONFIG['BASE_DOMAIN_PATH']."public_assets/user/photo/default.jpg";
			return $qData;
		}
		return false;
	}
	 
	
	function getUserAttribute(){		
		$sql = "
		SELECT sum(ancr.point) rank,categoryid ,category
		FROM axis_news_content_rank ancr
		LEFT JOIN axis_news_content_category ancc ON ancc.id= ancr.categoryid
		WHERE userid={$this->uid} 
		GROUP BY categoryid ORDER BY rank DESC LIMIT 5 ";
		$this->logger->log($sql);
		$qData = $this->apps->fetch($sql,1);
	
		if($qData){
			$mostLike = null;
			foreach($qData as $val){
				$mostLike[] = $val['category'];		
			}
			$userLikeCategory = implode(' , ',$mostLike);
		}
		$sql = "
			SELECT art.rank titleRank,art.id levelRank FROM my_rank sr
			LEFT JOIN social_media_account sma ON sma.userid=sr.userid
			LEFT JOIN axis_rank_table art ON art.id=sr.rank
			WHERE sr.userid = {$this->uid} AND sr.n_status = 1 limit 1		
		";
		$this->logger->log($sql);
		$qData = $this->apps->fetch($sql);
		if(isset($userLikeCategory)) $qData['userlike'] = $userLikeCategory;
		if($qData)	return $qData;
		else return false;
	
	}
	
	function getRankUser(){
		$sql ="
			SELECT * 
			FROM my_rank 
			WHERE userid = {$this->uid} 
			AND n_status = 1 LIMIT 1		
			";
		$this->logger->log($sql);
		$qData = $this->apps->fetch($sql);	
	
		if($qData){
			$lastPoint = $qData['point'];
			$lastDate  = $qData['date'];
	
			$qData = null;
			//cek new point // > tanggal
			$sql ="
				SELECT SUM(score) total 
				FROM tbl_exp_point 
				WHERE user_id = {$this->uid} AND date_time > '{$lastDate}'
				";
			$this->logger->log($sql);
			$qData = $this->apps->fetch($sql);	
			$point = $qData['total'];
			$qData = null;
					
			//klo ada point baru, setelah penginsert-an point sebelum nya , tambah point nya
			if($point==0)	return false;
				
			$newPoint = $lastPoint+$point;
					
			$sql = "
				SELECT id FROM {$this->dbshema}_rank_table 
				WHERE minPoint <= {$newPoint} AND maxPoint > {$newPoint} LIMIT 1";
			$this->logger->log($sql);
			$qData = $this->apps->fetch($sql);	
			$rank = $qData['id'];
			$qData = null;
			
			if($rank){
				$sql="INSERT INTO my_rank (userid,date,date_ts,rank,point,n_status) VALUES ({$this->uid},NOW(),".time().",{$rank},{$newPoint},1) ";
				$this->logger->log($sql);
				$qData = $this->apps->query($sql);
				$lastID = $this->apps->getLastInsertId();
				$qData = null;
				if($lastID!=0 || $lastID!=null){
				
					$sql="UPDATE my_rank SET n_status = 0 WHERE userid={$this->uid} AND id <> {$lastID}  ";
					$this->logger->log($sql);
					$qData = $this->apps->query($sql);
					$qData = null;
				}else {
					//cek data if n_status 1 have duplicate value
					$sql = "
						SELECT count(*) total, id FROM my_rank 
						WHERE n_status = 1 AND userid={$this->uid} ORDER BY id DESC LIMIT 2";
						$this->logger->log($sql);
					$qData = $this->apps->fetch($sql);	
					
					if($qData['total']>=2){
						$qData = null;
						$sql = "
						SELECT id FROM my_rank 
						WHERE n_status = 1 AND userid={$this->uid} ORDER BY id DESC LIMIT 1";
						$this->logger->log($sql);
						$qData = $this->apps->fetch($sql);	
						$usingIDRank = intval($qData['id']);
						$qData = null;
						if($usingIDRank!=0){
							$sql="UPDATE my_rank SET n_status = 0 WHERE id <> {$usingIDRank} AND userid={$this->uid} ";
							$this->logger->log($sql);
							$qData = $this->apps->query($sql);
							$qData = null;
						} 
					}else return true;
				
				
				}
			}
			return false;
			
		}else{
			
			//cek klo uda ada activity brarti rollback rank nya
			$sql ="
					SELECT count(*) total 
					FROM tbl_exp_point 
					WHERE user_id = {$this->uid} 
					LIMIT 1	
					";
				$this->logger->log($sql);
			$qData = $this->apps->fetch($sql);	
			
			if($qData['total']<=0){
				//klo ga ada. insert ke social rank newbie
				$sql="INSERT INTO my_rank (userid,date,date_ts,rank,point,n_status) VALUES ({$this->uid},NOW(),".time().",1,0,1) ";
				$this->logger->log($sql);
				$qData = $this->apps->query($sql);	
			}else{
				$qData = null;
				$sql ="
					SELECT SUM(score) total 
					FROM tbl_exp_point 
					WHERE user_id = {$this->uid} 
					";
					$this->logger->log($sql);
				$qData = $this->apps->fetch($sql);	
				$point = intval($qData['total']);
				$qData = null;
			
				$sql = "
					SELECT id FROM {$this->dbshema}_rank_table
					WHERE minPoint <= {$point} AND maxPoint >= {$point} LIMIT 1";
					$this->logger->log($sql);
				$qData = $this->apps->fetch($sql);	
				$rank = $qData['id'];
					
				if($rank!=0|| $rank!=null){
					$sql="INSERT INTO my_rank (userid,date,date_ts,rank,point,n_status) VALUES ({$this->uid},NOW(),".time().",{$rank},{$point},1) ";
					$this->logger->log($sql);
					$qData = $this->apps->query($sql);		
					return true;					
				}
			}
		return false;
		}
		
	
	}
	
	
	function getPreferenceThemeUser(){
		$sql =" SELECT * FROM social_preference_page WHERE userid={$this->uid} AND n_status=1 LIMIT 1";
		$this->logger->log($sql);
		$qData = $this->apps->fetch($sql);
		// print_r( unserialize($qData['apperances']));exit;
		if($qData) return unserialize($qData['apperances']);
		else return false;
	}
	
	function savePreferenceThemeUser(){
		$data = $this->getPreferenceThemeUser();
		if($this->apps->Request->getPost('bodyColor')) $data['body']['color'] = $this->apps->Request->getPost('bodyColor');
		// if($this->apps->Request->getPost('bodyImage')) $data['body']['image'] = $this->apps->Request->getPost('bodyImage');
		// $data['content']['color'] = $this->apps->Request->getPost('contentColor');
		// $data['border']['color'] = $this->apps->Request->getPost('borderColor');
		// $data['header']['font']['family'] = $this->apps->Request->getPost('headerFontFamily');
		// $data['header']['font']['size'] = $this->apps->Request->getPost('headerFontSize');
		// $data['header']['font']['color'] = $this->apps->Request->getPost('headerFontColor');
		if( $this->apps->Request->getPost('contentFontFamily')) $data['content']['font']['family'] = $this->apps->Request->getPost('contentFontFamily');
		if( $this->apps->Request->getPost('contentFontSize')) $data['content']['font']['size'] = $this->apps->Request->getPost('contentFontSize');
		if( $this->apps->Request->getPost('contentFontColor')) $data['content']['font']['color'] = $this->apps->Request->getPost('contentFontColor');
				
		$dataPreference = serialize($data);
		
		$sql="INSERT INTO 
		social_preference_page (userid,apperances,date,n_status) VALUES ({$this->uid},'{$dataPreference}',NOW(),1) 
		ON DUPLICATE KEY UPDATE
		apperances = VALUES(apperances)
		";
		$this->logger->log($sql);
		$qData = $this->apps->query($sql);	
		
		
	}
	
	
	function updateUserProfile(){
	
		$loginHelper = $this->apps->useHelper('loginHelper');
		
		$this->logger->log('can update profile');
		//cek token valid

		$tokenize = strip_tags($this->apps->_p('tokenize'));
		$accepttoken = cektokenize($tokenize,$this->uid);		
		if(!$accepttoken) return false;
		
		//get user
		$sql = "SELECT * FROM social_member WHERE n_status=1 AND id={$this->uid} LIMIT 1";
		$this->logger->log($sql);
		$rs = $this->apps->fetch($sql);
		if(!$rs)return false;
		$rs = null;
		$name = strip_tags($this->apps->_p('name'));
		$influencer = strip_tags($this->apps->_p('influencer'));
		$StreetName = strip_tags($this->apps->_p('StreetName'));
		$sex = strip_tags($this->apps->_p('sex'));
		$birthday = strip_tags($this->apps->_p('birthday'));
		$description = strip_tags($this->apps->_p('description'));
		if($name!='') $arrQuery[] = " name='{$name}' ";
		if($influencer!='') $arrQuery[] = " influencer='{$influencer}' ";
		if($StreetName!='') $arrQuery[] = " StreetName='{$StreetName}' ";
		if($sex!='') $arrQuery[] = " sex='{$sex}' ";
		if($birthday!='') $arrQuery[] = " birthday='{$birthday}' ";
		if($description!='') $arrQuery[] = " description='{$description}' ";

			$strQuery = implode(',',$arrQuery);
			if(!$strQuery) return false;
			$this->logger->log($strQuery);
			
			$sql = "
			UPDATE social_member 
			SET {$strQuery} 
			WHERE id={$this->uid} LIMIT 1
			";
			// pr($influencer);exit;
			$this->logger->log($sql);

			$qData = $this->apps->query($sql);
			if($qData) {
					$sql = "
					SELECT *
					FROM social_member 
					WHERE 
					n_status=1 AND 
					id={$this->uid}
					LIMIT 1";
				$this->logger->log($sql);
				$rs = $this->apps->fetch($sql);
				if($rs) $loginHelper->setdatasessionuser($rs); 
				else return false;
				return true;
			}else return false;
		
			
	
			
		}	
	
	function saveImage($widget){
		global $CONFIG,$LOCALE;
		$filename="";
		if($_FILES['myImage']['error']==0)	{
			if ($_FILES['myImage']['size'] <= 256000) {
				$path = $widget=='photo_profile' ? $CONFIG['LOCAL_PUBLIC_ASSET']."user/photo/" : $CONFIG['LOCAL_PUBLIC_ASSET']."user/cover/";	
				$dataImage  = $this->apps->uploadHelper->uploadThisImage(@$_FILES['myImage'],$path);
				if($dataImage['result']){
					if ($widget=='photo_profile') {
						$sql = "UPDATE social_member SET  img = '{$dataImage['arrImage']['filename']}' WHERE id={$this->uid} LIMIT 1";
						$this->logger->log($sql);
						
						$qData = $this->apps->query($sql);
						if($qData)	$filename = @$dataImage['arrImage']['filename'];
					} elseif ($widget=='photo_cover') {
						$sql_cover = "INSERT INTO my_wallpaper (myid,image,type,datetime,n_status) 
							values ('{$this->uid}','{$dataImage['arrImage']['filename']}',0,NOW(),1)
						";
						$arrData = $this->apps->query($sql_cover);
						if($arrData) $filename = @$dataImage['arrImage']['filename'];
					}
				}
			} else {
				return false;
			}
		}
		return $filename;
	}
	
	function saveImageCover(){
		global $CONFIG;
		$filename="";
	// return array('result'=>true,'arrImage'=> $arrImageData);
		if($_FILES['myImage']['error']==0)	{
			$path = $CONFIG['LOCAL_PUBLIC_ASSET']."user/photo/";	
			$dataImage  = $this->apps->uploadHelper->uploadThisImage(@$_FILES['myImage'],$path);
			if($dataImage['result']){
			
				$sql = "
				UPDATE social_member 
				SET  img = '{$dataImage['arrImage']['filename']}'
				WHERE id={$this->uid} LIMIT 1
				";
				$this->logger->log($sql);
				
				$qData = $this->apps->query($sql);
				if($qData)	$filename = @$dataImage['arrImage']['filename'];
			}
		}
		return $filename;
	}
	
	
	
	function saveCropImage(){
				global $CONFIG;
				
				$loginHelper = $this->apps->useHelper('loginHelper');
				
				$files['source_file'] = $this->apps->_p("imageFilename");
				$files['url'] = "{$CONFIG['LOCAL_PUBLIC_ASSET']}user/photo/";
				$arrFilename = explode('.',$files['source_file']);
				if($files==null) return false;
				$targ_w = $this->apps->_p('w');
				$targ_h =$this->apps->_p('h');
				$jpeg_quality = 90;
				
				if($files['source_file']=='') return false;
				
				//check is img have original char
						
				$arrOriginal = explode("_",$files['source_file']);
				if(is_array($arrOriginal)){
					if($arrOriginal[0]=='original') {						
						$files['source_file'] = $arrOriginal[1];
						unlink($files['url'].$files['source_file']);
						copy($files['url']."original_".$files['source_file'],$files['url'].$files['source_file']);
					}
					
				}				
			
				$src = 	$files['url'].$files['source_file'];
				copy($src, $files['url']."original_".$files['source_file']);
			
				try{
					
					$img_r = false;
					if($arrFilename[1]=='jpg' || $arrFilename[1]=='jpeg' ) $img_r = imagecreatefromjpeg($src);
					if($arrFilename[1]=='png' ) $img_r = imagecreatefrompng($src);
					if($arrFilename[1]=='gif' ) $img_r = imagecreatefromgif($src);
					if(!$img_r) return false;
					$dst_r = ImageCreateTrueColor( $targ_w, $targ_h );

					imagecopyresampled($dst_r,$img_r,0,0,$this->apps->_p('x'),$this->apps->_p('y'),	$targ_w,$targ_h,$this->apps->_p('w'),$this->apps->_p('h'));

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
								
				if(is_file($files['url'].$files['source_file'])){
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
							SELECT *
							FROM social_member 
							WHERE 
							n_status=1 AND id={$this->uid} LIMIT 1 ";
						$rs = $this->apps->fetch($sql);	
						if(!$rs)return false;
						$rs['img'] = $files['source_file'];
						//how to update the session on on fly
						if($rs) $loginHelper->setdatasessionuser($rs); 
						else return false;
						return $files['source_file'];
					}else return false;
					
				}else return false;
				
	}
	
	function saveCropCoverImage(){
		global $CONFIG;
		
		$loginHelper = $this->apps->useHelper('loginHelper');
		
		$files['source_file'] = $this->apps->_p("imageFilename");
		$files['url'] = "{$CONFIG['LOCAL_PUBLIC_ASSET']}user/cover/";
		$arrFilename = explode('.',$files['source_file']);
		if($files==null) return false;
		$targ_w = $this->apps->_p('w');
		$targ_h =$this->apps->_p('h');
		$jpeg_quality = 90;
		
		if($files['source_file']=='') return false;		
		
		//check is img have original char						
		$arrOriginal = explode("_",$files['source_file']);
		if(is_array($arrOriginal)){
			if($arrOriginal[0]=='original') {						
				$files['source_file'] = $arrOriginal[1];
				unlink($files['url'].$files['source_file']);
				copy($files['url']."original_".$files['source_file'],$files['url'].$files['source_file']);
			}
			
		}				
	
		$src = 	$files['url'].$files['source_file'];
		copy($src, $files['url']."original_".$files['source_file']);
		
		try{
			$img_r = false;
			$arrFilename[1] = strtolower($arrFilename[1]);
			if($arrFilename[1]=='jpg' || $arrFilename[1]=='jpeg' ) $img_r = imagecreatefromjpeg($src);
			if($arrFilename[1]=='png' ) $img_r = imagecreatefrompng($src);
			if($arrFilename[1]=='gif' ) $img_r = imagecreatefromgif($src);
			if(!$img_r) return false;
			$dst_r = ImageCreateTrueColor( $targ_w, $targ_h );

			imagecopyresampled($dst_r,$img_r,0,0,$this->apps->_p('x'),$this->apps->_p('y'),	$targ_w,$targ_h,$this->apps->_p('w'),$this->apps->_p('h'));

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
						
		if(is_file($files['url'].$files['source_file'])){
			$sql = "UPDATE my_wallpaper SET image = '{$files['source_file']}' WHERE myid={$this->uid} AND type=0 ORDER BY datetime DESC LIMIT 1";
			$this->logger->log($sql);			
			$qData = $this->apps->query($sql);
			if($qData){
				return $files['source_file'];
			} else return false;			
		} else return false;				
	}
	
	function isFriends($fid=null,$all=false){
		$fid = strip_tags($fid);
		if($fid=='') return false;
		if($this->uid==0) return false;
			
		$sql = "SELECT * FROM my_circle WHERE userid= {$this->uid} AND friendid IN ({$fid}) AND n_status<>0";

		$qData = $this->apps->fetch($sql,1);
		if(!$qData) return false;
		$data['total'] = count($qData);
		$data['result'] = $qData;
		
		if($data['total']>0) {
			if(!$all)return true;
			else return $data['result'];
		}else return false;
		
	}
	
	function getGroupUser(){
			$uid = strip_tags($this->apps->_request('uid'));
			if(!$uid) $uid = intval($this->uid);
			if($uid!=0 || $uid!=null) {
				$sql = "SELECT * FROM my_circle_group WHERE userid IN ({$uid}) AND n_status = 1 ORDER BY datetime DESC ";
				
				$qData = $this->apps->fetch($sql,1);
				if($qData) {
					foreach($qData as $val){
						$groupCircle[$val['id']] = $val['name'];
					}	
					if($groupCircle)	return $groupCircle;
					else return false;
				}
				
				
			}
			return false;
	}
	function getFriends($all=true,$limit=8,$start=0){
	//global user id, for list of friend of friend : 21,23,1,5,3
		$uid = strip_tags($this->apps->_request('uid'));
		$start = intval($this->apps->_p('start'));
		$group = intval($this->apps->_request('groupid'));
	
		if(!$uid) $uid = intval($this->uid);
		
		if($uid!=0 || $uid!=null) {
		
				
			//get circle group
		
				$groupdata = $this->getGroupUser($uid);
				$arrGroupId = array();
				if($groupdata) {
					
					foreach($groupdata as $key => $val){			
						$arrGroupId[] = $key;										
					}
						
				}
				
			
			if($group!=0){			
				$strGroupid = $group;
			}else {	
				array_push($arrGroupId,0);
				$strGroupid = implode(',',$arrGroupId);
			}
			// get all friend of this user
			$sql =	" SELECT count(*) total FROM ( SELECT friendid FROM my_circle WHERE groupid IN ({$strGroupid}) AND userid IN ({$uid}) AND n_status = 1 GROUP BY friendid ) a";
			// pr($sql);
			$friends = $this->apps->fetch($sql);

			if(!$friends) return false;
			if($friends['total']==0) return false;
			
			if($all) $qAllQData = " LIMIT {$start},{$limit} ";
			else  $qAllQData = "";
			
			//get circle
			$sql =	" SELECT * FROM my_circle WHERE groupid IN ({$strGroupid}) AND userid IN ({$uid}) AND n_status = 1 GROUP BY friendid  ORDER BY id DESC {$qAllQData}";
		
			$qData = $this->apps->fetch($sql,1);
			if(!$qData) return false;
		
			foreach($qData as $val){			
				$arrFriendId[ $val['friendid']] = $val['friendid'];
				$circledata[]= $val;
			}
			
			if(!$arrFriendId) return false;
			$strFriendId = implode(',',$arrFriendId);
			if($all) $qAllQData = " LIMIT {$limit} ";
			else  $qAllQData = "";
			//get friend on groups
			$sql =	" SELECT * FROM my_circle WHERE groupid IN ({$strGroupid}) AND userid IN ({$uid}) AND n_status = 1 ";
			$qData = $this->apps->fetch($sql,1);
			//catch array
			if(!$qData) return false;
			$arrFriendinGroup = false;
			foreach($qData as $key => $val){
				$arrFriendinGroup[$val['friendid']][] = $val['groupid'];
			}
				
			//get friend detail
			$sql =	" SELECT id,name,img,sex,last_name FROM social_member WHERE id IN ({$strFriendId}) AND  n_status = 1 {$qAllQData} ";
			
			$qData = $this->apps->fetch($sql,1);
			if(!$qData) return false;
			foreach($qData as $key => $val){
			
				//merge groups to friends
				if($arrFriendinGroup){
					if(array_key_exists($val['id'],$arrFriendinGroup)) $qData[$key]['groups'] = $arrFriendinGroup[$val['id']];
					else $qData[$key]['groups'] = false;
				}else $qData[$key]['groups'] = false;
				
				$frienduser[$val['id']] = $qData[$key];
				
			}
		
			if(!$circledata&&!$frienduser) return false;
			
			//merge data
			foreach($circledata as $key => $val){
				if(array_key_exists($val['friendid'],$frienduser)) $circledata[$key]['frienddetail'] = $frienduser[$val['friendid']];
				else  $circledata[$key]['frienddetail'] = false;
				
				
			}
			
			//create new array
			foreach($circledata as $key => $val){
				$circle[$val['userid']][$val['groupid']][] = $val;
			}
			
			if(!$circle) return false;
			
			// pr($circle);
			$data['result'] = $circle;
			$data['total'] = $friends['total'];	
			
		// pr($data);
			return $data;
			
			
		}
		return false;
	}
	
	function getCircleUser($all=true,$limit=8,$start=0){
		//global user id, for list of friend of friend : 21,23,1,5,3
		$uid = strip_tags($this->apps->_request('uid'));
		$start = intval($this->apps->_request('start'));

		
		if(!$uid) $uid = intval($this->uid);
		if($uid!=0 || $uid!=null) {
		
				
			//get circle group
		
				$groupdata = $this->getGroupUser($uid);
				$arrGroupId = array();
				if($groupdata) {
						
					foreach($groupdata as $key => $val){			
						$arrGroupId[] = $key;										
					}
						
				}else array_push($arrGroupId,0);
		
				
				$strGroupid = implode(',',$arrGroupId);
			
			// get all friend of this user
			$sql =	" SELECT count(*) total FROM ( SELECT friendid FROM my_circle WHERE groupid IN ({$strGroupid}) AND userid IN ({$uid}) AND n_status = 1 GROUP BY friendid ) a";
		
			$friends = $this->apps->fetch($sql);
			if(!$friends) return false;
			
			//get circle
			$sql =	" SELECT * FROM my_circle WHERE groupid IN ({$strGroupid}) AND userid IN ({$uid}) AND n_status = 1 ORDER BY id DESC  ";

			$qData = $this->apps->fetch($sql,1);
			if(!$qData) return false;
			
			foreach($qData as $val){			
				$arrFriendId[ $val['friendid']] = $val['friendid'];
				$circledata[]= $val;
			}
		
			if(!$arrFriendId) return false;
			$strFriendId = implode(',',$arrFriendId);
			if($all) $qAllQData = " LIMIT {$limit} ";
			else  $qAllQData = "";
			//get friend detail
			$sql =	" SELECT id,name,img,sex,last_name FROM social_member WHERE id IN ({$strFriendId}) AND  n_status = 1 {$qAllQData} ";
			
			$qData = $this->apps->fetch($sql,1);
			if(!$qData) return false;
			foreach($qData as $val){
				$frienduser[$val['id']] = $val;
			}
			
			if(!$circledata&&!$frienduser) return false;
			
			//merge data
			foreach($circledata as $key => $val){
				if(array_key_exists($val['friendid'],$frienduser)) $circledata[$key]['frienddetail'] = $frienduser[$val['friendid']];
				else  $circledata[$key]['frienddetail'] = false;			
			}
			
			//create new array
			foreach($circledata as $key => $val){
				$circle[$val['userid']][$val['groupid']][] = $val;
			}
			
			if(!$circle) return false;
			
			// pr($circle);
			$data['result'] = $circle;
			$data['total'] = $friends['total'];	
			
		// pr($data);
			return $data;
			
			
		}
		return false;
	
	}
	
	function createCircleUser(){
		$name = preg_replace("/_/i"," ",strip_tags($this->apps->_request('name')));
		$groupid = intval($this->apps->_p('groupid'));
		if($name=='') return false;
		if($groupid!=0){
			$sql = "
			UPDATE my_circle_group SET name=\"{$name}\"
			WHERE id={$groupid} LIMIT 1;
			";
			// pr($sql);
			$this->apps->query($sql);
			return true;
		}else{
			$sql = "
			INSERT INTO my_circle_group (name,userid,datetime,n_status)
			VALUES ('{$name}',{$this->uid},NOW(),1)
			ON DUPLICATE KEY UPDATE n_status=1;
			";		
			$this->apps->query($sql);
			if($this->apps->getLastInsertId()) return array("result"=>true,"content"=>$this->apps->getLastInsertId());
			else return false;
		}

		
	
	}
	
	function uncreateCircleUser(){
		$circleid = strip_tags($this->apps->_p('circleid'));
		// $name = str_replace("_"," ",strip_tags($this->apps->_request('name')));
		$sql = "
		UPDATE my_circle_group SET n_status=0
		WHERE id= {$circleid} AND userid={$this->uid}
		LIMIT 1
		";
		
		$result = $this->apps->query($sql);
		if($result) {
			$sql = "
			UPDATE my_circle SET groupid = 0
			WHERE userid = {$this->uid} AND groupid={$circleid}
			";
			$result = $this->apps->query($sql);			
			if($result)return true;
			else {
				$sql = "
					DELETE FROM my_circle WHERE groupid <> 0 AND userid = {$this->uid} AND groupid={$circleid}
				";
				$result = $this->apps->query($sql);	
				if($result)return true;
				else return false;
			}
		}else return false;
	
	}
	
	function addCircleUser(){
		$uid = intval($this->apps->_request('uid'));
		$groupid = intval($this->apps->_request('groupid'));

		//cek default circle , friends on circle
		if($this->uid==$uid) return false;
		$sql = "SELECT count(*) total, id FROM my_circle WHERE userid= {$this->uid} AND friendid={$uid} AND groupid=0 LIMIT 1";
			
		$qData = $this->apps->fetch($sql);
		
		if(!$qData) return false;
		if($qData['total']>0){
		$oldid = $qData['id'];
		//if found, use update to move friend
			//check they have other group
				$sql = "SELECT count(*) total, id FROM my_circle WHERE userid= {$this->uid} AND friendid={$uid} AND groupid = {$groupid} LIMIT 1";
				$qData = $this->apps->fetch($sql);
			
				if(!$qData) return false;
				if($qData['total']>0){
				//if found, update the status to true
					$sql = "
					UPDATE my_circle SET n_status = 1
					WHERE userid = {$this->uid} AND friendid={$uid} AND id={$qData['id']} LIMIT 1
					";
					
					$result = $this->apps->query($sql);	
					if($result) return true;
					else return false;
				}else{
					$sql = "
					UPDATE my_circle SET groupid = {$groupid} , n_status = 1
					WHERE userid = {$this->uid} AND friendid={$uid} AND id={$oldid} LIMIT 1
					";
					$result = $this->apps->query($sql);	
					if($result) return true;
				}
		}else{
		//if not found, re-check other id
			$sql = "SELECT count(*) total, id FROM my_circle WHERE userid= {$this->uid} AND friendid={$uid} AND groupid = {$groupid} LIMIT 1";
			$qData = $this->apps->fetch($sql);
			if(!$qData) return false;
			if($qData['total']>0){
				//if found, update the status to true
				$sql = "
				UPDATE my_circle SET n_status = 1
				WHERE userid = {$this->uid} AND friendid={$uid} AND id={$qData['id']} LIMIT 1
				";
				
				$result = $this->apps->query($sql);	
				if($result) return true;
				else return false;
				
			}else{
				//if really not found, then use insert
				$sql = "
				INSERT INTO my_circle (friendid,userid,groupid,date_time,n_status)
				VALUES ('{$uid}',{$this->uid},{$groupid},NOW(),1)
				ON DUPLICATE KEY UPDATE groupid = {$groupid}, n_status=1
				";
				
				$this->apps->query($sql);
				
				if($this->apps->getLastInsertId()) return true;
				else return false;
			}
		}		
		
		return false;
		
	
	}
	
	function deGroupCircleUser(){
		$uid = intval($this->apps->_request('uid'));
		$groupid = intval($this->apps->_request('groupid'));
		//cek friend on circle
		$sql = "SELECT count(*) total FROM my_circle WHERE userid= {$this->uid} AND friendid={$uid} AND groupid={$groupid} LIMIT 1";
		$qData = $this->apps->fetch($sql);
		if(!$qData) return false;
		if($qData['total']>0){
		//if found, use update to move friend
			$sql = "
			UPDATE my_circle SET n_status = 0
			WHERE userid = {$this->uid} AND friendid={$uid} AND groupid={$groupid} LIMIT 1
			";
			$result = $this->apps->query($sql);	
			if($result) return true;
			else return false;
		
		}else return false;
		
		
	
	}
	
	function unAddCircleUser(){
		$uid = intval($this->apps->_request('uid'));
		$groupid = intval($this->apps->_request('groupid'));
		//cek friend on circle
		$sql = "SELECT count(*) total FROM my_circle WHERE userid= {$this->uid} AND friendid={$uid}  LIMIT 1";
		$qData = $this->apps->fetch($sql);
		if(!$qData) return false;
		if($qData['total']>0){
		//if found, use update to move friend
			$sql = "
			UPDATE my_circle SET n_status = 0
			WHERE userid = {$this->uid} AND friendid={$uid} 
			";
			$result = $this->apps->query($sql);	
			if($result) return true;
			else return false;
		}else return false;
		
		
	
	}
	
	function attending($attendartype=0){
		
		$contentid = intval($this->apps->_request('contentid'));
		if($contentid==0) return false;
		
		if($attendartype!=0) {
			
			//select to my_pages_type as what
			$otherid = 0;
		}else $otherid = $this->uid;
		if($otherid==0) return false;
	
		$sql = "SELECT count(*) total FROM my_contest WHERE otherid={$otherid}  AND  mypagestype={$attendartype} AND contestid={$contentid} LIMIT 1";
			// pr($sql);
		$qData = $this->apps->fetch($sql);
		if(!$qData) return false;
		if($qData['total']>0) return false;
			
		$sql = "INSERT INTO my_contest (contestid,otherid,mypagestype,join_date,n_status) VALUES ({$contentid},{$otherid},{$attendartype},NOW(),1)";

		$this->apps->query($sql);
		if($this->apps->getLastInsertId()) return true;
		return false;
		
	}
	
	function getUserFavorite(){
		
		$uid = strip_tags($this->apps->_request('uid'));
		$start = intval($this->apps->_request('start'));	
		$limit = 9;
		if(!$uid) $uid = intval($this->uid);
		if($uid!=0 || $uid!=null) {
				$sql ="
					SELECT contentid FROM {$this->dbshema}_news_content_favorite WHERE n_status=  1 AND userid IN ({$uid})  GROUP BY contentid
					";
		
				$qData = $this->apps->fetch($sql,1);
				if($qData) {
					foreach($qData as $val){
						$favoriteData[$val['contentid']]=$val['contentid'];
					}
					
				if(!$favoriteData) return false;
				$strContentId = implode(',',$favoriteData);
				
					$sql = "
						SELECT id,title,brief,image,thumbnail_image,slider_image,posted_date,file,url,fromwho,tags,authorid,topcontent,cityid 
						FROM {$this->dbshema}_news_content 
						WHERE AND n_status<>3  AND id IN ({$strContentId}) 
						ORDER BY posted_date DESC , id DESC
						LIMIT {$start},{$limit}";
					
					$rqData = $this->apps->fetch($sql,1);
					if(!$rqData) return false;
					//cek detail image from folder
						//if is article, image banner do not shown
					foreach($rqData as $key => $val){
						if(file_exists("{$CONFIG['LOCAL_PUBLIC_ASSET']}article/{$val['image']}")) $rqData[$key]['banner'] = false;
						else $rqData[$key]['banner'] = true;		
					}
				
				if($rqData) $qData=	$this->getStatistictArticle($rqData);
				
				return $qData;
				}
		}
		return false;
	}
	
	function getSearchFriendPursuit()
	{
		$keywords = strip_tags($this->apps->_p('keywords'));	
		
		$sql = "SELECT id, name, image_profile, photo_moderation from social_member WHERE (name LIKE '%{$keywords}%' OR nickname LIKE '%{$keywords}%' OR email LIKE '%{$keywords}%') AND verified = 1 and n_status = 1";
		
		$res = $this->apps->fetch($sql,1);
		if ($res){
			
			return $res;
		}
		return false;
	}
	
	function getMasterCode()
	{
		$sql = "SELECT id, codename FROM tbl_code_detail WHERE n_status = 1";
		$result = $this->apps->fetch($sql,1);
		if ($result){
			foreach ($result as $value){
				$data[$value['id']] = $value['codename'];
				// $dataID[] = $value['id'];
				// $codeName[] = $value['codename'];
			}
			
			return $result;
		}else{
			return false;
		}
	}
	
	
	
	function getSearchFriends(){
		$limit = 16;
		$start= intval($this->apps->_request('start'));
		$searchKeyOn = array("name","email");
		$keywords = strip_tags($this->apps->_p('keywords'));	
		$keywords = rtrim($keywords);
		$keywords = ltrim($keywords);
		if(strpos($keywords,' ')) $parseKeywords = explode(' ', $keywords);
		else $parseKeywords = false;
		
		if(is_array($parseKeywords)) $keywords = $keywords.'|'.trim(implode('|',$parseKeywords));
		else  $keywords = trim($keywords);
			
			if($keywords!=''){
				foreach($searchKeyOn as $key => $val){
					$searchKeyOn[$key] = "{$val} REGEXP '{$keywords}'";
				}
				$strSearchKeyOn = implode(" OR ",$searchKeyOn);
				$qKeywords = " 	AND  ( {$strSearchKeyOn} )";
			}else $qKeywords = "";
			
		$sql = "SELECT id,name,img,email FROM social_member WHERE n_status =1  {$qKeywords} ORDER BY name ASC LIMIT {$start},{$limit}";
		$qData = $this->apps->fetch($sql,1);
	
		if(!$qData) return false;
		foreach($qData as $key => $val){
			$arrFriends[$val['id']] = $val['id']; 
		}
		// search friends
		if(!$arrFriends) return false;
		$strFriends = implode(',',$arrFriends);
		$friendsData = $this->isFriends($strFriends,true);
		$arrFriends = false;
		if($friendsData){
			foreach($friendsData as $val){
				$arrFriends[$val['friendid']] = $val['friendid'];
			}
		}
		foreach($qData as $key => $val){
			$qData[$key]['isFriends'] =false;
			if($arrFriends) {
				if(array_key_exists($val['id'],$arrFriends))$qData[$key]['isFriends'] = true;
			}
			
		}
		
		return $qData;
		
	}
	
	/* The pursuit app helper account */
	/* The Hunt Helper */
	
	/* Acount */
	function updateDataUser()
	{
		
		global $CONFIG,$LOCALE;
		
		$result['result'] = false;
		$result['message'] = false;
		
		$userSess = $this->apps->session->getSession($CONFIG['SESSION_NAME'],"WEB");
		
		$birthday = trim(strip_tags($this->apps->_p('birthday')));
		
		/*
		$userBirtthday = explode('-',$userSess->birthday);
		$userBirthdayImplode = $userBirtthday[]
		if($birthday == $userSess->birthday){
			$birthday = $userSess->birthday;
		}else{
			$exp = explode('/', $birthday);
			$birthday = $exp[2].'-'.$exp[0].'-'.$exp[1];
		}
		*/
			
		$nickname = trim(strip_tags($this->apps->_p('nickname')));
		$sex = trim(strip_tags($this->apps->_p('sex')));
		$pre_mobile = trim(strip_tags($this->apps->_p('pre_mobile')));
		$last_mobile = trim(strip_tags($this->apps->_p('last_mobile')));
		// $pre_line = trim(strip_tags($this->apps->_p('pre_line')));
		// $last_line = trim(strip_tags($this->apps->_p('last_line')));
		$oldpasswd = trim(strip_tags($this->apps->_p('oldpasswd')));
		$newpasswd = trim(strip_tags($this->apps->_p('newpasswd')));
		$confirmpasswd = trim(strip_tags($this->apps->_p('confirmpasswd')));
		
		
		$sql = "SELECT * FROM social_member WHERE id = {$userSess->id} LIMIT 1"; 
		$rs = $this->apps->fetch($sql);
		if(!$rs) return false;
		
		$password = $rs['password'];
		
		
		if ($oldpasswd&&$newpasswd&&$confirmpasswd){
			if ($oldpasswd !=''){				
				if(preg_match("/^(?=.{8,20})(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])/",$newpasswd)) {
						$oldcheckpass = $rs['password'];
						$check = sha1($oldpasswd.'{'.$rs['salt'].'}');
					if ($oldcheckpass == $check){
						if($newpasswd == $confirmpasswd){
							$password =  sha1($newpasswd.'{'.$rs['salt'].'}');
						}else {			
							$result['message'] = $LOCALE[1]['passwordnotmatch'];
							return $result;
						}
					}else {			
						$result['message'] = $LOCALE[1]['passwordnotmatch'];
						return $result;
					}
				}else {			
					$result['message'] = $LOCALE[1]['passwordwrongformat'];
					return $result;
				}
			}else {			
				$result['message'] = $LOCALE[1]['passwordcannotbeempty'];
				return $result;
			}			
		}
		
		
		$house = trim(strip_tags($this->apps->_p('house')));
		$city = trim(strip_tags($this->apps->_p('city')));
		$zipcode = trim(strip_tags($this->apps->_p('zipcode')));
		$refered_by = trim(strip_tags($this->apps->_p('refered_by')));
		$barangay = trim(strip_tags($this->apps->_p('barangay')));
		//pr($barangay);exit;
		//validate
		
		if(!$last_mobile&&!$nickname&&!$city&&!$zipcode&&!$house&&!$barangay) return false;
		
		//old validate number
			// if($pre_mobile&&$last_mobile) $mobile_number = $pre_mobile.$last_mobile;
			// else $mobile_number = $rs['phone_number'];
		
		
		
		if($last_mobile) $mobile_number = $last_mobile;
		else $mobile_number = $rs['phone_number'];
		
		if($nickname) $nickname = $nickname;
		else $nickname = $rs['nickname'];
		
		if($city) $cityID = $city;
		else $cityID = $rs['city'];		
			
		if($zipcode) $zipcode = $zipcode;
		else $zipcode = $rs['zipcode'];
		
		if($house) $house = $house;
		else $house = $rs['StreetName'];

		if($barangay) $barangay = $barangay;
		else $barangay = $rs['barangay'];
		
		
		$sql = "UPDATE social_member SET barangay='{$barangay}', nickname = '{$nickname}', phone_number = '{$mobile_number}',
				password = '{$password}', StreetName = '{$house}', city = '{$cityID}', zipcode = '{$zipcode}'
				WHERE id = {$userSess->id}";
		// pr($sql);exit;
		$res = $this->apps->query($sql);
		if($res) {
			$result['result'] = true;
			$result['message'] = "ok";
		}
		
		
		return $result;
		
	}
	
	function saveUserBrands()
	{
		global $CONFIG;
		$userSess = $this->apps->session->getSession($CONFIG['SESSION_NAME'],"WEB");
		
		$brand_primary = intval(trim($this->apps->_p('brand_primary')));
		$brand_secondary =intval(trim($this->apps->_p('brand_secondary')));
		$question_mark = intval(trim($this->apps->_p('question_mark')));
		
		
		// value 4 adalah other brands
		if($question_mark == 4){
			$other_answer = trim(strip_tags($this->apps->_p('anotherbrands')));
		}else{
			// kemungkinan gk dipake lagi soalnya udh pake dropdown
			// $other_answer = trim(strip_tags($this->apps->_p('other_answer')));
			$other_answer = "";
		}
		
		
		$sql = "UPDATE social_brand_preferences SET brand_primary = {$brand_primary}, brand_secondary = {$brand_secondary}, 
				question_mark = {$question_mark}, other_answer = '{$other_answer}'
				WHERE userid = {$userSess->id}";
		// pr($sql);
		$res = $this->apps->query($sql);
		if ($res) return TRUE;
		return FALSE;
	}
	
	function getBrandsByUser()
	{
		global $CONFIG;
		$userSess = $this->apps->session->getSession($CONFIG['SESSION_NAME'],"WEB");
		$sql = "SELECT * FROM social_brand_preferences WHERE userid = {$userSess->id} LIMIT 1";
		$res = $this->apps->fetch($sql);
		
		if ($res) return $res;
		return FALSE;
	}
	
	function getGiidUser()
	{
		global $CONFIG;
		$userSess = $this->apps->session->getSession($CONFIG['SESSION_NAME'],"WEB");
		
		$sql = "SELECT img, giid_type, giid_number FROM social_member WHERE id = {$userSess->id} LIMIT 1";
		$res = $this->apps->fetch($sql);
		if ($res) return $res;
		return FALSE;
		
	}
	
	function deactivate(){
		$deactivatepassword = strip_tags($this->apps->_p('passworddeactivate'));
		if($deactivatepassword){
			$sql = "SELECT password,salt FROM social_member WHERE id = {$this->uid} LIMIT 1";
			$res = $this->apps->fetch($sql);
			if($res){
				$approvepassword = sha1($deactivatepassword."{".$res['salt']."}");
				if($res['password']==$approvepassword){
					$sql = "UPDATE social_member SET n_status = 6 WHERE id={$this->uid} AND password='{$approvepassword}' LIMIT 1";
						$res = $this->apps->query($sql);
					if ($res) return true;		
				}
			}
		}
		return false;
		
	}
	
	function updatephoto($data=false){
		if(!$data) return false;	
		$sql = "UPDATE social_member SET image_profile = '{$data['filename']}' , photo_moderation =0 WHERE id={$this->uid} LIMIT 1";
		$res = $this->apps->query($sql);
		if ($res) return true;		
		return false;
		
	}
	
	function getMyClaimBirthday()
	{
		
		$address = strip_tags($this->apps->_p('address'));
		// $phone = strip_tags($this->apps->_p('phone'));
		$phone = $this->apps->user->phone_number;
		// $email = strip_tags($this->apps->_p('email'));
		$email = $this->apps->user->email;
		$name = strip_tags($this->apps->_p('name'));
		$idMerchan = strip_tags($this->apps->_p('id'));
		
		$date = date('Y-m-d H:i:s');
		$sql = "INSERT INTO my_merchandise (userid, merchandiseid, redeemdate, name, address, phonenumber, email, merhcandise_type, n_status ) VALUES ({$this->apps->user->id}, {$idMerchan}, '{$date}', '{$name}', '{$address}', '{$phone}', '{$email}', 1, 1)";
		$res = $this->apps->query($sql);
		// pr($sql);
		// exit;
		$ins = "UPDATE social_member SET birthday_flag = 1 WHERE id = {$this->apps->user->id}";
		$resIns = $this->apps->query($ins);
		
		
		if ($resIns) return true;		
		return false;
	}
	
	function getBirthdayGift()
	{
		
		$sql = "SELECT birthday_flag FROM social_member WHERE id = {$this->apps->user->id} ";
		
		$result = $this->apps->fetch($sql);
		if ($result['birthday_flag']){
			return false;
		}else{
			$sql = "SELECT id,image FROM  marlborohunt_merchandise WHERE merchandise_type = 1 AND stock > 0 AND n_status = 1 LIMIT 1";
			// pr($sql);exit;
			$res = $this->apps->fetch($sql);
			if ($res){
				return $res;
			}
			
		}
		
		
		return false;
	}
	
	function getMyStatement($limit=10,$mystat=false){
		$res = false;
		if($mystat){
			$sql = "
			SELECT ss.description, ms.n_status, ss.id
			FROM social_statement ss 
			LEFT JOIN my_statement ms ON ss.id=ms.statementid
			WHERE ms.n_status=2 AND ms.userid={$this->uid}
			ORDER BY ms.n_status  DESC,ms.id DESC
			LIMIT {$limit}
			" ;
			// pr($sql);
			$res = $this->apps->fetch($sql,1);
			if(!$res) return false;
		}else{
				
			$sql = "
			SELECT ss.description, ss.n_status, ss.id 
			FROM social_statement ss 
			WHERE ss.n_status = 1
			ORDER BY ss.id ASC
			LIMIT {$limit}
			" ;			
			$res = $this->apps->fetch($sql,1);
			if(!$res) return false;
			
			$sql = "
			SELECT ss.description, ms.n_status, ss.id
			FROM social_statement ss 
			LEFT JOIN my_statement ms ON ss.id=ms.statementid
			WHERE ms.n_status=2 AND ms.userid={$this->uid}
			ORDER BY ms.n_status  DESC,ms.id DESC
			LIMIT 3
			" ;
			$qData = $this->apps->fetch($sql,1);
			if($qData){
			
				foreach($qData as $val){
						$mystat[$val['id']] = $val['n_status'];
				}
				if($mystat){
					foreach($res as $key => $val){
							if(array_key_exists($val['id'],$mystat)) $res[$key]['n_status'] = $mystat[$val['id']];
					}
				}
			}
		}
		return $res;
	}
	
	function setMyStatement($statementid=false,$status=false){
		if($statementid==false)$statementid = intval($this->apps->_p('statementid'));
		if($status==false)$status = strip_tags($this->apps->_p('status'));
		
		if($status=='used') $n_status = 2;
		if($status=='unused') $n_status = 1;
		$datetimes = date("Y-m-d H:i:s");
		$total = 0;
		if($status=='used'){
			$sql = "
			SELECT COUNT(*) total 
			FROM my_statement 
			WHERE 
			userid={$this->uid}
			AND n_status = 2
			LIMIT 1
			";
		
			$qData = $this->apps->fetch($sql);
			if($qData){
				$total = intval($qData['total']);
			}	
		}
		if($total>2) return false;
		
		$sql = "
		INSERT INTO my_statement (n_status, statementid,userid,datetimes)
		VALUES ({$n_status},{$statementid},{$this->uid},'{$datetimes}')
		ON DUPLICATE KEY UPDATE n_status={$n_status},datetimes='{$datetimes}'
		" ;
		// pr($sql);
		$res = $this->apps->query($sql);
		if($this->apps->getLastInsertId()>0) {
			$this->apps->log('mystatement',"{$statementid}");
			return true;
		}
		return false;
	}
	
	function addMyStatement(){
		$mynewstatement = strip_tags($this->apps->_p('mynewstatement'));	
		$n_status = 1; /*rubah 0 klo uda mw test moderasi dari admin*/
		$datetimes = date("Y-m-d H:i:s");
		if($mynewstatement=='') return false;
		$sql = "
		INSERT INTO social_statement (n_status, description,datetimes)
		VALUES ({$n_status},'{$mynewstatement}','{$datetimes}')
		" ;
		// pr($sql);
		$res = $this->apps->query($sql);
		if($this->apps->getLastInsertId()>0) {
			$statementid = $this->apps->getLastInsertId();
			$data = $this->setMyStatement($statementid,'used');
			if($data) return true;
		}		
		return false;
	}
	
	function getUserProfileByID($id=0)
	{
		if ($id == 0) return false;
		
		$sql = "SELECT * FROM social_member WHERE id = '{$id}' LIMIT 1";
		$res = $this->apps->fetch($sql);
		if ($res) return $res;
		
		return false;
	}
	
	function recoverpassword()
	{
		global $CONFIG;
		$basedomain = $CONFIG['BASE_DOMAIN'];
		$id = $this->apps->_g('email');
		$dontsendmail = false;
	
			
			$sql = "SELECT * FROM social_member WHERE email = '{$id}' LIMIT 1";
			$res = $this->apps->fetch($sql);
			$userdata = false;
			if ($res){
				// kirim akun login
				$to = $res['email'];
				$from = $CONFIG['EMAIL_FROM_DEFAULT'];
				$subject = "Data login";
				$hashpass = $this->substringshash(md5(date("ymdhis").$res['salt'].$res['id'].$res['name']),10);
				$password = sha1($hashpass.'{'.$res['salt'].'}');
				
				$dataReset['email'] = $to;
				$dataReset['password'] = $res['password'];
				$token = urlencode64(serialize($dataReset));
				// pr($dataReset);
				// $msg = 'Username = '.$res['username'].'<br>';
				// $msg = $basedomain.'forgotpassword/verified_token/'.$token;
			
				$userdata['email'] = $to;
				$userdata['firstname'] = $res['name'];
				$userdata['lastname'] = $res['last_name'];
				$userdata['username'] = $to;
				$userdata['password'] = $res['password'];
				$userdata['trackingcode'] = "";
			}
			
			if($userdata){
					
				
				
				$this->getEmailTemplate('forgotpassword',$userdata,'send');
				// var_dump($send);
				
				print json_encode(array('status'=>TRUE));
				// print json_encode(array('data'=>$userdata));
				
				/*
					$send_mail = $this->sendGlobalMail($to,$from,$msg, 2);
					if ($send_mail['result']){
						print json_encode(array('status'=>TRUE));
					}else{
						print json_encode(array('status'=>FALSE));
					}
				*/				
				
				// $this->log->sendActivity("user send reset password",$id);
				$this->logger->log("user send recover password",$id);
			}else print json_encode(array('status'=>FALSE));
			exit;
		
	}
	
	function substringshash($hasher=null,$limit=10){
			if($hasher==null) return false;
			$strings = substr($hasher,0,$limit);
			
			return $strings;
	}
	
	function getEmailTemplate($mailtemplate='welcomeweb',$userdata=false,$sendType='send'){
		
		global $CONFIG;
		/* user data is array field */
		if($userdata==false) return false;
		
		$host = "api2.silverpop.com";
		$adminuser = "inong@marlboro.ph";
		$adminpass = "Kana9i8u!";
		$servlet = "http://api2.silverpop.com/servlet/XMLAPI";
		
		$list_id = false;
		$mailid = false;
		$email = false;
		$firstname = false;
		$username = false;
		$password = false;
		$lastname = false;
		$trackingcode = false;
		$MAIL = false;
		/* sample 
			
			$arrData['email'] = "rizal@kana.co.id";
			$arrData['firstname'] = "rizal aja";
			$arrData['username'] = "rizal@kana.co.id";
			$arrData['password'] = "9234g2934h239h40203240239480298wrjoiwtowehtoerhtiuerhteukrtj";
			$arrData['lastname'] = "9234g2934h239h40203240239480298wrjoiwtowehtoerhtiuerhteukrtj";
			$arrData['trackingcode'] = "9234g2934h239h40203240239480298wrjoiwtowehtoerhtiuerhteukrtj";
		
		*/
		foreach($userdata as $key => $val){
			$arrData[$key] = $val;
			$$key = $val;
		}
	
		
		// include "../../config/mail.inc.php";
		include "../config/mail.inc.php";
		$data =  false;
		// pr($MAIL);
		$this->logger->log('config mail '.$MAIL);
		// echo 'masuk';
		if($MAIL){
			$arrData['mailid'] =  $MAIL[$mailtemplate]['mailid'];
			$arrData['templatedataxml'] = $MAIL[$mailtemplate]['template'];
			
			
			
			
			if($sendType=='send') {
				
				$data['addRecipeForSilverPop'] =$this->addRecipeForSilverPop($arrData,$adminuser,$adminpass, $host);
				// exit;
				// echo 'ada';
				// if($data['addRecipeForSilverPop']){
					sleep(1);
					$data['sendMailViaSilverPop'] =	$this->sendMailViaSilverPop($arrData,$adminuser,$adminpass, $host);
				// }
				
			}else $data['addRecipeForSilverPop'] = $this->addRecipeForSilverPop($arrData,$adminuser,$adminpass, $host); 
			// echo 'kosong';
		}
		
		return $data;
	}
	
	function addRecipeForSilverPop($arrData,$adminname,$adminpass,  $host, $servlet="XMLAPI", $port=80, $time_out=20){
	
		$servlet = $servlet;
		
		foreach($arrData as $key => $val){
			$$key = $val;
		}
		$sock = fsockopen ($host, $port, $errno, $errstr, $time_out); // open socket on port 80 w/ timeout of 20
		$data = "xml=<?xml version=\"1.0\" encoding=\"UTF-8\" ?><Envelope><Body>";
		$data .= "<Login>";
		$data .= "<USERNAME>".$adminname."</USERNAME>";
		$data .= "<PASSWORD>".$adminpass."</PASSWORD>";
		$data .= "</Login>";
		$data .= "<AddRecipient>";
		$data .= $templatedataxml;
		$data .= "</AddRecipient>";
		$data .= "</Body></Envelope>";
		if (!$sock)
		{
		print("Could not connect to host:". $errno . $errstr);
		return (false);
		}
		$size = strlen ($data);
		fputs ($sock, "POST /servlet/" . $servlet . " HTTP/1.1\n");
		fputs ($sock, "Host: " . $host . "\n");
		fputs ($sock, "Content-type: application/x-www-form-urlencoded\n");
		fputs ($sock, "Content-length: " . $size . "\n");
		fputs ($sock, "Connection: close\n\n");
		fputs ($sock, $data);
		$buffer = "";
		while (!feof ($sock)) {
		$buffer .= fgets ($sock);
		}
		// pr($data);
		$this->logger->log('sending receipt :'.$buffer);
		fclose ($sock);
		return ($buffer);


	}
	
	function sendMailViaSilverPop($arrData,$adminname,$adminpass, $host, $servlet="XMLAPI", $port=80, $time_out=20){

		$servlet = $servlet;
		
		foreach($arrData as $key => $val){
			$$key = $val;
		}

		$sock = fsockopen ($host, $port, $errno, $errstr, $time_out); // open socket on port 80 w/ timeout of 20
		$data = "xml=<?xml version=\"1.0\" encoding=\"UTF-8\" ?><Envelope><Body>";
		$data .= "<Login>";
		$data .= "<USERNAME>".$adminname."</USERNAME>";
		$data .= "<PASSWORD>".$adminpass."</PASSWORD>";
		$data .= "</Login>";
		$data .= "<SendMailing>
		<MailingId>".$mailid."</MailingId>
		<RecipientEmail>".$email."</RecipientEmail>";	
		$data .= "</SendMailing></Body></Envelope>";
		if (!$sock)
		{
		print("Could not connect to host:". $errno . $errstr);
		return (false);
		}
		$size = strlen ($data);
		fputs ($sock, "POST /servlet/" . $servlet . " HTTP/1.1\n");
		fputs ($sock, "Host: " . $host . "\n");
		fputs ($sock, "Content-type: application/x-www-form-urlencoded\n");
		fputs ($sock, "Content-length: " . $size . "\n");
		fputs ($sock, "Connection: close\n\n");
		fputs ($sock, $data);
		$buffer = "";
		while (!feof ($sock)) {
		$buffer .= fgets ($sock);
		}
		// pr($data);
		$this->logger->log('sending mail :'.$email.$buffer);
		
		fclose ($sock);
		return ($buffer);

	}
	
	function getUserPoint()
	{
		/* 
		4 : merchandise lock
		6 : comment lock
		*/
		$n_status = "4,6";
		$sql = "SELECT sum( inv.point ) total, inv.userid
				FROM tbl_code_inventory inv
				LEFT JOIN tbl_code_detail det ON inv.codeid = det.id
				WHERE inv.n_status NOT IN ({$n_status})
				AND inv.userid = {$this->apps->user->id}";
		// pr($sql);
		$res = $this->apps->fetch($sql);
		
		$mypointinmerch = "SELECT sum( point ) total FROM my_merchandise 
							WHERE userid = {$this->apps->user->id} AND n_status = 1";
		$respointinmerch = $this->apps->fetch($mypointinmerch);
		// pr($mypointinmerch);
		
		$mypointinbucket = "SELECT sum( point ) total FROM my_bucket 
							WHERE userid = {$this->apps->user->id} AND n_status = 1 AND dochallenge = 1 ";
		$respointinbucket = $this->apps->fetch($mypointinbucket);
		
		$res['point'] = intval($res['total']) - intval($respointinmerch['total']) - intval($respointinbucket['total']);
		if ($res['point'] <0)$res['point'] = 0;
		if ($res) return $res;
		return false;
		
	}
	
	function getUserExclusive()
	{
		$name = $this->apps->user->name;
		$email = $this->apps->user->email;
		
		$sql = "SELECT id FROM tbl_exlusive_user WHERE firstname= '{$name}' AND email = '{$email}' LIMIT 1";
		$res = $this->apps->fetch($sql);
		if ($res['id']) return true;
		
		return false;
	}
	
	function showPopupOnceLogin()
	{
		$name = $this->apps->user->name;
		$email = $this->apps->user->email;
		
		$sql = "SELECT id, influencer FROM social_member WHERE name= '{$name}' AND email = '{$email}' LIMIT 1";
		// pr($sql);
		$res = $this->apps->fetch($sql);
		if ($res['influencer']==""){
			// pr($res);
			$sql = "UPDATE social_member SET influencer = 1 WHERE id = {$res['id']} LIMIT 1";
			// pr($sql);
			$result = $this->apps->query($sql);
			if ($result) return true;
			
			return false;
		}
		
		return false;
	}
	
	function getUserAccount($id=false,$email=false)
	{
		if (!$id and !$email) return false;
		if ($id)$sql = "SELECT id,name,image_profile FROM social_member WHERE id = {$id}";
		if ($email)$sql = "SELECT id,name,image_profile FROM social_member WHERE email = '{$email}'";
		// pr($sql);
		$res = $this->apps->fetch($sql);
		if ($res) return $res;
		return false;
	}
	
	function userlookpage($page=false)
	{
		if (!$page) return false;
		
		$sql = "SELECT count(*) total FROM tbl_activity_log WHERE user_id = {$this->apps->user->id} 
				AND action_value = '{$page}' AND action_id=6";
		$res = $this->apps->fetch($sql);
		// pr($sql);
		if ($res['total']>1) return true;
		return false;		
	}
}

?>

