<?php 
global $ENGINE_PATH;
include_once $ENGINE_PATH."Utility/Mobile_Detect.php";


class contentHelper {

	var $uid;
	var $osDetect;

	
	function __construct($apps){
		global $logger,$CONFIG;
		$this->logger = $logger;

		$this->apps = $apps;
		if($this->apps->isUserOnline())  {
			if(is_object($this->apps->user)) $this->uid = intval($this->apps->user->id);
			// if(is_object($this->apps->page)) $this->pageid = intval($this->apps->page->id);
			
		}
		
		if(isset($_SESSION['lid'])) $this->lid = intval($_SESSION['lid']);
		else $this->lid = 1;
		if($this->lid=='')$this->lid=1;
		$this->server = intval($CONFIG['VIEW_ON']);
		$this->osDetect = new Mobile_Detect;
		
		$this->moderation = $CONFIG['MODERATION'];
		$this->dbshema = "marlborohunt";
		$this->action_id = '20,21,23,24,25,26,27,28,30,45,55';
		
		$this->week = 7;
		$week = intval($this->apps->_request('weeks'));
		if($week!=0) $this->week = $week;
		
		$this->startweekcampaign = "2013-04-25";
		// pr($this->apps->_request('week'));
		$this->eventType = 5;
		$this->bucketType = 2;
		$this->challengeLimit = 5;
		
	}
	
	function getAuthorProfile($otherid=null,$typeAuthor='admin'){
		if($otherid==null) return false;
		
		$sql = "SELECT name, id AS authorid, '' as last_name,'' as pagestype,image as img FROM gm_member WHERE id IN ({$otherid}) LIMIT 10 ";
		if($typeAuthor == 'social' ) $sql = "SELECT name, id AS authorid, last_name,'' as pagestype,image_profile img  FROM social_member WHERE id IN ({$otherid}) LIMIT 10 ";
		if($typeAuthor == 'page' ) $sql = "SELECT name, id AS authorid , '' as last_name,type as pagestype,img FROM my_pages WHERE id IN ({$otherid}) LIMIT 10 ";
		// pr($sql);
		$data = $this->apps->fetch($sql,1);
		
		if(!$data) return false;
		
		foreach($data as $val){
			$arrData[$val['authorid']] = $val;
		}
		if(!isset($arrData)) return false;
		return $arrData;
	}
	
	function getFavoriteUrl($cid=null,$content=2){
		if($cid==null) return false;
		$sql="
		SELECT count(*) total, contentid,url FROM social_bookmark sb 
		WHERE content={$content}
		AND contentid IN ({$cid}) 
		GROUP BY contentid ";
		$qData = $this->apps->fetch($sql,1);
		if(!$qData) return false;
		foreach($qData as $val){
			$arrData[$val['contentid']] = $val['total'];
		}	
		if($arrData) return $arrData;
		return false;
	}
	
	function saveFavorite(){
		$cid = intval($this->apps->_p('cid'));
		$likes =1;
		$uid = intval($this->uid);
		if($cid!=0 && $uid!=0){
			$sql ="
					INSERT INTO {$this->dbshema}_news_content_favorite (userid,contentid,likes,date,n_status) 
					VALUES ({$uid},{$cid},{$likes},NOW(),1)
					";
			$this->apps->query($sql);
			$this->logger->log($sql);
			if($this->apps->getLastInsertId()>0) return true;
			
		}
		return false;
	}
	
	function getFavorite($cid=null){
		if($cid==null) $cid = intval($this->apps->_p('cid'));
		if($cid){
			$cidin = " AND contentid IN ({$cid}) ";
		}
			$sql ="
					SELECT count(*) total,contentid FROM {$this->dbshema}_news_content_favorite WHERE n_status=  1 {$cidin}  GROUP BY contentid
					";
		
				$qData = $this->apps->fetch($sql,1);
				if($qData) {
					$this->logger->log("have favorite");
					foreach($qData as $val){
						$favoriteData[$val['contentid']]=$val['total'];
					}
					
						return $favoriteData;
				}
		return false;
	}
	
	function addComment($cid=null,$comment=null){
		if($cid==null) $cid = intval($this->apps->_p('cid'));
		if($comment==null) $comment = strip_tags($this->apps->_p('comment'));
		$parentid = intval($this->apps->_p('parentid'));
		$gid = intval($this->apps->_p('gid'));
		if($gid==-1) $gid = 0;
		if(!$this->uid) return false;
		$uid = intval($this->uid);
		if($cid==-1) return false;
		if($cid&&$comment){
		if($comment=="") return false;
		 $datetimes = date("Y-m-d H:i:s");
		 $nstatus = 1;
		// exit;
		global $CONFIG;
			//bot system halt
			$sql = "SELECT id,date,count(id) total FROM {$this->dbshema}_news_content_comment WHERE userid={$uid} ORDER BY id DESC LIMIT 1";
			// pr($sql);
			$lastInsert = $this->apps->fetch($sql);
			$this->logger->log($lastInsert['total']);
			if($lastInsert['total']==0) $divTime = $CONFIG['DELAYTIME']+1;
			else $divTime = strtotime(date("Y-m-d H:i:s")) - strtotime($lastInsert['date']); 
			if($CONFIG['DELAYTIME']==0) $divTime = $CONFIG['DELAYTIME']+1;
			$this->logger->log(date("Y-m-d H:i:s").' .... '.$lastInsert['date']);
			if($divTime<=$CONFIG['DELAYTIME']) return false; 
			
			$sql ="
					INSERT INTO {$this->dbshema}_news_content_comment (userid,contentid,comment,date,n_status,parentid,gid) 
					VALUES ({$uid},{$cid},'{$comment}','{$datetimes}',{$nstatus},{$parentid},{$gid})
					";
				// pr($sql);exit;
			$this->apps->query($sql);
			$this->logger->log($sql);
			if($this->apps->getLastInsertId()>0) return true;
			
		} else $this->logger->log($cid.'|'.$comment);
		return false;
	}
	
	function getComment($cid=null,$all=false,$start=0,$limit=100){
		GLOBAL $CONFIG;
		// return $cid;
		
			if($cid==null) $cid = intval($this->apps->_request('id'));
		
		if(intval($this->apps->_request('start'))>=0)$start = intval($this->apps->_request('start'));
	
		if($cid){			
			if($all==true) $qAllRecord = "";
			else  $qAllRecord = " LIMIT {$start},{$limit} ";
			if($all==true) $qFieldRecord = " count(*) total , contentid ";
			else  $qFieldRecord = " * ";
			if($all==true) $qGroupRecord = " GROUP BY contentid ";
			else  $qGroupRecord = "  ";
			
			if($all!=true){
				$gid = intval($this->apps->_request('gid'));

				if($gid) $gidsearch = " AND gid = {$gid} ";
				else  $gidsearch = "  AND gid = 0 ";
		 	}else{
		 		$gidsearch = "";
		 	}
			
			
			
			$sql ="	SELECT {$qFieldRecord} FROM {$this->dbshema}_news_content_comment 
					WHERE contentid IN ({$cid}) AND n_status = 2 AND parentid = 0 {$gidsearch}
					{$qGroupRecord}
					ORDER BY date DESC {$qAllRecord}
					";
			//pr($sql);exit;
			$qData = $this->apps->fetch($sql,1);
			// pr($qData);
			$this->logger->log($sql);
			if($qData) {
			
				if($all==true) {
					foreach($qData as $val){
						$arrComment[$val['contentid']] = $val['total'];
					}
					return $arrComment;
				}
				
				
				foreach($qData as $key => $val){
					
					$comment = $this->replyComment($cid,$val['id']);
					if ($comment) $qData[$key]['replycomment'] = $comment;
					
					
					$arrUserid[$val['userid']] = $val['userid'];				
				}
				
				// pr($qData);
				// exit;
				$users = implode(",",$arrUserid);
				
				
				$sql = "SELECT * FROM social_member WHERE id IN ({$users})  AND n_status = 1 ";
				// pr($sql);
				$qDataUser = $this->apps->fetch($sql,1);
				if($qDataUser){
					// $userRate = $this->getUserFavorite($cid,$users);
					$userRate = false;
					
					foreach($qDataUser as $val){
						$userDetail[$val['id']]['name'] = $val['name'];
						
						if ($val['photo_moderation']==1){
							$userDetail[$val['id']]['img'] = $val['image_profile'];
						}else{
							$userDetail[$val['id']]['img'] = "default.jpg";
						}
						
						
					}
					// pr($qDataUser);
					foreach($qData as $key => $val){
						$arrComment[$val['contentid']][$key] = $val;
						if(array_key_exists($val['userid'],$userDetail)){
						
							$qData[$key]['imagepath'] = false;
			// pr("{$CONFIG['LOCAL_PUBLIC_ASSET']}user/photo/{$userDetail[$val['userid']]['img']}");
							if(!is_file("{$CONFIG['LOCAL_PUBLIC_ASSET']}user/photo/{$userDetail[$val['userid']]['img']}")) $userDetail[$val['userid']]['img'] = "default.jpg";
							
							$arrComment[$val['contentid']][$key]['name'] = $userDetail[$val['userid']]['name'] ;
							$arrComment[$val['contentid']][$key]['img'] = $userDetail[$val['userid']]['img'] ;
							
							$arrComment[$val['contentid']][$key]['date'] =  date("m/d/Y - g:i a",strtotime($val['date']));
						 
							
							if($userRate){
								if(array_key_exists($val['contentid'],$userRate)) {
									if(array_key_exists($val['userid'],$userRate[$val['contentid']]))$arrComment[$val['contentid']][$key]['favorite'] = $userRate[$val['contentid']][$val['userid']] ; 
									else $arrComment[$val['contentid']][$key]['favorite'] = 0;
								}else $arrComment[$val['contentid']][$key]['favorite'] = 0;
							}else  $arrComment[$val['contentid']][$key]['favorite'] = 0;
						}
					}
				
					$qData = null;
					// pr($arrComment);exit;
					return $arrComment;
				}
			}			
		}
		return false;	
	}
	
	
	function replyComment($cid, $val)
	{
		
		global $CONFIG;		
		$sql ="	SELECT * FROM {$this->dbshema}_news_content_comment 
				WHERE contentid IN ({$cid}) AND n_status = 2 AND parentid = {$val}
				ORDER BY date DESC
				";
		// pr($sql);
		$qData = $this->apps->fetch($sql,1);
		
		if ($qData){
			foreach($qData as $key => $val){
				$arrUserid[$val['userid']] = $val['userid'];	
			}
			
			$users = implode(",",$arrUserid);
		
		
			$sql = "SELECT * FROM social_member WHERE id IN ({$users})  AND n_status = 1 ";
			// pr($sql);
			$qDataUser = $this->apps->fetch($sql,1);
			if($qDataUser){
				// $userRate = $this->getUserFavorite($cid,$users);
				$userRate = false;
				
				foreach($qDataUser as $val){
					$userDetail[$val['id']]['name'] = $val['name'];
					if ($val['photo_moderation']==1){
						$userDetail[$val['id']]['img'] = $val['image_profile'];
					}else{
						$userDetail[$val['id']]['img'] = "default.jpg";
					}
					
				}
				// pr($qDataUser);
				foreach($qData as $key => $val){
					$arrComment[$key] = $val;
					if(array_key_exists($val['userid'],$userDetail)){
					
						$qData[$key]['imagepath'] = false;
		// pr("{$CONFIG['LOCAL_PUBLIC_ASSET']}user/photo/{$userDetail[$val['userid']]['img']}");
						if(!is_file("{$CONFIG['LOCAL_PUBLIC_ASSET']}user/photo/{$userDetail[$val['userid']]['img']}")) $userDetail[$val['userid']]['img'] = "default.jpg";
						
						$arrComment[$key]['name'] = $userDetail[$val['userid']]['name'] ;
						$arrComment[$key]['img'] = $userDetail[$val['userid']]['img'] ;
						
						$arrComment[$key]['date'] =  date("m/d/Y - g:i a",strtotime($val['date']));
					 
						
						if($userRate){
							if(array_key_exists($val['contentid'],$userRate)) {
								if(array_key_exists($val['userid'],$userRate[$val['contentid']]))$arrComment[$val['contentid']][$key]['favorite'] = $userRate[$val['contentid']][$val['userid']] ; 
								else $arrComment[$key]['favorite'] = 0;
							}else $arrComment[$key]['favorite'] = 0;
						}else  $arrComment[$key]['favorite'] = 0;
					}
				}
			
				$qData = null;
				// pr($arrComment);exit;
				return $arrComment;
			}
		}
		
		return false;
	
	}
	
	function getAttending($cid=null){
		if($cid==null) $cid = intval($this->apps->_p('cid'));
		if($cid){
			$cidin = " AND contestid IN ({$cid}) ";
		}
			$sql ="
					SELECT count(*) total,contestid FROM my_contest WHERE n_status=  1 {$cidin}  GROUP BY contestid
					";
		
				$qData = $this->apps->fetch($sql,1);
				if($qData) {
					$this->logger->log("have attending");
					foreach($qData as $val){
						$attendingData[$val['contestid']]=$val['total'];
					}
					
						return $attendingData;
				}
		return false;
	}
	
	function getBanner($page="home",$type="slider_header",$featured=0,$limit=4){
		global $CONFIG;
		$sql ="SELECT * FROM {$this->dbshema}_news_content_banner_type WHERE type ='{$type}' AND n_status=1 LIMIT 1 "; 
		
		$this->logger->log($sql);
		$bannerType = $this->apps->fetch($sql);	
		if(!$bannerType) return false;
		$sql ="SELECT * FROM {$this->dbshema}_news_content_page WHERE pagename = '{$page}' AND n_status = 1 LIMIT 1";		
		$this->logger->log($sql);
		$bannerPage = $this->apps->fetch($sql);
		if(!$bannerPage) return false;	
	 
		$sql = "SELECT * FROM {$this->dbshema}_news_content_banner WHERE page LIKE '%{$bannerPage['id']}%' AND type IN ({$bannerType['id']}) AND n_status IN ({$this->server}) ";
		$this->logger->log($sql);
		$banner = $this->apps->fetch($sql,1);		
		
		if(!$banner) return false;
		foreach($banner as $val){
			$arrBannerID[] = $val['parentid'];
			$banners[$val['parentid']] = $val;
		}
	
		$bannerId = implode(",",$arrBannerID) ;
		
		$sql = "	
		SELECT anc.id,anc.content,anc.brief,anc.title,anc.image,anc.posted_date ,anc.categoryid,ancc.category,anc.articleType,anc.slider_image,anc.thumbnail_image,anct.content_name,anct.type typeofarticlename,anc.url
		FROM {$this->dbshema}_news_content anc
		LEFT JOIN {$this->dbshema}_news_content_category ancc ON ancc.id = anc.categoryid
		LEFT JOIN {$this->dbshema}_news_content_type anct ON anct.id = anc.articleType
		WHERE anc.id IN ({$bannerId}) AND anc.n_status IN ({$this->server})
		ORDER BY posted_date DESC  LIMIT {$limit}
		";
		
		$this->logger->log($sql);
		// pr($sql);
		$qData = $this->apps->fetch($sql,1);
		if(!$qData) return false;
		foreach($qData as $key => $val){
			if(array_key_exists($val['id'],$banners)) $qData[$key]['banner'] = $banners[$val['id']];			
			
			if(is_file("{$CONFIG['LOCAL_PUBLIC_ASSET']}banner/thumb_{$val['slider_image']}")) $qData[$key]['banner_thumb'] = true;
			else  $qData[$key]['banner_thumb'] = false;
				// pr("{$CONFIG['LOCAL_PUBLIC_ASSET']}banner/thumb_{$val['slider_image']}");
			//parseurl for video thumb
				$video_thumbnail = false;
				if($val['articleType']==3&&$val['url']!='')	{				
					//parser url and get param data
						$parseUrl = parse_url($val['url']);
						if(array_key_exists('query',$parseUrl)) parse_str($parseUrl['query'],$parseQuery);
						else $parseQuery = false;
						if($parseQuery) {
							if(array_key_exists('v',$parseQuery))$video_thumbnail = $parseQuery['v'];
						} 
						$qData[$key]['video_thumbnail'] = $video_thumbnail;
				}else $qData[$key]['video_thumbnail'] = false;		
			
		}
		
		return $qData;
	}	
	
	function getCity_($province=NULL, $type=NULL, $cityID=NULL){
		if($cityID){
			$filter = 'AND id = '.$cityID;
			$default = "SELECT * FROM {$this->dbshema}_city_reference WHERE  provinceid<>0  AND city <> '(NOT SPECIFIED)' AND id ={$cityID} ORDER BY city";
			$qDefault = $this->apps->fetch($default);
		}else{
			$filter = '';
		}
		
		if ($province) {
			$filterprov = " provinceid = {$province}";
		} else {
			$filterprov = "";
		}
		
		$sql ="SELECT * FROM {$this->dbshema}_city_reference WHERE provinceid <> 0 AND city <> '(NOT SPECIFIED)' {$filterprov} {$filter}  ORDER BY city LIMIT 10";
		$qData = $this->apps->fetch($sql,1);
		$this->logger->log($sql);
		
		if($type=='topup'){
			array_unshift($qData, $qDefault);
		}		
		
		if(!$qData) return false;
		return $qData;
	}
	
	function getTypeContent(){
		$sql_type ="SELECT id,type FROM {$this->dbshema}_news_content_type WHERE id IN ('3','4','5','6') ORDER BY id";
		$qData = $this->apps->fetch($sql_type,1);
		
		if(!$qData) return false;
		return $qData;
	}
	
	function getEventArticleType(){
		$sql_type ="SELECT * FROM {$this->dbshema}_news_content_type WHERE content = 4 ORDER BY id";
		$qData = $this->apps->fetch($sql_type,1);
		
		if(!$qData) return false;
		return $qData;
	}
	
	function getProvince_($type=null,$id=null){
		if($id){
			$filter = 'WHERE id <> '.$id;
			$default = "SELECT * FROM {$this->dbshema}_province_reference WHERE id = ".$id;
			$qDefault = $this->apps->fetch($default);
		}else{
			$filter = '  ';
		}
		// pr($id);
		if($type=='topup'){$filterProvince = 'AND id IN (1,2,3,4,5,6,7,8,9,10,11,12)';}
		else if($type=='coverage'){$filterProvince = 'AND id IN (1,2,3,4,5,6,8,9,10,13,14,16,19,21,24,30)';}
		else if($type=='coveragemap'){$filterProvince = 'WHERE id IN (1,2,3,4,5,6,7,8,9,10,11,12,16,17,25,26,27,28,29,30)';}
		else{$filterProvince = '';}
	
		$sql ="SELECT * FROM {$this->dbshema}_province_reference {$filter} {$filterProvince}";
		$this->logger->log($sql);
		$qData = $this->apps->fetch($sql,1);
		
		if($type=='coverage' || $type=='topup'){
			array_unshift($qData, $qDefault);
		}
		
		
		if(!$qData) return false;
		return $qData;
	}
	
	// add by putra featured article
	function getArticleFeatured($contenttype=0,$topcontent=array(2)) {
		global $CONFIG;
		$topcontent = implode(',',$topcontent);
		$contenttype = intval($contenttype);
		$typeid = strip_tags($this->checkPage($contenttype));
		
		if(!$typeid) return false;
		
		$sql = "SELECT id,title,brief,image,thumbnail_image,slider_image,posted_date,file,url,fromwho,tags,authorid,topcontent,cityid ,articleType,can_save,content
		FROM {$this->dbshema}_news_content WHERE articleType IN ({$typeid}) AND topcontent={$topcontent}  AND n_status=1 ORDER BY posted_date DESC ,id DESC  LIMIT 1";
		
		$qData = $this->apps->fetch($sql,1);
		if(!$qData) return false;
		//CEK DETAIL IMAGE FROM FOLDER
		//IF IS ARTICLE, IMAGE BANNER DO NOT SHOWN
		foreach($qData as $key => $val){
			$qData[$key]['imagepath'] = false;
			
			if(is_file("{$CONFIG['LOCAL_PUBLIC_ASSET']}event/{$val['image']}")) 	$qData[$key]['imagepath'] = "event";
			if(is_file("{$CONFIG['LOCAL_PUBLIC_ASSET']}banner/{$val['image']}")) 	$qData[$key]['imagepath'] = "banner";
			if(is_file("{$CONFIG['LOCAL_PUBLIC_ASSET']}article/{$val['image']}"))  	$qData[$key]['imagepath'] = "article";	
		
			if(is_file("{$CONFIG['LOCAL_PUBLIC_ASSET']}article/{$val['image']}")) $qData[$key]['banner'] = false;
			else $qData[$key]['banner'] = true;	
			
			//CHECK FILE
			if(is_file("{$CONFIG['LOCAL_PUBLIC_ASSET']}music/mp3/{$val['file']}")) $qData[$key]['hasfile'] = true;
			else $qData[$key]['hasfile'] = false;				
			
			//PARSEURL FOR VIDEO THUMB
			if($val['articleType']==3&&$val['url']!='')	{
				//PARSER URL AND GET PARAM DATA
				$parseUrl = parse_url($val['url']);
				if(array_key_exists('query',$parseUrl)) parse_str($parseUrl['query'],$parseQuery);
				else $parseQuery = false;
				if($parseQuery) {
					if(array_key_exists('v',$parseQuery))$video_thumbnail = $parseQuery['v'];
					else $video_thumbnail= false;
				}else $video_thumbnail = false;
				$qData[$key]['video_thumbnail'] = $video_thumbnail;
			}else $qData[$key]['video_thumbnail'] = false;		
		}
		
		if($qData) $qData =	$this->getStatistictArticle($qData);
		else return false;
		return $qData;
	}
	
	function getArticleContent($start=null,$limit=10,$contenttype=0,$topcontent=array(0,3)) {
		global $CONFIG;
		
		$result['result'] = false;
		$result['total'] = 0;
		
		if($start==null)$start = intval($this->apps->_request('start'));
		$contenttype = intval($contenttype);
		$limit = intval($limit);
		$topcontent = implode(',',$topcontent);
		
		//RUN FILTER ENGINE, KEYWORDSEARCH , CONTENTSEARCH 
		$filter = $this->apps->searchHelper->filterEngine($limit);
		$typeid = strip_tags($this->checkPage($contenttype));		
	
		if(!$typeid) return false;		
		
		$file = "";
	
		//GET ARTICLE INTERVIEW
		$arrInterview = "";
		$interview = "";
		$sql_interview = "SELECT * FROM {$this->dbshema}_news_content WHERE articleType IN ({$typeid}) AND topcontent IN (3) {$file} AND n_status=1 ORDER BY posted_date DESC LIMIT 2";
		$arrInterview = $this->apps->fetch($sql_interview,1);
		if($arrInterview) {
			if($start==0) {
				$limit = $limit-4;
			} elseif($start==10) {
				$start = 10 - 4;
				$limit = 10;
			} else {
				$start = $start - 4;
				$limit = 10;
			}
			$interData=	$this->getStatistictArticle($arrInterview);
			foreach ($interData as $k => $v) {
				$interview[] = $v;
			}
		} else {
			$interview = false;
		}
		
		if ($typeid==3||$typeid==15) {
			$file = " AND NOT EXISTS (SELECT contentid FROM my_images WHERE contentid = anc.id ORDER BY date DESC LIMIT 50) ";
			//$file = " AND file <> '' ";
		} else{
			$file ="";
		}
		
		//GET TOTAL ARTICLE
		$sql = "SELECT count(*) total FROM {$this->dbshema}_news_content anc  WHERE articleType IN ({$typeid}) AND topcontent IN ({$topcontent}) {$file} {$filter['keywordsearch']} {$filter['categorysearch']} {$filter['citysearch']} {$filter['postedsearch']} {$filter['fromwhosearch']} AND n_status=1 ";
		$total = $this->apps->fetch($sql);
		
		if(intval($total['total'])<=$limit) $start = 0;
		
		$sql = "
			SELECT id,title,brief,image,thumbnail_image,slider_image,posted_date,file,url,fromwho,tags,authorid,topcontent,cityid ,articleType,can_save
			FROM {$this->dbshema}_news_content anc
			{$filter['subqueryfilter']} 
			WHERE articleType IN ({$typeid}) AND topcontent IN ({$topcontent}) {$file} {$filter['keywordsearch']} {$filter['categorysearch']} {$filter['citysearch']} {$filter['postedsearch']} {$filter['fromwhosearch']} AND n_status=1   
			ORDER BY {$filter['suborderfilter']}  posted_date DESC , id DESC
			LIMIT {$start},{$limit}";
		
		$rqData = $this->apps->fetch($sql,1);
		if($rqData) {
			//CEK DETAIL IMAGE FROM FOLDER
			//IF IS ARTICLE, IMAGE BANNER DO NOT SHOWN
			foreach($rqData as $key => $val){
				$rqData[$key]['imagepath'] = false;
				if(is_file("{$CONFIG['LOCAL_PUBLIC_ASSET']}event/{$val['image']}")) 	$rqData[$key]['imagepath'] = "event";
				if(is_file("{$CONFIG['LOCAL_PUBLIC_ASSET']}banner/{$val['image']}")) 	$rqData[$key]['imagepath'] = "banner";
				if(is_file("{$CONFIG['LOCAL_PUBLIC_ASSET']}article/{$val['image']}"))  	$rqData[$key]['imagepath'] = "article";					
				
				if(is_file("{$CONFIG['LOCAL_PUBLIC_ASSET']}article/{$val['image']}")) 	$rqData[$key]['banner'] = false;
				else $rqData[$key]['banner'] = true;
				
				//CHECK FILE
				if(is_file("{$CONFIG['LOCAL_PUBLIC_ASSET']}music/mp3/{$val['file']}")) $rqData[$key]['hasfile'] = true;
				else $rqData[$key]['hasfile'] = false;	
				
				//CHECK FILE SMALL
				if(is_file("{$CONFIG['LOCAL_PUBLIC_ASSET']}{$rqData[$key]['imagepath']}/small_{$val['image']}")) $rqData[$key]['image'] = "small_{$val['image']}";
				
				//PARSEURL FOR VIDEO THUMB
				$video_thumbnail = false;
				if($val['url']!='')	{
					//PARSER URL AND GET PARAM DATA
					$parseUrl = parse_url($val['url']);
					if(array_key_exists('query',$parseUrl)) parse_str($parseUrl['query'],$parseQuery);
					else $parseQuery = false;
					if($parseQuery) {
						if(array_key_exists('v',$parseQuery))$video_thumbnail = $parseQuery['v'];
					} 
					$rqData[$key]['video_thumbnail'] = $video_thumbnail;
				}else $rqData[$key]['video_thumbnail'] = false;
			}
			
			if($rqData) $qData=	$this->getStatistictArticle($rqData);
			else $qData = false;
		}else $qData = false;
		
		$result['articleinter'] = $interview;
		$result['result'] = $qData;
		$result['total'] = intval($total['total']);
		return $result;
	}
	
	function getStatistictArticle($rqData=null){
		
		if($rqData==null) return false;
		global $CONFIG;
		/* path to page */
		$profilepath[1] = "myband";
		$profilepath[4] = "mydj";
		
		$adminArrAuhtor = false;
		$socialArrAuthor = false;
		$pageArrAuhtor = false;
		$adminProfile = false;
		$socialProfile = false;
		$pageProfile = false;
		$qData = false;
		$cidArr = false;
		$cityData = false;
		$arrCityID = false;
		foreach($rqData as $key => $val){
		
			$cidArr[] = $val['id'];
			if(array_key_exists('cityid',$val)) $arrCityID[$val['cityid']] = intval($val['cityid']);
			
			//get profile array
			if($val['fromwho']==0) $adminArrAuhtor[] = $val['authorid'];
			if($val['fromwho']==1) $socialArrAuthor[] = $val['authorid'];
			if($val['fromwho']==2) $pageArrAuhtor[] = $val['authorid'];
			
			$qData[$key] = $val;
			$qData[$key]['ts'] = strtotime($val['posted_date']);
			$qData[$key]['user'] = false;
			// $qData[$key]['comment'] = false;
			$qData[$key]['commentcount'] = 0;
			$qData[$key]['favorite'] = 0;
			$qData[$key]['views'] = 0;
			$qData[$key]['author'] = false;
			$qData[$key]['attending'] = 0;
			$qData[$key]['gallery'] = false;
			$qData[$key]['profilepath'] = false;
			$qData[$key]['cityname'] = false;
		}
		
		if(!$cidArr) return false;
		$cidStr = implode(",",$cidArr);		
		
		if(!$arrCityID) return false;
		$cityArr = implode(",",$arrCityID);		
		
		//get profiler
		if($adminArrAuhtor) {
			$adminStr = implode(",",$adminArrAuhtor);
			$adminProfile = $this->getAuthorProfile($adminStr,'admin');
		}
		if($socialArrAuthor){
			$socialStr = implode(",",$socialArrAuthor);
			$socialProfile = $this->getAuthorProfile($socialStr,'social');
		}
		if($pageArrAuhtor) {
			$pageStr = implode(",",$pageArrAuhtor);
			$pageProfile = $this->getAuthorProfile($pageStr,'page');
		}
		
		if($cityArr){
			$cityData = $this->checkCity($cityArr);
		}
		
		//merge profiler
		foreach($qData as $key => $val){
				//admin profile
				if($adminProfile)	if($val['fromwho']==0)  if(array_key_exists($val['authorid'],$adminProfile)) $qData[$key]['author'] = $adminProfile[$val['authorid']];
				//user profile
				if($socialProfile)	if($val['fromwho']==1)  if(array_key_exists($val['authorid'],$socialProfile)) $qData[$key]['author'] = $socialProfile[$val['authorid']];
				//page profile
				if($pageProfile) if($val['fromwho']==2)  if(array_key_exists($val['authorid'],$pageProfile)) $qData[$key]['author'] = $pageProfile[$val['authorid']];
				//city data
				if($cityData)  if(array_key_exists($val['cityid'],$cityData)) $qData[$key]['cityname'] = $cityData[$val['cityid']];
				
				
				//admin profile
				if($adminProfile)	if($val['fromwho']==0)  $qData[$key]['profilepath'] = false;
				//user profile
				if($socialProfile)	if($val['fromwho']==1) $qData[$key]['profilepath'] = "friends";
				//page profile
				if($pageProfile) {
					if($val['fromwho']==2)  {
						if(array_key_exists($val['authorid'],$pageProfile)) {
							if(array_key_exists($pageProfile[$val['authorid']]['pagestype'],$profilepath)) $qData[$key]['profilepath'] = $profilepath[$pageProfile[$val['authorid']]['pagestype']];
							else $qData[$key]['profilepath'] = false;
							
						if(is_file("{$CONFIG['LOCAL_PUBLIC_ASSET']}pages/{$pageProfile[$val['authorid']]['img']}")){
							$qData[$key]['imagepath'] = "pages";		
							$qData[$key]['image'] = $pageProfile[$val['authorid']]['img'];
						}
						
						}else $qData[$key]['profilepath'] = false;
						
						
					}
				}
				
		}
		
		// favorite or like data
		$favoriteData = $this->getFavorite($cidStr);
		if($favoriteData){
			
			foreach($qData as $key => $val){
				if(array_key_exists($val['id'],$favoriteData)) $qData[$key]['favorite'] = $favoriteData[$val['id']];
				
			
			}
		
		}
		
		//comment di list article 
		$commentsData = $this->getComment($cidStr,true);
		
		if($commentsData){
			foreach($qData as $key => $val){
				if(array_key_exists($val['id'],$commentsData)) {
					// $qData[$key]['comment'] = $commentsData[$val['id']];
					$qData[$key]['commentcount'] = $commentsData[$val['id']];
				}
				
			
			}
			// pr($qData);
		}
		
		// gallery or repository data
		$gallerydata = $this->getContentRepository(false,$cidStr);
		if($gallerydata){
			
			foreach($qData as $key => $val){
				if(array_key_exists($val['id'],$gallerydata)) $qData[$key]['gallery'] = $gallerydata[$val['id']];
				
			
			}
			 
		}
		//get views
		$getTotalViewsArticle = $this->getTotalViewsArticle($cidStr);
		if($getTotalViewsArticle){
			
			foreach($qData as $key => $val){
				if(array_key_exists($val['id'],$getTotalViewsArticle)) $qData[$key]['views'] = $getTotalViewsArticle[$val['id']];
				
			
			}
		
		}
		
		//get attending
		$attendingData = $this->getAttending($cidStr);
		if($attendingData){
			
			foreach($qData as $key => $val){
				if(array_key_exists($val['id'],$attendingData)) $qData[$key]['attending'] = $attendingData[$val['id']];
				
			
			}
		
		}	
		
		
		if($qData) {
			return $qData;
		} else {
		return false;
		}
	}
	
	function checkCity($strCity=null){
			if($strCity==null) return false;
			$sql ="SELECT * FROM {$this->dbshema}_city_reference WHERE id IN ({$strCity}) LIMIT 20 ";
			// pr($sql);
			$qData = $this->apps->fetch($sql,1);
			if(!$qData)return false;
			$rqData = false;
			foreach($qData as $val){
				$rqData[$val['id']] = $val['city'];
			}	
			
			return $rqData;
			
	}
	
	function checkPage($contenttype=0){
	
		$articleType = strip_tags($this->apps->_g('page'));
		if($articleType=="event" || $articleType=="myband" || $articleType=="mydj") {
			$contenttype = "3,4";			
			$sql = "SELECT * FROM {$this->dbshema}_news_content_type WHERE content IN ({$contenttype})";
		}else $sql = "SELECT * FROM {$this->dbshema}_news_content_type WHERE type = '{$articleType}' AND content= '{$contenttype}' LIMIT 1";
		$arrType = $this->apps->fetch($sql,1);
		
		if(!$arrType) return false;
		foreach($arrType as $val){
			$arrtypeid[] = $val['id'];
		}
		$typeid = false;
		if($arrtypeid) $typeid = implode(',',$arrtypeid);
		else return false;
		return $typeid;
	}
	
	function getDetailArticle($start=null,$limit=1,$contenttype=false) {
		global $CONFIG;
		//pr($this->apps->user);
		if($start==null)$start = intval($this->apps->_request('start'));
		$category = intval($this->apps->_request('cid'));
		$id = intval($this->apps->_request('id'));
		
		$limit = intval($limit);
	
		if ($this->apps->_request('nolimit')) $start = 0;
		
		if($category!=0) $qCategory = " AND categoryid={$category} ";
		else $qCategory = "";
		
		if($id!=0) $qid = " AND acontent.id={$id} ";
		else $qid = ""; 
		
		if($contenttype){
			$contenttype = intval($contenttype);
			$qType = " AND articleType = {$contenttype} ";
		}else $qType = "";
		// $typeid = strip_tags($this->checkPage($contenttype));
		//get total
		$sql = "
		SELECT count(*) total  
		FROM {$this->dbshema}_news_content acontent
		LEFT JOIN {$this->dbshema}_news_content_category acategory ON acontent.categoryid = acategory.id
		WHERE  n_status=1  {$qid}  {$qCategory} {$qType} ";

		$totaldata = $this->apps->fetch($sql);
		if(!$totaldata) return false;
		if($totaldata['total']<=0) return false;
		
		$sql = "
		SELECT acontent.*, acategory.point ,acategory.category,anct.type  
		FROM {$this->dbshema}_news_content acontent
		LEFT JOIN {$this->dbshema}_news_content_type anct ON acontent.articleType = anct.id
		LEFT JOIN {$this->dbshema}_news_content_category acategory ON acontent.categoryid = acategory.id
		WHERE  n_status=1  {$qid}  {$qCategory} {$qType} 
		ORDER BY posted_date DESC LIMIT {$start},{$limit}";

		$rqData = $this->apps->fetch($sql,1);
		if($rqData){
			//cek detail image from folder
				//if is article, image banner do not shown
			foreach($rqData as $key => $val){
				$rqData[$key]['session_userid'] = $this->apps->user->id;
				$rqData[$key]['session_pageid'] = @$this->apps->user->pageid;
				$untags = unserialize($val['tags']);
				if(is_array($untags)) $rqData[$key]['un_tags'] = implode(",",$untags);	
				else $rqData[$key]['un_tags'] = false;
				$rqData[$key]['imagepath'] = false;
								
				if(is_file("{$CONFIG['LOCAL_PUBLIC_ASSET']}event/{$val['image']}")) 	$rqData[$key]['imagepath'] = "event";
				if(is_file("{$CONFIG['LOCAL_PUBLIC_ASSET']}banner/{$val['image']}")) 	$rqData[$key]['imagepath'] = "banner";
				if(is_file("{$CONFIG['LOCAL_PUBLIC_ASSET']}article/{$val['image']}"))  	$rqData[$key]['imagepath'] = "article";	
				if(is_file("{$CONFIG['LOCAL_PUBLIC_ASSET']}news/{$val['image']}"))  	$rqData[$key]['imagepath'] = "news";	
				if(is_file("{$CONFIG['LOCAL_PUBLIC_ASSET']}video/{$val['image']}"))  	$rqData[$key]['imagepath'] = "video";	
				// pr(is_file("{$CONFIG['LOCAL_PUBLIC_ASSET']}article/{$val['image']}"));
				//check file
				if(is_file("{$CONFIG['LOCAL_PUBLIC_ASSET']}music/mp3/{$val['file']}")) $rqData[$key]['hasfile'] = true;
				else $rqData[$key]['hasfile'] = false;	
				if(is_file("{$CONFIG['LOCAL_PUBLIC_ASSET']}video/{$val['file']}")) $rqData[$key]['hasfile'] = true;
				else $rqData[$key]['hasfile'] = false;	
				
				//parseurl for video thumb
				$video_thumbnail = false;
				if($val['url']!='')	{
					//parser url and get param data
						$parseUrl = parse_url($val['url']);
						if(array_key_exists('query',$parseUrl)) parse_str($parseUrl['query'],$parseQuery);
						else $parseQuery = false;
						if($parseQuery) {
							if(array_key_exists('v',$parseQuery))$video_thumbnail = $parseQuery['v'];
						} 
						$rqData[$key]['video_thumbnail'] = $video_thumbnail;
				}else $rqData[$key]['video_thumbnail'] = false;		

					$rqData[$key]['content'] = html_entity_decode($val['content']);
					if($rqData[$key]['imagepath']==false) $rqData[$key]['image'] = '';
					// $rqData[$key]['posted_date'] = date("d F Y  g:i a",strtotime($val['posted_date']));
			}
		}
		if($rqData) $qData=	$this->getStatistictArticle($rqData);
		else $qData = false;
		//pr($qData);exit;
		if(!$qData) return false;
		if($this->uid && $qData){
		
				$sql ="
				INSERT INTO {$this->dbshema}_news_content_rank (categoryid ,	point, 	userid ,	date) 
				VALUES ({$qData[0]['categoryid']},{$qData[0]['point']},{$this->uid},NOW())
				
				";
				$this->apps->query($sql);
				
				// job buat bot tracking user preference
				// $sql ="
				// INSERT INTO job_content_preference (user_id ,	content_id, 	n_status) 
				// VALUES ({$this->uid},{$qData['id']},0)
				
				// ";
				
				// $this->apps->query($sql);
		
	
		}
		
		
		if(!$qData) return false;
		
		$result['result'] = $qData;
		$result['total'] = $totaldata['total'];		
		 //pr($result);
		return $result;
	}
	
	function getTotalViewsArticle($cid=null){
		if($cid==null) return false;
		
		$sql = "SELECT COUNT(DISTINCT(user_id)) total, action_value as cid FROM tbl_activity_log WHERE action_id=2 AND action_value IN ({$cid}) GROUP BY action_value";
		//pr($sql);
		$qData = $this->apps->fetch($sql,1);
		if(!$qData) return false;
		$arrViewArticle = false;
		foreach($qData as $key => $val){
			$arrViewArticle[$val['cid']] = $val['total'];
		}
		if($arrViewArticle){
			return $arrViewArticle;
		}else return false;
		
	}
	
	function getContentRepository($gallerytype=false,$strId=null,$limit=200,$start=0){
		
		if($strId==null) return false;
	 
		
		$gallerytype = intval($gallerytype);
		$limit = intval($limit);
				
		// $sql = "SELECT * FROM  {$this->dbshema}_news_content_repo WHERE otherid IN ({$strId}) AND n_status=1 ORDER BY created_date DESC LIMIT {$limit} ";
		
		$sql = "
		
		SELECT *
		FROM 
		(
			(
				SELECT `id`, `title`, `brief`, `content`, `typealbum`, `gallerytype`, `files`, `thumbnail`, `fromwho`, `otherid`, `userid`, `created_date`, `winning_date`, `n_status`, 'repo'  fromtab
				FROM `{$this->dbshema}_news_content_repo` WHERE n_status IN (1,3) AND otherid IN ({$strId}) ORDER BY created_date DESC  
			)
			UNION
			(
				SELECT 	 
				`id`,'' `title`,other `brief`, `content`,1 `typealbum`,post_type `gallerytype`,image `files`,video `thumbnail`,1 `fromwho`,content_id `otherid`, `userid`, datetime `created_date`,datetime `winning_date`, `n_status` , 'poster' fromtab  
				FROM `my_post` 
				WHERE n_status = 1 
				AND content_id IN ({$strId}) 
				AND image IS NOT NULL AND image <> '' ORDER BY datetime DESC  
			)

		) viewstabs
		ORDER BY created_date DESC 
		";
		
		/* 
		$sql = "
		SELECT `id`, `title`, `brief`, `content`, `typealbum`, `gallerytype`, `files`, `thumbnail`, `fromwho`, `otherid`, `userid`, `created_date`, `winning_date`, `n_status` FROM  {$this->dbshema}_news_content_repo WHERE otherid IN ({$strId}) AND n_status=1 ORDER BY created_date DESC LIMIT {$start},{$limit}
		"; 
		*/
		// pr($sql);
		$rqData = $this->apps->fetch($sql,1);
		// pr($rqData);
		if(!$rqData) return false;
		$qData = false;
		foreach($rqData as $key => $val){
			$qData[$val['otherid']][$val['id']] = $val;
		}
		
		if(!$qData) return false;
		
		return $qData;
	
	}
	
	function getListSongs($start=null,$limit=9) {
		global $CONFIG;
		
		$pid = intval($this->apps->_request('pid'));
		if(!$pid) $pid = intval($this->apps->user->pageid);
		if($pid!=0 || $pid!=null) {		
			if($start==null)$start = intval($this->apps->_request('start'));
			$limit = intval($limit);
			$pages = $this->apps->_g('page');
			$userid = $this->uid;
			if ($pages=='my') {
				//GET IDCONTENT PLAYLIST
				$sql_playlist = "SELECT id,otherid FROM my_playlist where myid = {$userid} AND n_status=1 ORDER BY datetime DESC";
				$dataPlaylist = $this->apps->fetch($sql_playlist,1);
				if ($dataPlaylist) {
					foreach($dataPlaylist as $key => $val){
						$idcontent[] = $val['otherid'];
					}
					if(!$idcontent) return false;
					$arrIdContent = implode(",",$idcontent);
				} else return false;
				
				//GET TOTAL PLAYLIST
				$sql_total = "SELECT count(*) total FROM {$this->dbshema}_news_content WHERE id IN ({$arrIdContent}) AND n_status = 1";
				$total = $this->apps->fetch($sql_total);
				
				if(!$total) return false;
				if($start>intval($total['total'])) return false;
				if(intval($total['total'])<=$limit) $start = 0;
				
				//GET DATA PLAYLIST
				$sql = " SELECT * FROM {$this->dbshema}_news_content WHERE id IN ({$arrIdContent}) AND n_status = 1 LIMIT {$start},{$limit}";
			} elseif ($pages=='myband' || $pages=='mydj' ) {
				$type = $pages=='myband' ? 1 : 4;
				
				//GET TOTAL SONGS
				$sql_total = "SELECT count(*) total FROM {$this->dbshema}_news_content WHERE fromwho = 2 AND articleType = 3 AND file <> '' AND authorid = {$pid} AND n_status = 1";
				$total = $this->apps->fetch($sql_total);
				
				if(!$total) return false;
				if($start>intval($total['total'])) return false;
				if(intval($total['total'])<=$limit) $start = 0;
				
				//GET DATA SONGS
				$sql = "SELECT * FROM {$this->dbshema}_news_content WHERE fromwho = 2 AND articleType = 3 AND file <> '' AND authorid = {$pid} AND n_status = 1 ORDER BY posted_date DESC LIMIT {$start},{$limit}";
			} else return false;
			$rqData = $this->apps->fetch($sql,1);
			
			$no=1;
			if($rqData) {
				foreach ($rqData as $k => $v) {
					$v['no'] = $no++;
					if ($v['filesize']) {
						$durasi = $v['filesize']/1000;
						$v['filesize'] = sprintf("%02d:%02d", ($durasi /60), $durasi %60 );
					} else $v['filesize'] = "";
					$rqData[$k] = $v;
					
					if(is_file("{$CONFIG['LOCAL_PUBLIC_ASSET']}music/mp3/{$v['file']}")) $rqData[$k]['hasfile'] = true;
					else $rqData[$k]['hasfile'] = false;				
				}
			}
			
			if($rqData) $qData=	$this->getStatistictArticle($rqData);
			else $qData = false;
			
			if(!$qData) return false;
			$arrPlaylist['result'] = $qData;
			$arrPlaylist['total'] = $total['total'];
			return $arrPlaylist;
		}
		return false;
	}
	
	function getMygallery($start=null,$limit=3,$userid=NULL) {
		global $CONFIG;
		if($start==null) $start = intval($this->apps->_g('start'));
		
		if(strip_tags($this->apps->_g('page'))=='my') $userid = $this->uid;
		else $userid = intval($this->apps->_g('uid'));
				
		//get total gallery
		$sql_total = "
			SELECT count(*) total FROM `my_images` mm
			WHERE mm.userid = '{$userid}'
			AND mm.n_status = 1
		";
		$total = $this->apps->fetch($sql_total);
		
		if(!$total) return false;
		if($start>intval($total['total'])) return false;
		if(intval($total['total'])<=$limit) $start = 0;
		
		$sql = "
			SELECT mm.*,nc.title,nc.brief,nc.image,nc.file,nc.articleType,ct.content as typecontent,ct.type as typeofarticle,nc.fromwho,nc.authorid,nc.posted_date , nc.url
			FROM `my_images` mm
			LEFT JOIN {$this->dbshema}_news_content nc ON mm.contentid = nc.id
			LEFT JOIN {$this->dbshema}_news_content_type ct ON nc.articleType = ct.id
			WHERE mm.userid = '{$userid}' AND mm.type = 0
			AND mm.n_status = 1 ORDER BY mm.date DESC LIMIT {$start},{$limit}
		";
		
		$rqData = $this->apps->fetch($sql,1);
		if(!$rqData) return false;
		foreach ($rqData as $key => $val) {
			if ($val['typecontent']==0) {
				$val['typecontent'] = "article";
			} elseif($val['typecontent']==2) {
				$val['typecontent'] = "banner";
			} elseif($val['typecontent']==4) {
				$val['typecontent'] = "event";
			} else {
				$val['typecontent'] = "";
			}
			$val['id_image'] = $val['id'];
			$val['id'] = $val['contentid'];
			
			$rqData[$key] = $val;
			
			$rqData[$key]['imagepath'] = false;
								
			if(is_file("{$CONFIG['LOCAL_PUBLIC_ASSET']}event/{$val['image']}")) 	$rqData[$key]['imagepath'] = "event";
			if(is_file("{$CONFIG['LOCAL_PUBLIC_ASSET']}banner/{$val['image']}")) 	$rqData[$key]['imagepath'] = "banner";
			if(is_file("{$CONFIG['LOCAL_PUBLIC_ASSET']}article/{$val['image']}"))  	$rqData[$key]['imagepath'] = "article";	
			
			if(is_file("{$CONFIG['LOCAL_PUBLIC_ASSET']}event/small_{$val['image']}"))  	$rqData[$key]['image'] = "small_{$val['image']}";	
			if(is_file("{$CONFIG['LOCAL_PUBLIC_ASSET']}banner/small_{$val['image']}"))  	$rqData[$key]['image'] = "small_{$val['image']}";	
			if(is_file("{$CONFIG['LOCAL_PUBLIC_ASSET']}article/small_{$val['image']}"))  	$rqData[$key]['image'] = "small_{$val['image']}";	
			
			$video_thumbnail = false;
			if($val['url']!='')	{
				//parser url and get param data
				$parseUrl = parse_url($val['url']);
				if(array_key_exists('query',$parseUrl)) parse_str($parseUrl['query'],$parseQuery);
				else $parseQuery = false;
				if($parseQuery) {
					if(array_key_exists('v',$parseQuery))$video_thumbnail = $parseQuery['v'];
				} 
				$rqData[$key]['video_thumbnail'] = $video_thumbnail;
			}else $rqData[$key]['video_thumbnail'] = false;		
			
			if(is_file("{$CONFIG['LOCAL_PUBLIC_ASSET']}music/mp3/{$val['file']}")) $rqData[$key]['hasfile'] = true;
			else $rqData[$key]['hasfile'] = false;			
		}
		//pr($rqData);
		if($rqData) $qData=	$this->getStatistictArticle($rqData);		
		else $qData = false;
		
		if(!$qData) return false;
		$arrGallery['result'] = $qData;
		$arrGallery['total'] = $total['total'];
		return $arrGallery;
	}
	
	function hapusmygallery(){
		$cid = intval($this->apps->_p('cid'));
		$sql = "UPDATE my_images set n_status = 0 WHERE id = {$cid}";
		if ($this->apps->query($sql)) {
			$data = array("status"=>1);
		} else {
			$data = array("status"=>0);
		}
		
		return $data;
	}
	
	function addUploadImage($data,$type=NULL){
		
		$fromwho = intval($this->apps->_p('fromwho'));
		if($fromwho==0) {
			$fromwho = $type==3 ? 2 : 1;
		}
		$title = strip_tags($this->apps->_p('title'));
		$description = strip_tags($this->apps->_p('desc'));
		$tags = strip_tags($this->apps->_p('tags'));
		$brief = strip_tags($this->apps->_p('brief'));
		if($brief=='') $brief = $this->wordcut($description,10);
		$posted_date = strip_tags($this->apps->_p('posted_date'));
		$city_event = intval($this->apps->_p('city_event'));
		$image = $data['arrImage']['filename'];
		if($tags){
			$tags = serialize(explode(',',$tags));
		}
		if ($type==3||$fromwho==2) {
			$authorid = intval($this->apps->user->pageid);
		} else {
			if(!$this->uid) return false;
			$authorid = intval($this->uid);
			if(!$authorid) return false;		
		}
		
		if($posted_date=='') $posted_date = date('Y-m-d H:i:s');		
		
		if(intval($data['result'])==1){
			$sql ="
				INSERT INTO {$this->dbshema}_news_content (cityid,brief,title,content,tags,image,articleType,created_date,posted_date,authorid,fromwho,n_status) 
				VALUES ('{$city_event}','{$brief}','{$title}','{$description}','{$tags}','{$image}',{$type},NOW(),'{$posted_date}','{$authorid}','{$fromwho}',{$this->moderation})
				";
				$this->logger->log($sql);
				if ($this->apps->query($sql)) return true;
				else return false;
				
				/* $result = false;
				if($this->apps->getLastInsertId()>0) {
					$result['cotentid'] = $this->apps->getLastInsertId();
					$contentid = $result['cotentid'];
					if ($type==3 || $type==15) {
						$sql_image = "INSERT INTO my_images (userid,type,contentid,date,n_status) values ('{$uid}',1,'{$contentid}',NOW(),1) ";
						$this->apps->query($sql_image);
					}
					return $result;
				} */
		} else return false;
	}
	
	function addUploadMusic($data,$type=NULL){
		$fromwho = 2;
		$title = strip_tags($this->apps->_p('title'));
		$description = strip_tags($this->apps->_p('desc'))=='Description' ? "" : strip_tags($this->apps->_p('desc'));
		$tags = strip_tags($this->apps->_p('tags'));
		$can_save = intval($this->apps->_p('can_save'));
		$music = $data['arrMusic']['filename'];
		
		if($tags){
			$tags = serialize(explode(',',$tags));
		}
		
		$pageid = intval($this->apps->user->pageid);		
		if(!$pageid) return false;
		if(intval($data['result'])==1){
			$sql ="
				INSERT INTO {$this->dbshema}_news_content (title,content,tags,file,articleType,created_date,posted_date,authorid,fromwho,can_save,n_status) 
				VALUES ('{$title}','{$description}','{$tags}','{$music}',{$type},NOW(),NOW(),'{$pageid}','{$fromwho}','{$can_save}',{$this->moderation})
				";
			$this->apps->query($sql);
			//$this->logger->log($sql);
			if($this->apps->getLastInsertId()>0) return true;			
		} else return false;
	}
	
	function addUploadVideo($type=NULL){
		$fromwho = $type==3 ? 2 : 1;
		$title = strip_tags($this->apps->_p('title'));
		$description = strip_tags($this->apps->_p('desc'));
		$tags = strip_tags($this->apps->_p('tags'));
		$url = strip_tags($this->apps->_p('url'));
		
		if($tags){
			$tags = serialize(explode(',',$tags));
		}
		if ($type==3) {
			$authorid = intval($this->apps->user->pageid);
		} else {
			if(!$this->uid) return false;
			$authorid = intval($this->uid);
			if(!$authorid) return false;		
		}
		$sql ="
			INSERT INTO {$this->dbshema}_news_content (title,content,tags,url,articleType,created_date,posted_date,authorid,fromwho,n_status) 
			VALUES ('{$title}','{$description}','{$tags}',\"{$url}\",{$type},NOW(),NOW(),'{$authorid}','{$fromwho}',{$this->moderation})
			";
		;
		//$this->logger->log($sql);
		if($this->apps->query($sql)) return true;
		else return false;
	}
	
	function getPagesCategory($pageid='photography',$checkpage=true){
		if($checkpage){
			$page = intval($pageid);
			$sql ="SELECT * FROM {$this->dbshema}_news_content_page WHERE pagename='{$page}' LIMIT 1" ;
			$pageData = $this->apps->fetch($sql); 
			if(!$pageData) return false;
			$pageid = intval($pageData['id']);
		}else $pageid = intval($pageid);
		
		$sql ="SELECT * FROM {$this->dbshema}_news_content_category WHERE pageid={$pageid} " ;
		$qData = $this->apps->fetch($sql,1);
		
		return $qData ; 
	}
	
	function populartags($contenttype=0,$limit=5){
			
			$typeid = strip_tags($this->checkPage($contenttype));
			
			$limit = intval($limit);
			
			$sql ="	SELECT COUNT(*) total,content.id,content.tags
					FROM {$this->dbshema}_news_content content 
					LEFT JOIN tbl_activity_log log ON log.action_value = content.id
					WHERE log.action_id=2  AND content.n_status=1 AND content.articleType IN ({$typeid})
					GROUP BY content.id
					ORDER BY total DESC LIMIT {$limit}
					";
			// pr($sql);
			$qData = $this->apps->fetch($sql,1);
			
			if(!$qData) return false;
			$nametags = false;
			foreach($qData as $key => $val){
				if($val['tags']) $nametags[$val['id']] = unserialize($val['tags']);
				
			}
			$qData = null;
			if(!$nametags) return false;
			foreach($nametags as $key => $val){
				foreach($val as $tags){	
					$arrtags[$key] = $tags;	
				}
			}
			
			if($arrtags)	return $arrtags;
			return false;
			
	}
	
	function weeklyPopular ($contenttype=0,$limit=9){
			global $CONFIG;
			$typeid = strip_tags($this->checkPage($contenttype));
			
			$limit = intval($limit);
			//get between this week days
				//get monday of this week
					$mondaydate = date("Y-m-d",strtotime('last monday', strtotime('next sunday')));
				
			$sql ="	
					SELECT COUNT(*) total,content.*
					FROM {$this->dbshema}_news_content content 
					LEFT JOIN tbl_activity_log log ON log.action_value = content.id
					WHERE log.date_time BETWEEN '{$mondaydate}' AND DATE_ADD(NOW(),INTERVAL 1 DAY) AND content.n_status=1 AND articleType IN ({$typeid})
					GROUP BY content.id
					ORDER BY total DESC LIMIT {$limit}
					";
	
			$qData = $this->apps->fetch($sql,1);

			if(!$qData) return false;
			foreach($qData as $key => $val){
			$qData[$key]['imagepath'] = false;
								
			if(is_file("{$CONFIG['LOCAL_PUBLIC_ASSET']}event/{$val['image']}")) 	$qData[$key]['imagepath'] = "event";
			if(is_file("{$CONFIG['LOCAL_PUBLIC_ASSET']}banner/{$val['image']}")) 	$qData[$key]['imagepath'] = "banner";
			if(is_file("{$CONFIG['LOCAL_PUBLIC_ASSET']}article/{$val['image']}"))  	$qData[$key]['imagepath'] = "article";	
			
			if(is_file("{$CONFIG['LOCAL_PUBLIC_ASSET']}event/small_{$val['image']}"))  	$qData[$key]['image'] = "small_{$val['image']}";	
			if(is_file("{$CONFIG['LOCAL_PUBLIC_ASSET']}banner/small_{$val['image']}"))  	$qData[$key]['image'] = "small_{$val['image']}";	
			if(is_file("{$CONFIG['LOCAL_PUBLIC_ASSET']}article/small_{$val['image']}"))  	$qData[$key]['image'] = "small_{$val['image']}";	
			
			if(is_file("{$CONFIG['LOCAL_PUBLIC_ASSET']}event/thumbnail_{$val['thumbnail_image']}")) $qData[$key]['image'] = "thumbnail_{$val['image']}";	
			if(is_file("{$CONFIG['LOCAL_PUBLIC_ASSET']}banner/thumbnail_{$val['thumbnail_image']}")) $qData[$key]['image'] = "thumbnail_{$val['image']}";	
			if(is_file("{$CONFIG['LOCAL_PUBLIC_ASSET']}article/thumbnail_{$val['thumbnail_image']}")) $qData[$key]['image'] = "thumbnail_{$val['image']}";	
			
			if(is_file("{$CONFIG['LOCAL_PUBLIC_ASSET']}event/square{$val['image']}")) $qData[$key]['image'] = "square{$val['image']}";
			if(is_file("{$CONFIG['LOCAL_PUBLIC_ASSET']}banner/square{$val['image']}")) $qData[$key]['image'] = "square{$val['image']}";
			if(is_file("{$CONFIG['LOCAL_PUBLIC_ASSET']}article/square{$val['image']}")) $qData[$key]['image'] = "square{$val['image']}";
			
			
			//parseurl for video thumb
			$video_thumbnail = false;
			if($val['url']!='')	{
				//parser url and get param data
				$parseUrl = parse_url($val['url']);
				if(array_key_exists('query',$parseUrl)) parse_str($parseUrl['query'],$parseQuery);
				else $parseQuery = false;
				if($parseQuery) {
					if(array_key_exists('v',$parseQuery))$video_thumbnail = $parseQuery['v'];
				} 
				$qData[$key]['video_thumbnail'] = $video_thumbnail;
			}else $qData[$key]['video_thumbnail'] = false;		
			
			if(is_file("{$CONFIG['LOCAL_PUBLIC_ASSET']}music/mp3/{$val['file']}")) $qData[$key]['hasfile'] = true;
			else $qData[$key]['hasfile'] = false;			
		}
		if($qData) $qData=	$this->getStatistictArticle($qData);
		else $qData = false;
		return $qData;
	}
	
	function getnewestupload(){
		global $CONFIG;
		$sql = "
			SELECT anc.id,anc.title,anc.brief,image,thumbnail_image,slider_image,posted_date,file,url,fromwho,tags,authorid,topcontent,cityid , anct.type,anct.content,anc.articleType,anct.type pagesname
			FROM {$this->dbshema}_news_content  anc
			LEFT JOIN {$this->dbshema}_news_content_type anct ON anc.articleType = anct.id
			WHERE n_status = 1   AND anc.articleType <> 0
			ORDER BY created_date DESC , id DESC
			LIMIT 4";
			$qData = $this->apps->fetch($sql,1);
		if(!$qData) return false;
		//cek detail image from folder
			//if is article, image banner do not shown
		foreach($qData as $key => $val){
			$qData[$key]['imagepath'] = false;
								
			if(is_file("{$CONFIG['LOCAL_PUBLIC_ASSET']}event/{$val['image']}")) 	$qData[$key]['imagepath'] = "event";
			if(is_file("{$CONFIG['LOCAL_PUBLIC_ASSET']}banner/{$val['image']}")) 	$qData[$key]['imagepath'] = "banner";
			if(is_file("{$CONFIG['LOCAL_PUBLIC_ASSET']}article/{$val['image']}"))  	$qData[$key]['imagepath'] = "article";	
			
			if(is_file("{$CONFIG['LOCAL_PUBLIC_ASSET']}event/small_{$val['image']}"))  	$qData[$key]['image'] = "small_{$val['image']}";	
			if(is_file("{$CONFIG['LOCAL_PUBLIC_ASSET']}banner/small_{$val['image']}"))  	$qData[$key]['image'] = "small_{$val['image']}";	
			if(is_file("{$CONFIG['LOCAL_PUBLIC_ASSET']}article/small_{$val['image']}"))  	$qData[$key]['image'] = "small_{$val['image']}";	
			
			if(is_file("{$CONFIG['LOCAL_PUBLIC_ASSET']}event/thumbnail_{$val['thumbnail_image']}")) $qData[$key]['image'] = "thumbnail_{$val['image']}";	
			if(is_file("{$CONFIG['LOCAL_PUBLIC_ASSET']}banner/thumbnail_{$val['thumbnail_image']}")) $qData[$key]['image'] = "thumbnail_{$val['image']}";	
			if(is_file("{$CONFIG['LOCAL_PUBLIC_ASSET']}article/thumbnail_{$val['thumbnail_image']}")) $qData[$key]['image'] = "thumbnail_{$val['image']}";	
			
			if(is_file("{$CONFIG['LOCAL_PUBLIC_ASSET']}event/square{$val['image']}")) $qData[$key]['image'] = "square{$val['image']}";
			if(is_file("{$CONFIG['LOCAL_PUBLIC_ASSET']}banner/square{$val['image']}")) $qData[$key]['image'] = "square{$val['image']}";
			if(is_file("{$CONFIG['LOCAL_PUBLIC_ASSET']}article/square{$val['image']}")) $qData[$key]['image'] = "square{$val['image']}";
			
			
			//parseurl for video thumb
			$video_thumbnail = false;
			if($val['url']!='')	{
				//parser url and get param data
				$parseUrl = parse_url($val['url']);
				if(array_key_exists('query',$parseUrl)) parse_str($parseUrl['query'],$parseQuery);
				else $parseQuery = false;
				if($parseQuery) {
					if(array_key_exists('v',$parseQuery))$video_thumbnail = $parseQuery['v'];
				} 
				$qData[$key]['video_thumbnail'] = $video_thumbnail;
			}else $qData[$key]['video_thumbnail'] = false;		
			
			if(is_file("{$CONFIG['LOCAL_PUBLIC_ASSET']}music/mp3/{$val['file']}")) $qData[$key]['hasfile'] = true;
			else $qData[$key]['hasfile'] = false;			
		}
		if($qData) $qData=	$this->getStatistictArticle($qData);
		else $qData = false;
		//pr($qData);
		return $qData;
	}
	
	function setCover(){
		global $CONFIG;
		$cid = intval($this->apps->_request('cid'));
		$fromwhere = intval($this->apps->_request('fromwhere'));
		$typeofpage = intval($this->apps->_request('typeofpage')); //used by who
	
		//type of page , define user pages
		if($typeofpage!=0){
			$myid = $this->pageid; // wait for session
		}else $myid = $this->uid;
		$image = false;
		//if from content, get image content
		$sql ="SELECT id,image FROM {$this->dbshema}_news_content WHERE  id={$cid} AND fromwho = {$fromwhere} LIMIT 1;";
		$data = $this->apps->fetch($sql);
	
		if(!$data) return false;
		if($typeofpage==0) $coverfolder = "user/cover/";
		else $coverfolder = "pages/cover/";
		$folder = $CONFIG['LOCAL_PUBLIC_ASSET'];
		$image = $data['image'];	
		copy($folder."article/".$image,$folder.$coverfolder.$image);
		
		//userid 	image 	otherid 	fromwhere 0:news_content;1:my	type 0:user;1-n mypagestype	n_status 
		$sql =" 
		INSERT INTO my_wallpaper ( myid,	image ,	otherid ,	fromwhere ,type, n_status ,datetime )
		VALUES ({$myid},'{$image}',{$cid},{$fromwhere},{$typeofpage},1,NOW())
		ON DUPLICATE KEY UPDATE datetime=NOW()
		";
		//pr($sql);
		$this->apps->query($sql);
		
		if($this->apps->getLastInsertId()>0) {
			$sql =" 
			INSERT INTO my_images ( userid,	contentid ,	date ,	n_status )
			VALUES ({$myid},{$cid},NOW(),1)
			ON DUPLICATE KEY UPDATE date=NOW()
			";
			
			$this->apps->query($sql);
			return true;
		} else return false;
		
	}
	
	function setPlaylist(){
		$cid = intval($this->apps->_request('cid'));
		$fromwhere = intval($this->apps->_request('fromwhere'));
		$typeofpage = intval($this->apps->_request('typeofpage'));
		$authorid = intval($this->apps->_request('authorid'));
		
		//check user have myid relation		
		$sql ="SELECT id, ownerid FROM my_pages WHERE ownerid={$this->uid} AND n_status<> 3 LIMIT 1";
		$data = $this->apps->fetch($sql);
		if($data) {
			if($data['id']==$authorid) {
				return false;
			} else {
				$myid = $this->uid;
			}
		} else {
			$myid = $this->uid;
		}
		
		//get file content
		$file = false;
		$sql ="SELECT id,file FROM {$this->dbshema}_news_content WHERE  id={$cid} AND fromwho = {$fromwhere} LIMIT 1;";
		$data = $this->apps->fetch($sql);
	
		if(!$data) return false;
		$file = $data['file'];
		
		//get type of page
		$type = false;
		$sql ="SELECT id,type FROM my_pages WHERE  id={$authorid} LIMIT 1;";
		$arrtype = $this->apps->fetch($sql);
		if (!$arrtype) return false;
		$type = $arrtype['type'];
		
		//userid  image  otherid  fromwhere 0:news_content;1:my	type 0:user;1-n mypagestype	n_status 
		$sql =" 
			INSERT INTO my_playlist (myid,file,otherid,fromwhere,type,n_status,datetime) VALUES ({$myid},'{$file}',{$cid},{$fromwhere},{$type},1,NOW())
			ON DUPLICATE KEY UPDATE datetime=NOW()
		";
		
		$this->apps->query($sql);
		
		if($this->apps->getLastInsertId()>0) {
			/*
			$sql =" 
			INSERT INTO my_images ( userid,	contentid ,	date ,	n_status )
			VALUES ({$myid},{$cid},NOW(),1)
			ON DUPLICATE KEY UPDATE date=NOW()
			";
			
			$this->apps->query($sql);
			*/
			return true;
		} else return false;
	}
		
	function getMyFavorite($userid=0,$limit=10){
			global $CONFIG;
			
			if($userid==0) return false;
			$start = intval($this->apps->_request('start'));
			$sql ="
					SELECT contentid FROM {$this->dbshema}_news_content_favorite WHERE n_status=  1 AND userid = {$userid} ORDER BY date DESC  LIMIT {$start},{$limit}
					";

				$qData = $this->apps->fetch($sql,1);
				if($qData) {
					$this->logger->log("have favorite");
					foreach($qData as $val){
						$favoriteData[$val['contentid']]=$val['contentid'];
					}
				
					if(!$favoriteData) return false;
					$strcontentid = implode(',',$favoriteData);
					
					//get content
					$sql = "
					SELECT anc.id,anc.title,anc.brief,anc.image,anc.thumbnail_image,anc.slider_image,anc.posted_date,anc.file,anc.url,anc.fromwho,anc.tags,anc.authorid,anc.topcontent,anc.cityid,anct.type pagesname FROM athreesix_news_content anc
					LEFT JOIN {$this->dbshema}_news_content_type anct ON anct.id = anc.articleType					
					WHERE anc.id IN ({$strcontentid}) AND anc.n_status=1 LIMIT {$limit}";
			
					$qData = $this->apps->fetch($sql,1);
					if($qData){
						foreach($qData as $val){
							$arrContent[] = $val;
						}
					}else $arrContent = false;
					
					if($arrContent){
						
						foreach($arrContent as $key => $val){
							$arrContent[$key]['imagepath'] = false;
								
							if(is_file("{$CONFIG['LOCAL_PUBLIC_ASSET']}event/{$val['image']}")) 	$arrContent[$key]['imagepath'] = "event";
							if(is_file("{$CONFIG['LOCAL_PUBLIC_ASSET']}banner/{$val['image']}")) 	$arrContent[$key]['imagepath'] = "banner";
							if(is_file("{$CONFIG['LOCAL_PUBLIC_ASSET']}article/{$val['image']}"))  	$arrContent[$key]['imagepath'] = "article";	
							
							if(is_file("{$CONFIG['LOCAL_PUBLIC_ASSET']}event/small_{$val['image']}"))  	$arrContent[$key]['image'] = "small_{$val['image']}";	
							if(is_file("{$CONFIG['LOCAL_PUBLIC_ASSET']}banner/small_{$val['image']}"))  	$arrContent[$key]['image'] = "small_{$val['image']}";	
							if(is_file("{$CONFIG['LOCAL_PUBLIC_ASSET']}article/small_{$val['image']}"))  	$arrContent[$key]['image'] = "small_{$val['image']}";	
							
							$video_thumbnail = false;
							if($val['url']!='')	{
								//parser url and get param data
								$parseUrl = parse_url($val['url']);
								if(array_key_exists('query',$parseUrl)) parse_str($parseUrl['query'],$parseQuery);
								else $parseQuery = false;
								if($parseQuery) {
									if(array_key_exists('v',$parseQuery))$video_thumbnail = $parseQuery['v'];
								} 
								$arrContent[$key]['video_thumbnail'] = $video_thumbnail;
							}else $arrContent[$key]['video_thumbnail'] = false;		
							
							if(is_file("{$CONFIG['LOCAL_PUBLIC_ASSET']}music/mp3/{$val['file']}")) $arrContent[$key]['hasfile'] = true;
							else $arrContent[$key]['hasfile'] = false;		
						}
						
						$arrContent = $this->apps->contentHelper->getStatistictArticle($arrContent);
						return $arrContent;
					}else return false;
				}
		return false;
			
			
			
			
	}
	
	function getContestSubmission($userid=0,$mypagestype=0,$limit=10){
			
			global $CONFIG;
			if($userid==0) return false;
			$start = intval($this->apps->_request('start'));
			$sql ="
					SELECT contestid FROM my_contest WHERE n_status=  1 AND otherid = {$userid}  AND mypagestype={$mypagestype} LIMIT {$start},{$limit}
					";
		// pr($sql);
				$qData = $this->apps->fetch($sql,1);
				if($qData) {
					$this->logger->log("have contest");
					foreach($qData as $val){
						$contestData[$val['contestid']]=$val['contestid'];
					}

					if(!$contestData) return false;
					$strcontentid = implode(',',$contestData);
					
					//get content
					$sql = "
					SELECT anc.id,anc.title,anc.brief,anc.image,anc.thumbnail_image,anc.slider_image,anc.posted_date,anc.file,anc.url,anc.fromwho,anc.tags,anc.authorid,anc.topcontent,anc.cityid,anct.type pagesname FROM athreesix_news_content anc
					LEFT JOIN {$this->dbshema}_news_content_type anct ON anct.id = anc.articleType					
					WHERE anc.id IN ({$strcontentid}) AND anc.n_status=1 LIMIT {$limit}";
			
					$qData = $this->apps->fetch($sql,1);
					if($qData){
						foreach($qData as $key => $val){
							$qData[$key]['imagepath'] = false;
								
							if(is_file("{$CONFIG['LOCAL_PUBLIC_ASSET']}event/{$val['image']}")) 	$qData[$key]['imagepath'] = "event";
							if(is_file("{$CONFIG['LOCAL_PUBLIC_ASSET']}banner/{$val['image']}")) 	$qData[$key]['imagepath'] = "banner";
							if(is_file("{$CONFIG['LOCAL_PUBLIC_ASSET']}article/{$val['image']}"))  	$qData[$key]['imagepath'] = "article";	
							
							if(is_file("{$CONFIG['LOCAL_PUBLIC_ASSET']}event/small_{$val['image']}"))  	$qData[$key]['image'] = "small_{$val['image']}";	
							if(is_file("{$CONFIG['LOCAL_PUBLIC_ASSET']}banner/small_{$val['image']}"))  	$qData[$key]['image'] = "small_{$val['image']}";	
							if(is_file("{$CONFIG['LOCAL_PUBLIC_ASSET']}article/small_{$val['image']}"))  	$qData[$key]['image'] = "small_{$val['image']}";	
							
							$video_thumbnail = false;
							if($val['url']!='')	{
								//parser url and get param data
								$parseUrl = parse_url($val['url']);
								if(array_key_exists('query',$parseUrl)) parse_str($parseUrl['query'],$parseQuery);
								else $parseQuery = false;
								if($parseQuery) {
									if(array_key_exists('v',$parseQuery))$video_thumbnail = $parseQuery['v'];
								} 
								$qData[$key]['video_thumbnail'] = $video_thumbnail;
							}else $qData[$key]['video_thumbnail'] = false;	
							
							if(is_file("{$CONFIG['LOCAL_PUBLIC_ASSET']}music/mp3/{$val['file']}")) $qData[$key]['hasfile'] = true;
							else $qData[$key]['hasfile'] = false;		
							
							$arrContent[] = $qData[$key];
							
						}
					}else $arrContent = false;
					
					if($arrContent){
						$arrContent = $this->apps->contentHelper->getStatistictArticle($arrContent);
						return $arrContent;
					}else return false;
				}
		return false;			
	}
	
	function getMyCalendar($userid=0,$mypagestype=0,$limit=10){
			
			global $CONFIG;
			if($userid==0) return false;
			$contestData = false;
			$start = intval($this->apps->_request('start'));
			if($mypagestype>=2) $qFromWho = " AND anc.fromwho = 2 ";
			else $qFromWho = "";
					$sql ="
					SELECT contestid FROM my_contest WHERE n_status=  1 AND otherid = {$userid}  AND mypagestype={$mypagestype} LIMIT {$start},{$limit}
					";
			
				$qData = $this->apps->fetch($sql,1);
				// pr($sql);
					if($qData) {
						$this->logger->log("have contest");
						foreach($qData as $val){
							$contestData[$val['contestid']]=$val['contestid'];
						}
					}
					
					if($contestData){
						$strcontentid = implode(',',$contestData);
						$qContentId = " anc.id IN ({$strcontentid}) OR  ";
					}else $qContentId = "";
					//get content
					$sql = "
					SELECT anc.id,anc.title,anc.brief,anc.image,anc.thumbnail_image,anc.slider_image,anc.posted_date,anc.file,anc.url,anc.fromwho,anc.tags,anc.authorid,anc.topcontent,anc.cityid,anct.type pagesname FROM athreesix_news_content anc
					LEFT JOIN {$this->dbshema}_news_content_type anct ON anct.id = anc.articleType					
					WHERE {$qContentId} authorid={$userid}  AND anc.n_status=1 {$qFromWho} ORDER BY anc.posted_date DESC LIMIT {$limit}";
						// pr($sql);
					$qData = $this->apps->fetch($sql,1);
					
					if($qData){
						foreach($qData as $key => $val){
							$qData[$key]['imagepath'] = false;
								
							if(is_file("{$CONFIG['LOCAL_PUBLIC_ASSET']}event/{$val['image']}")) 	$qData[$key]['imagepath'] = "event";
							if(is_file("{$CONFIG['LOCAL_PUBLIC_ASSET']}banner/{$val['image']}")) 	$qData[$key]['imagepath'] = "banner";
							if(is_file("{$CONFIG['LOCAL_PUBLIC_ASSET']}article/{$val['image']}"))  	$qData[$key]['imagepath'] = "article";	
							
							if(is_file("{$CONFIG['LOCAL_PUBLIC_ASSET']}event/small_{$val['image']}"))  	$qData[$key]['image'] = "small_{$val['image']}";	
							if(is_file("{$CONFIG['LOCAL_PUBLIC_ASSET']}banner/small_{$val['image']}"))  	$qData[$key]['image'] = "small_{$val['image']}";	
							if(is_file("{$CONFIG['LOCAL_PUBLIC_ASSET']}article/small_{$val['image']}"))  	$qData[$key]['image'] = "small_{$val['image']}";	
							
							$video_thumbnail = false;
							if($val['url']!='')	{
								//parser url and get param data
								$parseUrl = parse_url($val['url']);
								if(array_key_exists('query',$parseUrl)) parse_str($parseUrl['query'],$parseQuery);
								else $parseQuery = false;
								if($parseQuery) {
									if(array_key_exists('v',$parseQuery))$video_thumbnail = $parseQuery['v'];
								} 
								$qData[$key]['video_thumbnail'] = $video_thumbnail;
							}else $qData[$key]['video_thumbnail'] = false;	
							
							if(is_file("{$CONFIG['LOCAL_PUBLIC_ASSET']}music/mp3/{$val['file']}")) $qData[$key]['hasfile'] = true;
							else $qData[$key]['hasfile'] = false;		
							
							$arrContent[] = $qData[$key];
							
						}
					}else $arrContent = false;
					
					if($arrContent){
						$arrContent = $this->apps->contentHelper->getStatistictArticle($arrContent);
						return $arrContent;
					}else return false;
			
		return false;			
	}
	
	function setEditContent(){
		global $CONFIG;
		$cid = intval($this->apps->_p('article_id'));
		$title = strip_tags($this->apps->_p('title'));
		$content = strip_tags($this->apps->_p('description'));
		$tags = strip_tags($this->apps->_p('tags'));
		if($tags){
			$tags = serialize(explode(',',$tags));
		}
		
		$sql = "UPDATE {$this->dbshema}_news_content SET title = \"{$title}\",content = \"{$content}\",tags = '{$tags}' WHERE id = '{$cid}' ";
		if ($this->apps->query($sql)) {
			return true;
		} else return false;
		return false;
	}
	
	function wordcut($str=null,$num=1){
			if($str==null) return false;
			
			$arrStr = explode(" ",$str);
			$arrNewStr = false;
			foreach($arrStr as $key => $val){
				if($key<=$num){
					$arrNewStr[] = $val;
				}else break;
			}
			if($arrNewStr==false) return false;
			$str = implode(" ",$arrNewStr);
			return $str;
	
	}
	
	function getProvince() {
		$sql = "SELECT * FROM marlborohunt_province_reference WHERE id > 0 ORDER BY province ASC";
		$result = $this->apps->fetch($sql, 1);
		
		if ($result) return $result;
		
		return FALSE;
	}
	
	function getCity($data) {
		if (array_key_exists('idProvince', $data)){
			$sql = "SELECT id, city FROM {$this->dbshema}_city_reference WHERE provinceid = {$data['idProvince']} GROUP BY city ASC";
			$result = $this->apps->fetch($sql, 1);
		}else if (array_key_exists('idCity', $data)){
			$sql = "SELECT * FROM {$this->dbshema}_city_reference WHERE id = {$data['idCity']} LIMIT 1";
			$result = $this->apps->fetch($sql);
		}
		if ($result) return $result;
		return FALSE;
	}
	
	function getMobilePrefix() {
		$sql = "SELECT * FROM mobile_prefix";
		$res = $this->apps->fetch($sql,1);
		if ($res) return $res;
		return FALSE;
	}
	
	function submitTaskCode() {
		
		$user = $this->apps->user;
		// cek code ke tabel publicity apakah valid atau tidak
		$taskCode = strip_tags($this->apps->_p('code'));
		
		// $taskID = $_SESSION['idTaskOnSubmit'];
		$taskID = intval($this->apps->_p('idtask'));
		
		$sql = "SELECT code.*, master.codename FROM tbl_code_publicity AS code LEFT JOIN tbl_code_detail AS master 
				ON code.codeid = master.id WHERE code.maskcode = '{$taskCode}' AND code.grouptype = 0 AND code.n_status = 1 LIMIT 1";
		// pr($sql);
		$res = $this->apps->fetch($sql);
		// pr($res);exit;
		if ($res){
			// cek apakah user sudah menguunakan code ini sebelumnya, biar gk input code ini trus
			$sql = "SELECT COUNT(id) AS jumlah FROM tbl_code_inventory WHERE userid = {$user->id} 
					AND codepublicityid = {$res['id']} AND n_status = 1  ";
				// pr($sql);
			$hasil = $this->apps->fetch($sql);
			
			if ($hasil&&$hasil['jumlah'] > 0){
				return false;
			}else{
				/* randcode , add proba in here if want to use of fontend */
				$getMasterCodeForRandom = $this->getMasterCodeForRandom();
				$randcodeidmekans = $this->randomcodegen($getMasterCodeForRandom);
				if($randcodeidmekans!=false) $res['codeid']=$randcodeidmekans;
				
				// insert code ke tabel inventory
				$date = date('Y-m-d H:i:s');
				$sql = "INSERT INTO tbl_code_inventory (userid, codeid, codepublicityid, n_status, datetimes, histories) 
						VALUES ({$user->id}, '{$res['codeid']}', {$res['id']}, 0, '{$date}', 'get code from input code task')";
				$result = $this->apps->query($sql);
					// pr($sql);
				if($this->apps->getLastInsertId()>0){
					// cek ke database apakah sudah ada id atau belum
					$get = "SELECT id FROM my_task WHERE userid = {$user->id} AND taskid = {$taskID} LIMIT 1";
					$getRes = $this->apps->fetch($get);
					
					if (!$getRes){
						$sql = "INSERT INTO my_task (userid, taskid, taskdate, n_status)
								VALUES ({$user->id}, {$taskID}, '{$date}', 1)";
						$hasil = $this->apps->query($sql);
						
						if ($hasil){
							
							$this->setPursuitUpdate(array('act'=>0, 'update'=>'get code'));
							
							if (isset($_SESSION['idTaskOnSubmit'])){
								unset($_SESSION['idTaskOnSubmit']);
							}
							$this->apps->log('accompalished', $this->apps->user->name.' Accomplished task');
							sleep(1);
							$this->apps->log('getletter', $this->apps->user->name.' unlocked the letter "'. $res['codename'].'"');
							
							return $res['codename'];
						}
						// echo '3';
						return false;
					}
				}
				// echo '2';
				return false;
			}
			
		}
		// echo '1';
		return false;
	}
	
	function randomcodegen($getMasterCode=false){
			global $CONFIG;
			if($getMasterCode==false) return false;
			$this->logger->log(json_encode($getMasterCode));
			$randcode = false;
		 
				foreach ($getMasterCode as $key => $value){
					if($CONFIG['usingelusive']){
							$popprob = ($value['prob'] * ($value['prob'] * (rand(1,12))));
							$randcode[$value['id']] = $popprob;
					}else{
						if($value['codetype']==0){
							$popprob = ($value['prob'] * ($value['prob'] * (rand(1,12))));
							$randcode[$value['id']] = $popprob;
						}
					}
				}
				if($randcode){
					$this->logger->log(json_encode($randcode));
					$maxCode = max($randcode);
					$this->logger->log($maxCode);
					$codeid = array_search($maxCode, $randcode);
					$this->logger->log($codeid);
					return $codeid;
				}
				return false;
	}
	
	function inputCodeFromMenu() {
		$code = strip_tags($this->apps->_p('code'));
		
		$getMasterCode = $this->getMasterCode();
		$getHiddenCode = $this->getHiddenCode();
		
		
		
		if ($getMasterCode){
		
			foreach ($getMasterCode as $value){
				$data[$value['id']] = $value['codename'];
			}
		}
		
		if ($getHiddenCode){
		
			foreach ($getHiddenCode as $value){
				$hiden[] = $value['maskcode'];
			}
		}
		
		$user = $this->apps->user;
		// cek ketersedian maskcode ke tabel publicity
		// $sql = "SELECT id, codeid FROM tbl_code_publicity WHERE maskcode = '{$code}' AND channel = 'key account' LIMIT 1";
		$sql = "SELECT id, codeid FROM tbl_code_publicity WHERE maskcode = '{$code}' and n_status = '1' LIMIT 1";
		// pr($sql);
		$res = $this->apps->fetch($sql);
		$this->logger->log($sql);
		
		// fungsi random perolehan letter kecuali dari hidden pack
		if ($res){
			if (!in_array($code, $hiden)){
				/* randcode , add proba in here if want to use of fontend */
				$getMasterCodeForRandom = $this->getMasterCodeForRandom();
				$randcodeidmekans = $this->randomcodegen($getMasterCodeForRandom);
				if($randcodeidmekans!=false) $res['codeid']=$randcodeidmekans;
			}
			// cek apakah user sudah memiliki kode ini sebelumnya
			$sql = "SELECT id FROM  tbl_code_inventory WHERE userid = {$user->id} AND codepublicityid = {$res['id']} LIMIT 1";
			$result = $this->apps->fetch($sql);
			$this->logger->log($sql);
			if ($result){
				// echo '1';
				return false;
			}else{
				$dateTime = date('Y-m-d H:i:s');
				if (in_array($code, $hiden)){
					return false;
					/* this code not used any more*/
					/*
						$messg = 'hidden code';
						$sqlgames = "INSERT INTO my_games (gamesid, userid, point, datetimes, n_status)
							VALUES (3, {$this->apps->user->id}, 100, '{$dateTime}', 1)";
						$resgames = $this->apps->query($sqlgames);
						$this->logger->log($sqlgames);
					*/
					// pr($sqlgames);		
				}else{
					$messg = 'key account';
				}
				$sql = "INSERT INTO tbl_code_inventory (userid, codeid, codepublicityid, n_status, histories,datetimes)
						VALUES ({$user->id}, {$res['codeid']}, {$res['id']}, 0, '{$messg}','{$dateTime}')";
				// pr($sql);
				$res = $this->apps->query($sql);
				$this->logger->log($sql);
				// $sqlTaskLog = "INSERT INTO my_task (userid, taskid, taskdate, n_status)
						// VALUES ({$user->id}, 22, '{$dateTime}', 1)";
				// pr($sql);
				// $resTaskLog = $this->apps->query($sqlTaskLog);
				
				
				if ($res){
				
					if($this->apps->getLastInsertId()>0);
					$id = $this->apps->getLastInsertId();
					
					$sql = "SELECT * FROM tbl_code_inventory WHERE userid = {$user->id} AND id = {$id} LIMIT 1";
					$res = $this->apps->fetch($sql);
					if ($res){
						
						// $res['name'] = $user->name;
						$res['codename'] = $data[$res['codeid']];
						if (in_array($code, $hiden)){
							$this->apps->log('hiddencode', $this->apps->user->name.' found the hidden pack');
						}else{
							$this->apps->log('getletter', $this->apps->user->name.' unlocked the letter "'. $res['codename'].'"');
						}
						
						$typeDouble = array(9,11,12);
						if (in_array($res['codeid'], $typeDouble)){
							$codeName = $res['codename'].'1';
						}else{
							$codeName = $res['codename'];
						}
						return $codeName;
					}
					// echo '4';
					return false;
				}
				// echo '3';
				return false;
			}
		}
		// echo '2';
		return false;
	}
	
	function getHiddenCode() {
		// WZNABVE98H
		$datetimes = date("Y-m-d H:i:s");
		$sql = " SELECT id, codeid, maskcode FROM  tbl_code_publicity WHERE channel='games hidden code' AND n_status = 1" ;
		// pr($sql);
		$qData = $this->apps->fetch($sql,1);
		if ($qData){
			return $qData;
		}
		return false;
	}
	
	function setPursuitUpdate($data) {
		$user = $this->apps->user;
		$date = date('Y-m-d H:i:s');
		if ($data){
			
			($data['update'] !=="") ? $data['update'] = $data['update'] : $data['update'] = "";
			
			if (isset($data['otherid'])){
				$sql = "INSERT INTO tbl_pursuit_task_log (activity, userid, otherid, history, datetime, n_status)
						VALUES ({$data['act']}, {$user->id}, {$data['otherid']}, '{$data['update']}', '{$date}',1)";
			}else{
				$sql = "INSERT INTO tbl_pursuit_task_log (activity, userid, history, datetime, n_status)
						VALUES ({$data['act']}, {$user->id}, '{$data['update']}', '{$date}', 1)";
			}
			
			// pr($sql);
			$res = $this->apps->query($sql);
			if ($res){
				return true;
			}
			
			return false;
		}
	}
	
	function getMasterCode() {
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
	
	function getMasterCodeForRandom() {
		$sql = "SELECT * FROM tbl_code_detail WHERE n_status = 1 AND codetype=0 ";
		$result = $this->apps->fetch($sql,1);
		if ($result){
			return $result;
		}else{
			return false;
		}
	}
	
	function listTradeCode($start=0,$limit=6,$n_status="1") {
		$start = intval($this->apps->_p('start'));
		
		$getMasterCode = $this->getMasterCode();
		if ($getMasterCode){
		
			foreach ($getMasterCode as $value){
				$data[$value['id']] = $value['codename'];
				
			}
		}
		
		$sqlTotal = "SELECT COUNT(trade.id) AS total FROM tbl_code_trade AS trade
				LEFT JOIN social_member AS user ON trade.userid = user.id 
				WHERE trade.activity = 1 AND trade.otheruserid = 0
				AND trade.n_status = 0 AND trade.userid != {$this->apps->user->id} ORDER BY trade.datetime DESC ";
		$resTotal = $this->apps->fetch($sqlTotal);
		if($resTotal){
			if ($resTotal['total']>0) $total = $resTotal['total'];
			else $total = 0;
		}
		
		$sql = "SELECT trade.*, user.name, user.image_profile, user.photo_moderation FROM tbl_code_trade AS trade
				LEFT JOIN social_member AS user ON trade.userid = user.id 
				WHERE trade.activity = 1 AND trade.otheruserid = 0
				AND trade.n_status = 0 AND trade.userid != {$this->apps->user->id} ORDER BY trade.datetime DESC LIMIT {$start},{$limit}";
		$res = $this->apps->fetch($sql,1);
		// pr($sql);
		if ($res){
			// pr($res);
			$typeDouble = array('9','11','12');
			
			foreach ($res as $key => $value){
				if (in_array($value['sourceCode'], $typeDouble)){
					$res[$key]['sourceCodeName'] = $data[$value['sourceCode']].'1';
					
				}else{
					$res[$key]['sourceCodeName'] = $data[$value['sourceCode']];
					
				}
				
				if (in_array($value['targetCode'], $typeDouble)){
					
					$res[$key]['targetCodeName'] = $data[$value['targetCode']].'1';
				}else{
					
					$res[$key]['targetCodeName'] = $data[$value['targetCode']];
				}
				
			}
			
			return array('rec'=>$res, 'total'=>$total);
		}
		
		return false;
	}
	
	function makeTradeCode() {
		$user = $this->apps->user;
		// get master code
		$sql = "SELECT id, codename FROM tbl_code_detail WHERE n_status = 1";
		$res = $this->apps->fetch($sql,1);
		if ($res){
			$typeDouble = array('9','11','12');
			foreach ($res as $value){
				$data[$value['id']] = $value['codename'];
				$dataID[] = $value['id'];
				
				if (in_array($value['id'], $typeDouble)){
					$codeName[$value['id']] = $value['codename'].'1';
				}else{
					$codeName[$value['id']] = $value['codename'];
				}
				
			}
			
			// get master code 
			
			$getMasterCode = $this->getMasterCode();
			foreach ($getMasterCode as $value){
				$master[$value['id']] = $value['codename'];
				
			}
			
			// get my code
			$sql = "SELECT codeid FROM tbl_code_inventory WHERE userid = {$user->id} AND n_status = 0";
			$res = $this->apps->fetch($sql,1);
			if ($res){
				
				
				foreach ($res as $value){
					
					if (in_array($value['codeid'], $typeDouble)){
						$mycode[$value['codeid']] = $master[$value['codeid']].'1';
					}else{
						$mycode[$value['codeid']] = $master[$value['codeid']];
					}
					
					$mycodeID[] = $value['codeid'];
				}
				// pr($mycodeID);
				// foreach ($id as $value){
					// $code[] = $data[$value];
				// }
				
				
				
				// $myCode = array_unique($id);
				
				// need Code
				// foreach ($dataID as $value){
					// if (!in_array($value, $myCode)){
						// $needCode[$value] = $data[$value];
					// }
					 
				// }
				
				// get myCode
				// foreach ($myCode as $value){
					// $myCodeName[$value] = $data[$value];
					 
				// }
				// pr($mycode);
				return array('mycode'=>$mycode, 'needcode'=>$codeName, 'codeID'=>$dataID, 'mycodeID'=>$mycodeID);
				
				
			}
		}
		
	}
	
	function getTrade() {
		// $id = strip_tags($this->apps->_p('getTrade'));
		$id = intval($_SESSION['getTrade']);
		
		$getMasterCode = $this->getMasterCode();
		foreach ($getMasterCode as $value){
			$data[$value['id']] = $value['codename'];
		}
		
		$user = $this->apps->user;
		// ambil data trade berdasarkan id yang dipilih
		$sql = "SELECT trade.*, user.name FROM tbl_code_trade AS trade
				LEFT JOIN social_member AS user ON trade.userid = user.id 
				WHERE trade.id = {$id} ";
		$res = $this->apps->fetch($sql);
		
		if ($res){
			$res['sourceCodeName'] = $data[$res['sourceCode']];
			$res['targetCodeName'] = $data[$res['targetCode']];
			
			// cek apakah user aktif ada status trade atau tidak
			$sqlCheck = "SELECT id, sourceCode FROM tbl_code_trade WHERE sourceCode = {$res['targetCode']} 
							AND targetCode = {$res['sourceCode']} AND userid = {$user->id} AND n_status = 0 LIMIT 1"; 
			$resCheck = $this->apps->fetch($sqlCheck);
			// pr($sqlCheck);exit;
			if ($resCheck['id']){
				// jika user ada trade maka lakukan pengantian code
				// tuker record 
				$date = date('Y-m-d H:i:s');
				// $sqlme = "UPDATE tbl_code_trade SET userid = {$user->id}, datetime = '{$date}', n_status = 1 WHERE id = {$res['id']}";
				$sqlyou = "UPDATE tbl_code_trade SET otheruserid = {$user->id}, n_status = 2 WHERE id = {$res['id']}";
				// $sqlyou = "UPDATE tbl_code_trade SET userid = {$res['userid']}, datetime = '{$date}', n_status = 1 WHERE id = {$resCheck['id']}";
				$sqlme = "UPDATE tbl_code_trade SET otheruserid = {$res['userid']}, n_status = 2 WHERE id = {$resCheck['id']}";
				
				$resme = $this->apps->query($sqlme);
				$resyou = $this->apps->query($sqlyou);
				
				// exit;
				if (($resme) and ($resyou)){
					// ambil id dari tabel inv untuk masing2 record yang akan di tuker
					$getmyinv = "SELECT id FROM tbl_code_inventory WHERE codeid = {$resCheck['sourceCode']} AND userid = {$user->id} AND n_status = 5 LIMIT 1";
					$resmyinv = $this->apps->fetch($getmyinv);
					
					$getyouinv = "SELECT id FROM tbl_code_inventory WHERE codeid = {$res['sourceCode']} AND userid = {$res['userid']} AND n_status = 5 LIMIT 1";
					$resyouinv = $this->apps->fetch($getyouinv);
					// pr($resmyinv);
					// pr($resyouinv);
					if (($resmyinv) and ($resyouinv)){
						// update record di inv berdasarkan id yang sudah di select sebelumnya
						$updmyinv = "UPDATE tbl_code_inventory SET userid = {$res['userid']}, histories = 'trade' WHERE id = {$resmyinv['id']} ";
						$resupmyinv = $this->apps->query($updmyinv);
						
						$updyouinv = "UPDATE tbl_code_inventory SET userid = {$user->id}, histories = 'trade' WHERE id = {$resyouinv['id']} ";
						$resupyouinv = $this->apps->query($updyouinv);
						
						// ambil semua data yang dibutuhkan untuk tampilan
						$sql = "SELECT inv.*, master.codename FROM tbl_code_inventory AS inv LEFT JOIN tbl_code_detail AS master 
							ON inv.codeid = master.id WHERE inv.codeid = {$res['sourceCode']} AND inv.userid = {$this->apps->user->id}
							AND master.n_status = 1";
						
						// pr($sql);
						$result = $this->apps->fetch($sql);
						
						// set log pursuit
						$this->setPursuitUpdate(array('act'=>5, 'otherid' => $res['userid'], 'update'=>$res['id'].'-'.$resCheck['id']));
						
						if ($result){
							return array('source'=>$result, 'target'=>$res);
						}else{
							// echo '5';
							return false;
						}
					}else{
						// reset kembali data di trade sebelumnya
						$sqlyou = "UPDATE tbl_code_trade SET otheruserid = {$res['userid']}, n_status = 0 WHERE id = {$res['id']}";
						$sqlme = "UPDATE tbl_code_trade SET otheruserid = {$user->id}, n_status = 0 WHERE id = {$resCheck['id']}";
						$resme = $this->apps->query($sqlme);
						$resyou = $this->apps->query($sqlyou);
						// echo '4';
						return false;
					}
					
				}else{
				
					$sqlme = "UPDATE tbl_code_trade SET userid = {$res['userid']}, datetime = '{$date}', n_status = 1 WHERE id = {$res['id']}";
					$sqlyou = "UPDATE tbl_code_trade SET userid = {$user->id}, datetime = '{$date}', n_status = 1 WHERE id = {$resCheck['id']}";
					// pr($sqlme);
					// pr($sqlyou);
					
					$resme = $this->apps->query($sqlme);
					$resyou = $this->apps->query($sqlyou);
					
					// echo '3';
					return false;
				}
				
			}else{
				// echo '2';
				return false;
			}
			
		}else{
			// echo '1';
			return false;
		}
	}
	
	function getTradeFromFloor() {
		// ambil data ke tabel trade berdasarkan id
		$id = intval(intval($this->apps->_p('id')));
		$getMasterCode = $this->getMasterCode();
		foreach ($getMasterCode as $value){
			$data[$value['id']] = $value['codename'];
		}
		
		$user = $this->apps->user;
		// ambil data trade berdasarkan id yang dipilih
		$sql = "SELECT trade.*, user.name, user.image_profile, user.photo_moderation FROM tbl_code_trade AS trade
				LEFT JOIN social_member AS user ON trade.userid = user.id 
				WHERE trade.id = {$id} ";
		$res = $this->apps->fetch($sql);
		if ($res){
			
			$typeDouble = array('9','11','12');
			
			if (in_array($res['sourceCode'], $typeDouble)){
				$res['sourceCodeName'] = $data[$res['sourceCode']].'1';
			}else{
				$res['sourceCodeName'] = $data[$res['sourceCode']];
			}
				
			if (in_array($res['targetCode'], $typeDouble)){
				$res['targetCodeName'] = $data[$res['targetCode']].'1';
			}else{
				$res['targetCodeName'] = $data[$res['targetCode']];
			}
			
			// $res['sourceCodeName'] = $data[$res['sourceCode']];
			// $res['targetCodeName'] = $data[$res['targetCode']];
			
			// cek apakah user yang melakuka trade ada letter yang dibutuhkan org lain atau tidak
		
			$sql = "SELECT * FROM tbl_code_inventory WHERE userid = {$user->id} AND codeid = {$res['targetCode']} AND n_status = 0 LIMIT 1";
			$result = $this->apps->fetch($sql);
			
			if ($result){
				
				// ambil data detail dari trader di inventory
				// n status code yang di trade = 5
				
				$getotherinv = "SELECT id, userid, codeid FROM tbl_code_inventory WHERE codeid = {$res['sourceCode']} AND userid = {$res['userid']} AND n_status = 5 LIMIT 1";
				// pr($getotherinv);
				$resotherinv = $this->apps->fetch($getotherinv);
				if ($resotherinv){
					// lock terlebih dahulu record inventory masing2
					// ambil data inv tabel saya
					$lockmycode = "UPDATE  tbl_code_inventory SET n_Status = 1 WHERE id = {$result['id']} AND userid = {$user->id}";
					$reslockmycode = $this->apps->query($lockmycode);
					
					
					$lockothercode = "UPDATE  tbl_code_inventory SET n_Status = 1 WHERE id = {$resotherinv['id']} AND userid = {$resotherinv['userid']}";
					$reslockothercode = $this->apps->query($lockothercode);
					
					if (($reslockmycode) AND ($reslockothercode)){
						
						// update record di inv berdasarkan id yang sudah di lock sebelumnya
						$updmyinv = "UPDATE tbl_code_inventory SET codeid = {$resotherinv['codeid']}, histories = 'trade', n_status = 0 WHERE id = {$result['id']} ";
						$resupmyinv = $this->apps->query($updmyinv);
						
						$updotherinv = "UPDATE tbl_code_inventory SET codeid = {$result['codeid']}, histories = 'trade', n_status = 0 WHERE id = {$resotherinv['id']} ";
						$resupotherinv = $this->apps->query($updotherinv);
						
						if (($reslockmycode) AND ($reslockothercode)){
							// update tabel trade set n status = 2
							$sqlupdateTrade = "UPDATE tbl_code_trade SET n_status = 2 WHERE id = {$res['id']}";
							$resupdateTrade = $this->apps->query($sqlupdateTrade);
							
							sleep(1);
							
							$this->apps->log('tradefloor', $user->name.'  just traded the Letter '.$data[$res['sourceCode']].' from '.$res['name']);
							
							// ambil semua data yang dibutuhkan untuk tampilan
							$sql = "SELECT inv.*, master.codename FROM tbl_code_inventory AS inv LEFT JOIN tbl_code_detail AS master 
								ON inv.codeid = master.id WHERE inv.codeid = {$res['sourceCode']} AND inv.userid = {$user->id}
								AND master.n_status = 1";
							
							// pr($sql);
							$resultView = $this->apps->fetch($sql);
							if ($resultView) return array('source'=>$resultView, 'target'=>$res);
							// echo '5';
							return false;
						}else{
							// echo '4';
							return false;
						}
						
					}else{
						// echo '3';
						// release lock
						return false;
					}
				}else{
					// echo '6';
					return false;
				}
				
			}else{
				// user tidak ada huruf yang diacri oleh trader
				// echo '2';
				return false;
			}
			
			// pr($result);
		}else{
			// echo '1';
			return false;
		}
		
	}
	
	function getDataTrade() {
		$id = intval($this->apps->_p('idtrade'));
		
		$getMasterCode = $this->getMasterCode();
		foreach ($getMasterCode as $value){
			$data[$value['id']] = $value['codename'];
		}
		
		$user = $this->apps->user;
		
		$sql = "SELECT trade.*, user.name, user.image_profile, user.photo_moderation AS photo_approved FROM tbl_code_trade AS trade
				LEFT JOIN social_member AS user ON trade.userid = user.id 
				WHERE trade.id = {$id} ";
		$res = $this->apps->fetch($sql);
		// pr($sql);
		if ($res){
		
			$typeDouble = array('9','11','12');
			
			if (in_array($res['sourceCode'], $typeDouble)){
				$res['sourceCodeName'] = $data[$res['sourceCode']].'1';
			}else{
				$res['sourceCodeName'] = $data[$res['sourceCode']];
			}
			
			if (in_array($res['targetCode'], $typeDouble)){
				$res['targetCodeName'] = $data[$res['targetCode']].'1';
			}else{
				$res['targetCodeName'] = $data[$res['targetCode']];
			}
			
			// $res['sourceCodeName'] = $data[$res['sourceCode']];
			// $res['targetCodeName'] = $data[$res['targetCode']];
			$res['myname'] = $user->name;
			
			$getmyprofile = "SELECT name, image_profile, photo_moderation FROM social_member WHERE id = {$user->id}";
			$resmyprofile = $this->apps->fetch($getmyprofile);
			if ($resmyprofile){
				$res['photo_moderation'] = $resmyprofile['photo_moderation'];
				$res['mypicture'] = $resmyprofile['image_profile'];
			}
			
			// cek apakah user aktif ada letter trade atau tidak
			$sqlCheck = "SELECT COUNT(codeid) AS jumlah FROM  tbl_code_inventory WHERE codeid = {$res['targetCode']} 
						AND userid = {$user->id} AND n_status = 0 LIMIT 1"; 
			$resCheck = $this->apps->fetch($sqlCheck);
			// pr($sqlCheck);
			if ($resCheck['jumlah']){
				// pr($res);
				return $res;
			}else{
				return false;
			}
		} else {
			return false;
		}
	}
	
	function cancelTrade() {
		$id = intval($this->apps->_p('cancelTrade'));
		
		$getMasterCode = $this->getMasterCode();
		foreach ($getMasterCode as $value){
			$data[$value['id']] = $value['codename'];
		}
		
		$sql = "SELECT sourceCode, targetCode FROM tbl_code_trade WHERE id = {$id} AND n_status = 0";
		$result = $this->apps->fetch($sql);
		if ($result){
			if ($result['sourceCode']){
				$sql = "UPDATE tbl_code_trade SET activity = 0, n_status = 2 WHERE id = {$id}";
				$res = $this->apps->query($sql);
				if ($res){
					$sql = "UPDATE tbl_code_inventory SET n_status = 0 WHERE codeid = {$result['sourceCode']} AND n_status = 5 AND userid = {$this->apps->user->id} LIMIT 1";
					$res = $this->apps->query($sql);
					
					sleep(1);
					
					$this->apps->log('trading', 'You have succesfully cancel your trade for '.$data[$result['sourceCode']].' with '.$data[$result['targetCode']]);
					
					return true;
				}
				
				return false;
			}
		}
		
		return false;
	}
	
	function lookTradeToPost() {
		$look = intval($this->apps->_p('look'));
		$trade = intval($this->apps->_p('trade'));
		$user = $this->apps->user;
		
		// get master code
		$masterCode = $this->getMasterCode();
		foreach ($masterCode as $value){
			$master[] = $value['id'];
			$data[$value['id']] = $value['codename'];
		}
		$sql = "SELECT DISTINCT(codeid) AS mycode, id FROM tbl_code_inventory WHERE userid = {$user->id} AND n_status = 0";
		$res = $this->apps->fetch($sql,1);
		// pr($res);
		if ($res){
			foreach ($res as $value){
				$mycode[] = $value['mycode'];
			}
		}else{
			// echo '1';
			return false;
		}
		
		if (in_array($trade, $mycode)){ 
			$trade = $trade;
		}else{
			// echo '2';
			return false;
		}
		
		if (in_array($look, $master)){ 
			$look = $look;
		}else{
			// echo '3';
			return false;
		}
		
		// pr($masterCode);
		$date = date('Y-m-d H:i:s');
		$sql = "INSERT INTO tbl_code_trade (userid, activity, sourceCode, targetCode, datetime, n_status)
				VALUES ({$user->id}, '1', {$trade}, {$look},'{$date}',0)";
		$result = $this->apps->query($sql);
		if ($result){
		
			$update = "UPDATE tbl_code_inventory SET n_status = 5 WHERE codeid = {$trade} AND userid = {$user->id} AND n_status IN (0)  LIMIT 1";
			$resultUpdate = $this->apps->query($update);
			
			sleep(1);
			
			$this->apps->log('tradefloor', $user->name. ' post trade '.$data[$look]);
			return true;
		}
		// echo '4';
		return false;
	}
	
	function sendTradeReq() {
		$codefrom = intval($this->apps->_p('codefrom'));
		$codeto = intval($this->apps->_p('codeto'));
		$msg = strip_tags($this->apps->_p('msg'));
		$idtarget = intval($this->apps->_p('idtarget'));
		
		$user = $this->apps->user;
		$date = date('Y-m-d H:i:s');
		$sql = "INSERT INTO my_message (fromid, recipientid, fromwho, message, sourceCode, targetCode, datetime, n_status)
				VALUES ({$user->id}, {$idtarget}, 1, '{$msg}', {$codefrom}, {$codeto}, '{$date}', 0)";
		
		$res = $this->apps->query($sql);
		if ($res) return true;
		return false;
	}
	
	function giveLetterWhenUserLogin() {
		$sql = "SELECT COUNT(id) jumlah FROM tbl_activity_log WHERE action_id = 28 AND user_id = {$this->apps->user->id}";
		$res = $this->apps->fetch($sql);
		if ($res['jumlah']>0){
			return false;
		}else{
			
			$sql = "SELECT usertype FROM social_member WHERE id = {$this->apps->user->id} LIMIT 1";
			$result = $res = $this->apps->fetch($sql);
			if ($result['usertype'] == 2){
				$this->generateLetterForUserGift(2);
			}else{
				$this->generateLetterForUserGift(1);
			}
			
			return true;
		}
	} 
	
	function generateLetterForUserGift($param=1) {
		$sqlDetail = "SELECT * FROM tbl_code_publicity WHERE channel = 'key account' ORDER BY id ASC";
		$resDetail = $this->apps->fetch($sqlDetail,1);
		if ($resDetail){
			foreach ($resDetail as $value){
				$idPublicity[] = $value['id'];
				$dataLetter[$value['id']] = $value;
			}
			
			$sqlGetUserLetter = "SELECT * FROM tbl_code_inventory WHERE userid = {$this->apps->user->id}";
			$resGetUserLetter = $resDetail = $this->apps->fetch($sqlGetUserLetter,1);
			if ($resGetUserLetter){
				foreach ($resGetUserLetter as $value){
					$idPublicityUser[] = $value['codepublicityid'];
					
				}
				
				$setIdPublicity = rand(min($idPublicity), max($idPublicity));
				
				
				$date = date('Y-m-d H:i:s');
				
				if ($param == 2){
					for ($i = 0; $i<=1; $i++){
						
						$data = $dataLetter[$setIdPublicity];
						
						$sql = "INSERT INTO tbl_code_inventory (userid, codeid, codepublicityid, n_status, datetimes, histories) VALUES ({$this->apps->user->id}, '{$data['codeid']}', '{$data['id']}', 0, '{$date}', 'key account')";
						// pr($sql);
						$result = $this->apps->query($sql);
						
					}
					if ($result){
						$this->apps->log("logingift", "Congratulation!, You've got your first. 2 Letters for joining The Pursuit");
						
						return true;
					}
					
				}else{
					$data = $dataLetter[$setIdPublicity];
					
					$sql = "INSERT INTO tbl_code_inventory (userid, codeid, codepublicityid, n_status, datetimes, histories) VALUES ({$this->apps->user->id}, '{$data['codeid']}', '{$data['id']}', 0, '{$date}', 'key account')";
				
					$this->apps->query($sql);
					if($this->apps->getLastInsertId()){						
						
					
						// $this->apps->log("logingift", "Congratulation You have got your first letters for joining The Pursuit");
						
						return true;
					}
					
				}
				
				
				
			}else{
			
			}
		}
	}
	
	function getMyMessgSent($start=0,$limit=5,$n_status="1")
	{
		$user = $this->apps->user;
		$messageStatus = '0,1';
		
		$sql ="
			SELECT my.id, IFNULL( my.subject, 'no subject' ) subject, my.message,my.fromid,my.recipientid,my.fromwho,my.datetime,my.n_status, person.name,1 type FROM my_message AS my LEFT JOIN social_member AS person 
			ON my.recipientid = person.id WHERE my.fromid = {$user->id} AND my.n_status IN ({$messageStatus})
			ORDER BY datetime DESC 
			LIMIT {$start},{$limit};
		";
		// pr($sql);
		$this->logger->log($sql);
		$qData = $this->apps->fetch($sql,1);
		// pr($notif);
		
		$sqlcount ="
			SELECT SUM(total) AS total FROM (
			SELECT COUNT(my.id) AS total FROM my_message AS my LEFT JOIN social_member AS person 
			ON my.recipientid = person.id WHERE my.fromid = {$user->id} AND my.n_status IN ({$messageStatus}) ORDER BY datetime DESC 
			) AS total";
		$res = $this->apps->fetch($sqlcount);
		if ($qData){
			// pr($qData);exit;
			
			
			return array('data'=>$qData, 'total'=>$res['total']);
		}
		
		return false;
	}
	
	
	function getMyMessgTrash($start=0,$limit=5,$n_status="1")
	{
		$user = $this->apps->user;
		$messageStatus = 2;
		$acion_id = $this->action_id;
		
		$sql ="
			
			(SELECT my.id,IFNULL( my.subject, 'no subject' ) subject, my.message,my.fromid,my.recipientid,my.fromwho,my.datetime datetime,my.n_status, person.name,1 type FROM my_message AS my LEFT JOIN social_member AS person 
			ON my.recipientid = person.id WHERE my.fromid = {$user->id} AND my.n_status IN ({$messageStatus})
			ORDER BY datetime DESC )
			UNION (
			SELECT my.id,'' subject,my.action_value message,'' fromid,my.user_id recipientid,'' fromwho,my.date_time datetime,my.n_status,'[notification]' name,0 type FROM tbl_activity_log AS my LEFT JOIN social_member AS person 
			ON my.user_id = person.id WHERE my.user_id = {$user->id} AND action_id IN ({$acion_id}) AND my.n_status IN ({$messageStatus}) ORDER BY my.date_time DESC )
			UNION (
			SELECT gm.id, '' subject,gm.description, '' fromid, '' recipientid, '3' fromwho, gm.date_time datetime,'1'  n_status, '[notification]' name, 0 type FROM gm_activity_log AS gm WHERE action = 'inputtask' AND ( gm.n_status IN ({$messageStatus}) OR gm.id IN ( SELECT notifid FROM tbl_un_notification unotif WHERE unotif.userid = {$user->id} AND  unotif.type='adminnotif' AND  unotif.n_status = 2 AND gm.id = unotif.notifid ))
			ORDER BY datetime DESC )
			ORDER BY datetime DESC
			LIMIT {$start},{$limit};
		";
		// pr($sql);
		$this->logger->log($sql);
		$qData = $this->apps->fetch($sql,1);
		// pr($notif);
		
		$sqlcount ="
			SELECT SUM(total) AS total FROM (
			SELECT COUNT(my.id) AS total FROM my_message AS my LEFT JOIN social_member AS person 
			ON my.recipientid = person.id WHERE my.fromid = {$user->id} AND my.n_status IN ({$messageStatus}) ORDER BY datetime DESC 
			) AS total";
		$res = $this->apps->fetch($sqlcount);
		if ($qData){
			// pr($qData);exit;
			return array('data'=>$qData, 'total'=>$res['total']);
		}
		
		return false;
	}
	
	function getMyNotification($start=0,$limit=5,$n_status="1")
	{
		$acion_id = $this->action_id;
		$user = $this->apps->user;
		
		// $letterGift = $this->giveLetterWhenUserLogin();
		
		if (isset($_SESSION['statusinbox'])){
			
			$status = @$_SESSION['statusinbox'];
			if ($status == 1)$messageStatus = '0,1';
			if ($status == 2)$messageStatus = '0,1';
			if ($status == 3)$messageStatus = '2';
			if ($status == 4)$messageStatus = '0,1';
			// if ($status == 5)$messageStatus = '2';
		}else{
			$messageStatus = '2,3';
		}
		
		$sql ="
			(SELECT my.id,'' subject,my.action_value message,'' fromid,my.user_id recipientid,'' fromwho,my.date_time datetime,my.n_status,'[notification]' name,0 type FROM tbl_activity_log AS my LEFT JOIN social_member AS person 
			ON my.user_id = person.id WHERE my.user_id = {$user->id} AND action_id IN ({$acion_id}) AND my.n_status IN ({$messageStatus}) ORDER BY my.date_time DESC )
			UNION 
			(SELECT gm.id, '' subject,gm.description, '' fromid, '' recipientid, '3' fromwho, gm.date_time,'1'  n_status, '[notification]' name, 0 type FROM gm_activity_log AS gm WHERE action = 'inputtask' AND gm.n_status IN ({$messageStatus}) AND NOT EXISTS ( SELECT * FROM tbl_un_notification unotif WHERE unotif.userid = {$user->id} AND  unotif.type='adminnotif' AND  unotif.n_status IN (2,3) AND gm.id = unotif.notifid ) )
			ORDER BY datetime DESC 
			LIMIT {$start},{$limit};
		";
		// pr($sql);exit;
		$this->logger->log($sql);
		$qData = $this->apps->fetch($sql,1);
		// pr($notif);
		
		$sqlcount ="
			SELECT SUM(total) AS total FROM (
			(SELECT COUNT(my.id) AS total FROM tbl_activity_log AS my LEFT JOIN social_member AS person 
			ON my.user_id = person.id WHERE my.user_id = {$user->id} AND action_id IN ({$acion_id}) AND my.n_status IN ({$messageStatus}) ORDER BY my.date_time DESC )
			UNION 
			(SELECT COUNT(gm.id) AS total FROM gm_activity_log AS gm WHERE action = 'inputtask')
			) AS total";
		$res = $this->apps->fetch($sqlcount);
		if ($qData){
			// pr($qData);exit;
			return array('data'=>$qData, 'total'=>$res['total']);
		}
		
		return false;
	}
	
	
	function getMymesgTrade($start=0,$limit=5,$n_status="1") {
		$acion_id = $this->action_id;
		$user = $this->apps->user;
		
		// $letterGift = $this->giveLetterWhenUserLogin();
		
		if (isset($_SESSION['statusinbox'])){
			
			$status = @$_SESSION['statusinbox'];
			if ($status == 1)$messageStatus = '0,1';
			if ($status == 2)$messageStatus = '0,1';
			if ($status == 3)$messageStatus = '2';
			if ($status == 4)$messageStatus = '0,1';
			// if ($status == 5)$messageStatus = '2';
		}else{
			$messageStatus = '2,3';
		}
		
		// pr($_SESSION['statusinbox']);
		$sql ="
			SELECT my.id,IFNULL( my.subject, 'no subject' ) subject, my.message,my.fromid,my.recipientid,my.fromwho,my.datetime,my.n_status, person.name,1 type FROM my_message AS my LEFT JOIN social_member AS person 
			ON my.fromid = person.id WHERE my.recipientid = {$user->id} AND my.n_status IN ({$messageStatus})
			ORDER BY datetime DESC 
			LIMIT {$start},{$limit};
		";
		// pr($sql);
		$this->logger->log($sql);
		$qData = $this->apps->fetch($sql,1);
		// pr($notif);
		
		$sqlcount ="
			SELECT SUM(total) AS total FROM (
			SELECT COUNT(my.id) AS total FROM my_message AS my LEFT JOIN social_member AS person 
			ON my.fromid = person.id WHERE my.recipientid = {$user->id} AND my.n_status IN ({$messageStatus}) ORDER BY datetime DESC 
			) AS total";
		$res = $this->apps->fetch($sqlcount);
		if ($qData){
			// pr($qData);exit;
			return array('data'=>$qData, 'total'=>$res['total']);
		}
		
		
		/*
		// sorting data by datetime
			foreach ($res as $key => $value){
				$dataIndex[$key] = $value['taskdate'];
			}
			
			arsort($dataIndex);
			// get key sort
			foreach ($dataIndex as $key => $value){
				$dataIndexFix[] = $key;
			}
			// get array sort 
			foreach ($dataIndexFix as $value){
				
				$result[] = $res[$value];
			}
		*/
		// if ($data['mesg']) return $data;
		
		return false;
	}
	
	function getMyTradeReq($start=0,$limit=3,$n_status="1") {
		$user = $this->apps->user;
		
		if($start==null)$start = intval($this->apps->_request('start'));
		/*
		$sql = "SELECT my.*, person.name FROM my_message AS my LEFT JOIN social_member AS person 
				ON my.fromid = person.id WHERE my.recipientid = {$user->id}";
		*/
		
		$sqlTotal = "SELECT COUNT(id) AS total FROM  tbl_code_trade WHERE userid = {$user->id} AND n_status IN (0,1) ORDER BY id DESC";
		$resTotal = $this->apps->fetch($sqlTotal);
		if ($resTotal){
			if ($resTotal['total']>0) $total = $resTotal['total'];
			else $total = 0;
		}
		
		
		$sql = "SELECT * FROM  tbl_code_trade WHERE userid = {$user->id} AND n_status IN (0,1) ORDER BY id DESC LIMIT {$start}, {$limit}";
		$res = $this->apps->fetch($sql,1);
		// pr($sql);
		$masterCode = $this->getMasterCode();
		// pr($masterCode);
		
		$typeDouble = array('9','11','12');
		
		if ($res){
			foreach ($masterCode as $value){
				$changeMaster[$value['id']] = $value['codename'];
			}
			
			foreach ($res as $key => $value){
				// $res[$key]['fromCode'] = $changeMaster[$value['sourceCode']];
				// $res[$key]['toCode'] = $changeMaster[$value['targetCode']];
			
				if (in_array($value['sourceCode'], $typeDouble)){
					$res[$key]['fromCode'] = $changeMaster[$value['sourceCode']].'1';
					// $res[$key]['toCode'] = $changeMaster[$value['targetCode']].'1';
				}else{
					// $res[$key]['sourceCodeName'] = $data[$value['sourceCode']];
					$res[$key]['fromCode'] = $changeMaster[$value['sourceCode']];
					// $res[$key]['toCode'] = $changeMaster[$value['targetCode']];
				}
				
				if (in_array($value['targetCode'], $typeDouble)){
					// $res[$key]['sourceCodeName'] = $data[$value['sourceCode']].'1';
					// $res[$key]['fromCode'] = $changeMaster[$value['sourceCode']].'1';
					$res[$key]['toCode'] = $changeMaster[$value['targetCode']].'1';
				}else{
					// $res[$key]['sourceCodeName'] = $data[$value['sourceCode']];
					// $res[$key]['fromCode'] = $changeMaster[$value['sourceCode']];
					$res[$key]['toCode'] = $changeMaster[$value['targetCode']];
				}
				
			}
			
			// pr($res);
			return array('rec'=>$res, 'total'=>$total);
		}
		// pr($res);
		
		return false;
	}
	
	function getPursuitPlayer($start=null,$limit=12,$n_status="1") {
		if($start==null)$start = intval($this->apps->_request('start'));
		// $data = trim(strip_tags($this->apps->_p('paging')));
		
		$sql = "SELECT id,name, email, image_profile, photo_moderation FROM social_member WHERE verified = 1 AND n_status = 1 AND id != {$this->apps->user->id}  ORDER BY register_date DESC LIMIT {$start},{$limit}";
		
		
		$sqlTotal = "SELECT COUNT(id) AS total FROM social_member WHERE verified = 1 AND n_status = 1 AND id != {$this->apps->user->id}";
		$resTotal = $this->apps->fetch($sqlTotal);
		
		
		$res = $this->apps->fetch($sql, 1);
		if ($res){
			
			$total = $resTotal['total'];
			// pr($sql);
			return array('rec'=>$res, 'total'=>$total);
		}
		return false;
	}
	
	function searchPursuitPlayer() {
		$sql = "SELECT name, email,image_profile FROM social_member WHERE verified = 1 AND n_status = 1 AND id != {$this->apps->user->id}";
		$res = $this->apps->fetch($sql, 1);
		if ($res){
			return $res;
		}
		return false;
	}
	
	function getMyLetterDetail() {
		$getMasterCode = $this->getMasterCode();
		if ($getMasterCode){
		
			foreach ($getMasterCode as $value){
				$data[$value['id']] = $value['codename'];
			}
		}
		
		$user = $this->apps->user;
		$sql = "SELECT codeid FROM tbl_code_inventory WHERE userid = {$user->id} AND n_status = 0  ";
		$res = $this->apps->fetch($sql,1);
		// pr($sql);
		if ($res){
			
			foreach ($res as $value){
				$mycode[$value['codeid']][] = $value['codeid'];
			}	
			
			foreach($data as $key => $val){
				$code[$key]['letter'] = $val;
				if(array_key_exists($key,$mycode)) $code[$key]['total'] = count($mycode[$key]);
				else $code[$key]['total'] = 0;
			}
			// pr($code);
			return $code;
		}else{
			return false;
		}
		
	}
	
	function getMyLetterAlreadySet() {
		$getMasterCode = $this->getMasterCode();
		if ($getMasterCode){
		
			foreach ($getMasterCode as $value){
				$data[$value['id']] = $value['codename'];
			}
		}
		
		$user = $this->apps->user;
		$sql = "SELECT codeid FROM tbl_code_inventory WHERE userid = {$user->id} AND n_status = 3  ";
		$res = $this->apps->fetch($sql,1);
		// pr($sql);
		if ($res){
			
			foreach ($res as $value){
				$mycode[$value['codeid']][] = $value['codeid'];
			}	
			
			foreach($data as $key => $val){
				$code[$key]['letter'] = $val;
				if(array_key_exists($key,$mycode)) $code[$key]['total'] = count($mycode[$key]);
				else $code[$key]['total'] = 0;
			}
			// pr($code);
			return $code;
		}else{
			return false;
		}
	}
	
	function getMyLetter() {
		$getMasterCode = $this->getMasterCode();
		if ($getMasterCode){
		
			foreach ($getMasterCode as $value){
				$data[$value['id']] = $value['codename'];
			}
		}
		
		
		$user = $this->apps->user;
		$sql = "SELECT codeid FROM tbl_code_inventory WHERE userid = {$user->id} AND n_status = 3 GROUP BY codeid";
		$res = $this->apps->fetch($sql,1);
		if ($res){
			
			foreach ($res as $value){
				$code[$value['codeid']] = $data[$value['codeid']];
			}
			
			return $code;
		}else{
			return false;
		}
		
	}
	
	function getPursuitUpdate($start=null,$limit=5,$n_status="1") {
		if($start==null)$start = intval($this->apps->_request('start'));
		
		$masterCode = $this->getMasterCode();
		if ($masterCode){
			foreach ($masterCode as $value){
				$master[$value['id']] = $value['codename'];
			}
		}
		
		$acion_id = '20,21,22,23,24,25,26,27';
		$sql = "SELECT log.action_id,log.action_value, user.name, user.image_profile, user.photo_moderation 
				FROM tbl_activity_log AS log 
				LEFT JOIN social_member AS user
				ON log.user_id = user.id WHERE log.n_status = 0 AND log.action_id IN ({$acion_id}) ORDER BY log.id DESC LIMIT {$start},{$limit}";
		$res = $this->apps->fetch($sql,1);
		// pr($sql);
		$total = "SELECT COUNT(log.id) AS total FROM tbl_activity_log AS log LEFT JOIN social_member AS user
				ON log.user_id = user.id WHERE log.n_status = 0 AND log.action_id IN ({$acion_id}) ORDER BY log.id DESC";
		$restotal = $this->apps->fetch($total);
		// pr($sql);
		if ($res){
			$total = $restotal['total'];
			return array('rec'=>$res, 'total'=>$total);
		}
		return false;
	}
	
	function taskList($start=null,$limit=5,$n_status="1") {
		
		$start = intval($this->apps->_p('start'));
		$newtaskid = false;
		$user = $this->apps->user;
		$sql = "SELECT id FROM marlborohunt_news_content_type WHERE content = 1 AND id != 17"; // 17 = DYO task
		$res = $this->apps->fetch($sql,1);
		if ($res){
			// pr($res);
			foreach ($res as $value){
				$data[$value['id']] = $value['id'];
			}
			$contentID = implode(',',$data);
			
			$sql = "SELECT taskid FROM my_task WHERE n_status = 1 AND userid = {$user->id}";
			$resTask = $this->apps->fetch($sql,1);
			// pr($resTask);
			if ($resTask){
				foreach ($resTask as $value){
					$task_id[$value['taskid']] = $value['taskid'];
					
					// pr($value);
				}
				
				//get contentid splicer
				$sql = " SELECT id FROM {$this->dbshema}_news_content WHERE categoryid = 1 ";
				$qData = $this->apps->fetch($sql,1);
				
				if($qData){
					foreach($qData as $val){
						$contentarr[$val['id']] = $val['id'];
					}
				
					if($contentarr){
						// pr($contentarr);
						// pr($contentarr);
						foreach($task_id as $val){
							if(!array_key_exists($val,$contentarr)) $newtaskid[$val]=$val;
						}
						
						if($newtaskid==false) $newtaskid = array(0);
					}
				}
				
				if($newtaskid) $task_id = $newtaskid;
				
				// pr($task_id);
				
				$taskID = implode(',',$task_id);
			}else{
				$taskID = 0;
			}
			
			$sql = "SELECT id, title, articleType, content, topcontent,image FROM  marlborohunt_news_content WHERE 
			n_status = 1 AND 
			articleType IN ({$contentID}) AND id NOT IN ({$taskID}) ORDER BY posted_date DESC LIMIT {$start},{$limit}";
			$result = $this->apps->fetch($sql, 1);
			// pr($sql);
			
			$sqlTotal = "SELECT COUNT(id) AS total FROM  marlborohunt_news_content WHERE n_status = 1 AND  articleType IN ({$contentID}) AND id NOT IN ({$taskID}) ORDER BY posted_date DESC";
			$resultTotal = $this->apps->fetch($sqlTotal);
			
			if ($result){
				
				return array('rec'=>$result, 'total'=>$resultTotal['total']);
				// return $result;
			}
			
			
		}
		
		return FALSE;
	}
	
	function accomplishedTask($start=null,$limit=5,$n_status="1") {
		$start = intval($this->apps->_p('start'));
		
		$user = $this->apps->user;
		// $sql = "SELECT news.id, news.title FROM marlborohunt_news_content AS news WHERE news.articleType = 3 
				// AND news.n_status = 1 LIMIT 5"; // 3 = task
		
		$sql = "SELECT news.id, news.title , news.topcontent , news.image ,  task.taskdate 
				FROM my_task AS task 
				LEFT JOIN {$this->dbshema}_news_content AS news 
				  ON task.taskid = news.id 
				LEFT JOIN {$this->dbshema}_news_content_type AS ctype 
				  ON news.articleType = ctype.id 
				WHERE ctype.content = 1 
				AND news.n_status = 1 AND task.userid = {$user->id} ORDER BY taskdate DESC"; // 3 = task
				 
		$res = $this->apps->fetch($sql, 1);
		// pr($sql);
		// $sqlTask = "SELECT taskid FROM my_task WHERE n_status = 1 AND userid = {$user->id} ORDER BY id DESC LIMIT 5"; // 3 = task
		// $resTask = $this->apps->fetch($sqlTask, 1);
		
		// get hidden code log to store in accomplished task too
		$sqlTaskHidden = "SELECT * FROM  tbl_activity_log WHERE user_id = {$this->apps->user->id} AND action_id = 26";
		$resTaskHidden = $this->apps->fetch($sqlTaskHidden,1);
		
		
		if ($res) {
			
			// $data['task'] = $res;
			// $data['accomplish'] = $resTask;
			
			// return $data;
			if ($resTaskHidden){
				$logHidden = $resTaskHidden;
				
				foreach ($res as $key => $value){
					$keyRes = $key; 
				}
				
				foreach ($logHidden as $key => $value){
					$res[$keyRes+1]['id'] = $value['id'];
					$res[$keyRes+1]['title'] = $value['action_value'];
					$res[$keyRes+1]['topcontent'] = 0;
					$res[$keyRes+1]['image'] = "";
					$res[$keyRes+1]['taskdate'] = $value['date_time'];
				}
			}
			// pr($res);
			// sorting data by datetime
			foreach ($res as $key => $value){
				$dataIndex[$key] = $value['taskdate'];
			}
			
			arsort($dataIndex);
			// get key sort
			foreach ($dataIndex as $key => $value){
				$dataIndexFix[] = $key;
			}
			// get array sort 
			foreach ($dataIndexFix as $value){
				
				$result[] = $res[$value];
			}
			
			
			return $result;
			// return $res;
			
		}
		return FALSE;
	}
	
	function getAccomplishedTask($start=null,$limit=5,$n_status="1") {
		$start = intval($this->apps->_p('start'));
		$user = $this->apps->user;
		
		$sql = "(SELECT news.id AS id, news.title AS title, task.taskdate AS date, news.image AS image
				FROM my_task AS task 
				LEFT JOIN {$this->dbshema}_news_content AS news 
				  ON task.taskid = news.id 
				LEFT JOIN {$this->dbshema}_news_content_type AS ctype 
				  ON news.articleType = ctype.id 
				WHERE news.n_status NOT IN (2) AND task.userid = {$user->id})
				UNION 
				(SELECT log.id AS id, log.action_value AS title, log.date_time AS date, log.session AS image
				FROM tbl_activity_log AS log WHERE log.user_id = {$this->apps->user->id} AND log.action_id = 26
				)
				ORDER BY date DESC LIMIT {$start},{$limit}"; // 3 = task
		// pr($sql);		 
		
		$res = $this->apps->fetch($sql, 1);
		
		$sqlTotal = "(SELECT COUNT(news.id) AS total
				FROM my_task AS task 
				LEFT JOIN {$this->dbshema}_news_content AS news 
				  ON task.taskid = news.id 
				LEFT JOIN {$this->dbshema}_news_content_type AS ctype 
				  ON news.articleType = ctype.id 
				WHERE ctype.content = 1 
				AND news.n_status NOT IN (2)  AND task.userid = {$user->id})
				UNION 
				(SELECT COUNT(log.id) AS total
				FROM tbl_activity_log AS log WHERE log.user_id = {$this->apps->user->id} AND log.action_id = 26
				)
				";
		// pr($sqlTotal);
		$result = $this->apps->fetch($sqlTotal,1);
		if ($result){
			$jumlah = 0;
			foreach ($result as $value){
				$jumlah += $value['total'];
			}
			
		}
		
		if ($res){
			return array('rec'=>$res, 'total'=>$jumlah);
		}
		return false;
	}
	
	function sendTradeMesg() { 
		
		
		$recipientid = strip_tags($this->apps->_p('id'));
		$mesg = strip_tags($this->apps->_p('mesg'));
		
		$user = $this->apps->user;
		$date = date('Y-m-d H:i:s');
		$sql = "INSERT INTO my_message (fromid, recipientid, fromwho, message, datetime, n_status)
				VALUES ({$user->id}, {$recipientid}, 1, '{$mesg}', '{$date}', 0)";
		$res = $this->apps->query($sql);
		if ($res){
			return true;
		}
		return false;
	}
	
	function composeMessage($to=false,$subject,$messg)
	{
		
		if (!$to) return false;
		$user = $this->apps->user;
		$date = date('Y-m-d H:i:s');
		$sql = "INSERT INTO my_message (fromid, recipientid, fromwho, subject, message, datetime, n_status)
				VALUES ({$user->id}, {$to}, 1, '{$subject}', '{$messg}', '{$date}', 0)";
		// pr($sql);
		$res = $this->apps->query($sql);
		if ($res){
			return true;
		}
		return false;
	}
	
	function invitePursuitFriends() {
		
		$inviteData = strip_tags($this->apps->_p('friendsEmail'));
		$explode = explode(',', $inviteData);
		$user = $this->apps->user;
		$date = date('Y-m-d H:i:s');
		
		
		$getListUser = "SELECT id, name, email FROM social_member WHERE verified = 1 ";
		$resListUser = $this->apps->fetch($getListUser,1);
		if ($resListUser){
			foreach ($resListUser as $value){
				$dataUser[$value['id']] = $value['email'];
				$namaUser[$value['id']] = $value['name'];
				
			}
		}
		
		foreach ($explode as $value){
			if ($value !=""){
				
				$id = array_search(trim($value), $dataUser);
				if ($id){
					$mesg = 'Hi '.$namaUser[$id]. ','. $user->name." invite you to join pursuit game";
					
					$sql = "INSERT INTO my_message (fromid, recipientid, fromwho, message, datetime, n_status)
							VALUES ({$user->id}, {$id}, 1, '{$mesg}', '{$date}', 1)";
					
					$res = $this->apps->query($sql);
				}
				
			}
		}
		
	}

	function getSearchTradingFloor() {
		$keywords = strip_tags($this->apps->_p('keywords'));	
		
		if ($keywords == ""){
			return $this->listTradeCode();

		}else{
		
			$getMasterCode = $this->getMasterCode();
			if ($getMasterCode){
			
				foreach ($getMasterCode as $value){
					$data[$value['id']] = $value['codename'];
					// $dataID[] = $value['id'];
					// $codeName[] = $value['codename'];
				}
			}
			
			// query trade
			
			$search = array_search(strtoupper($keywords), $data);
			
			foreach ($data as $key => $value){
				if ($value == strtoupper($keywords)){
					$tradeID[] = $key;
				}
			}
			
			if (isset($tradeID)){
				$masterDetail = implode(',',$tradeID);
			}else{
				$masterDetail = 0;
			}
			
			
			$sql = "SELECT trade.id AS idtrade, trade.sourceCode, trade.targetCode, user.id AS iduser, user.name, user.image_profile
					FROM tbl_code_trade AS trade
					LEFT JOIN social_member AS user ON trade.userid = user.id
					WHERE (
					user.name LIKE '%{$keywords}%'
					OR user.last_name LIKE '%{$keywords}%'
					OR trade.sourceCode
					IN ( {$masterDetail} )
					OR trade.targetCode
					IN ( {$masterDetail} )
					)
					AND trade.userid !={$this->apps->user->id} 
					ORDER BY trade.datetime DESC";
			// pr($sql);
			$res = $this->apps->fetch($sql,1);
			if ($res){
				foreach ($res as $key =>$value){
					$res[$key]['sourceCodeName'] = $data[$value['sourceCode']];
					$res[$key]['targetCodeName'] = $data[$value['targetCode']];
					
				}
				
				return $res;
			}
			return false;
			
			/*
			$sqlsource = "SELECT trade.id AS idtrade, trade.sourceCode, trade.targetCode, user.id AS iduser, user.name, master.codename
					FROM tbl_code_trade AS trade
					LEFT JOIN social_member AS user ON trade.userid = user.id
					LEFT JOIN tbl_code_detail AS master ON trade.sourceCode = master.id 
					WHERE user.name LIKE '%{$keywords}%' 
					AND user.verified = 1 AND
					trade.activity = 1 AND trade.otheruserid = 0
					AND trade.n_status = 0 AND trade.userid != {$this->apps->user->id} 
					OR master.codename LIKE UPPER('{$keywords}')
					ORDER BY trade.datetime DESC";
			pr($sqlsource);
			$ressource = $this->apps->fetch($sqlsource,1);
			if ($ressource){
				foreach ($ressource as $key =>$value){
					$ressource[$key]['sourceCodeName'] = $data[$value['sourceCode']];
					$ressource[$key]['targetCodeName'] = $data[$value['targetCode']];
				}
				
				
				// return $ressource;
			}
			
			if ($res){
				// echo 'res';
				$dataHasil = $res;
			}
			
			if ($ressource){
				// echo 'resource';
				$dataHasil = $ressource;
			}
			
			if ($res and $ressource){
				// echo 'gabung';
				$merge = array_merge($res, $ressource);
				foreach ($merge as $key =>$value){
					$dataUnik[$value['idtrade']] = $value; 
				}
				
				foreach ($dataUnik as $key =>$value){
					$Hasil[] = $value; 
				}
				$dataHasil = $Hasil;
			}
			
			
			// pr($dataHasil);
			
			if (isset($dataHasil))return $dataHasil;
			else return false;*/
		}
		
		
	}

	function redeemPrize(){
		$user = $this->apps->user;
		
		$sql = "SELECT * FROM tbl_code_inventory WHERE userid = {$user->id} AND n_status = 3";
		// pr($sql);
		$qData = $this->apps->fetch($sql,1);
		
		$masterCode = $this->getMasterCode();
		foreach($masterCode as $value){
			$mastercode[$value['id']] = $value['codename'];
			
		}
		
		$dont = array(1,2,3,4);
		$dontbe = array(1,2,3,4,5,6);
		$dontbea = array(1,2,3,4,5,6,7);
		$dontbeamaybe = array(1,2,3,4,5,6,7,8,9,10,11,12);
		
		if (!$qData) return false;
		// pr($qData);
		foreach($qData as $key => $val){
			
			if(in_array($val['codeid'],$dont)){
				$data[$val['id']] = $val['codeid'];			
				
			}
			
			if(in_array($val['codeid'],$dontbe)){
				$data2[$val['id']] = $val['codeid'];
			}
			
			if(in_array($val['codeid'],$dontbea)){
				$data3[$val['id']] = $val['codeid'];
			}
			
			if(in_array($val['codeid'],$dontbeamaybe)){
				$data4[$val['id']] = $val['codeid'];
			}
		}
		
		if (isset($data)){
			$dontArr = array_unique($data);
		}else{
			$dontArr = false;
		}
		if (isset($data2)){
			$dontbeArr = array_unique($data2);
		}else{
			$dontbeArr = false;
		}
		if (isset($data3)){
			$dontbeaArr = array_unique($data3);
		}else{
			$dontbeaArr = false;
		}
		if (isset($data4)){
			$dontbeamaybeArr = array_unique($data4);
		}else{
			$dontbeamaybeArr = false;
		}
		
		if(count($dontArr) == count($dont)){
			$readyToReedem['dont'] = 1; 
			$readyToReedem['dontData'] = $dontArr; 
		}else{
			$readyToReedem['dont'] = 0;
		}
		
		if(count($dontbeArr)==count($dontbe)){
			$readyToReedem['dontbe'] = 1; 
			$readyToReedem['dontbeData'] = $dontbeArr; 
		}else{
			$readyToReedem['dontbe'] = 0;
		}
		
		if(count($dontbeaArr)==count($dontbea)){
			$readyToReedem['dontbea'] = 1; 
			$readyToReedem['dontbeaData'] = $dontbeaArr; 
		}else{
			$readyToReedem['dontbea'] = 0;
		}
		
		if(count($dontbeamaybeArr)==count($dontbeamaybe)){
			$readyToReedem['dontbeamaybe'] = 1; 
			$readyToReedem['dontbeamaybeData'] = $dontbeamaybeArr; 
		}else{
			$readyToReedem['dontbeamaybe'] = 0;
		}
		
		return $readyToReedem;
		
	}
	
	function redeemConfirmDialog() {
		$idMerchan = intval($this->apps->_p('id')); 
		
		$sql = "SELECT id, image, detail, stock FROM  marlborohunt_merchandise WHERE id = {$idMerchan} AND stock <> 0 AND n_status = 1 LIMIT 1";
		// pr($sql);
		$res = $this->apps->fetch($sql);
		if ($res){
			return $res;
		}
		return false;
	}
	
	function checkIfAlreadyRedeemPhrase($id) {
	
		$sqlMerch = "SELECT letter
					FROM marlborohunt_merchandise WHERE id = {$id} LIMIT 1";
		$resMerch = $this->apps->fetch($sqlMerch);
		
		$sqlUser = "SELECT merchandiseid
					FROM my_merchandise WHERE userid = {$this->apps->user->id}";
		$resUser = $this->apps->fetch($sqlUser,1);
		if ($resUser){
			foreach ($resUser as $value){
				$sql = "SELECT merc.letter
						FROM my_merchandise my 
						LEFT JOIN marlborohunt_merchandise merc 
						ON my.merchandiseid = merc.id
						WHERE my.merhcandise_type = 0 AND my.userid = {$this->apps->user->id}
						LIMIT 1";
				$res = $this->apps->fetch($sql);
				if ($res['letter']) $myMerchandise[$res['letter']] = $res['letter'];
			}
			
			if (in_array($resMerch['letter'], $myMerchandise)){
				
				return true;
			}else{
				return false;
			}
		}else{
			
			return false;
		}
		
		
	}
	
	function checkUserHasRedeemThisMerchandise($idmerch = false){
			
			if($idmerch==false) return false;
			$idmerch = intval($idmerch);
			$sql = "SELECT count(*) total
					FROM my_merchandise my 				
					WHERE my.merhcandise_type = 0 AND my.userid = {$this->apps->user->id} AND merchandiseid = {$idmerch}
					LIMIT 1";
			$res = $this->apps->fetch($sql);
			
			if($res){
				if($res['total']>0) return false;
				else return true;
			}
			
			return false;
			
	}
	
	function firstcheckredeemitem($phrase=false) {
		if($phrase==false) return false;
	

			$sql = "SELECT merc.letter
					FROM my_merchandise my 
					LEFT JOIN marlborohunt_merchandise merc 
					ON my.merchandiseid = merc.id
					WHERE my.merhcandise_type = 0 AND my.userid = {$this->apps->user->id}
					LIMIT 1";
			$res = $this->apps->fetch($sql);
			if ($res['letter']) $myMerchandise[$res['letter']] = strtoupper($res['letter']);
		
			if (in_array(strtoupper($phrase), $myMerchandise)){
				
				return true;
			}
			
			return false;
	}
	
	function redeemPrizeConfirm() {
		// $getData = $this->redeemPrize();
		// pr($getData);
		$uid = intval($this->uid);
		if($uid==0)return false;
		
		$getData = $this->getMyLetterToredeem();
		
		$idMerchan = intval($this->apps->_p('id')); 
		if ($getData){
			
			if ($this->apps->_p('redeemDont')){
				$dataRedeem = @$getData['dontData'];
			}
			
			if ($this->apps->_p('redeemDontBe')){
				$dataRedeem = @$getData['dontbeData'];
			}
			
			if ($this->apps->_p('redeemDontBeA')){
				$dataRedeem = @$getData['dontbeaData'];
			}
			
			if ($this->apps->_p('redeemDontBeAMayBe')){
				$dataRedeem = @$getData['dontbeamaybeData'];
			}
			
			// pr($getData);
			// pr($dataRedeem);
			if (!$dataRedeem) return false;
			
			// if ($getData['dont']){
			
			// $checkBefore = $this->checkIfAlreadyRedeemPhrase($idMerchan);
			$checkBefore = $this->checkUserHasRedeemThisMerchandise($idMerchan);
			// pr($checkBefore);
			// exit;
			if (!$checkBefore) return false;
				$arrval = false;
				if (is_array($dataRedeem)){
					foreach ($dataRedeem as $key => $value){
						$arrval[$value] = $value;
						
					}
					
					if(!$arrval) return false;
						
					$limit = count($arrval);
					$arrstring = implode(',',$arrval);
										
					$DONT = array(1,2,3,4);
					$DONTBE = array(1,2,3,4,5,6);
					$DONTBEA = array(1,2,3,4,5,6,7);
					$DONTBEAMAYBE = array(1,2,3,4,5,6,7,8,9,10,11,12);
										
					$sql = "SELECT id, image, detail, stock,letter FROM  marlborohunt_merchandise WHERE id = {$idMerchan} AND stock <> 0 AND n_status = 1 LIMIT 1";
					// pr($sql);
					$res = $this->apps->fetch($sql);
					
					$lettervalidate = strtoupper($res['letter']);
					
					$sql = " SELECT codeid FROM tbl_code_inventory WHERE codeid IN ({$arrstring}) AND userid={$this->uid}  AND n_status = 3 LIMIT {$limit} ";

					$qData = $this->apps->fetch($sql,1);
					if(!$qData) return false;

					foreach($qData as $val){
						$comparearr[$val['codeid']] = $val['codeid'];
					}
					foreach($$lettervalidate as $val){
						if(array_key_exists($val,$comparearr)) $haveletter[$val] = true;
						else   $haveletter[$val] = false;
					} 
					
					if(in_array(false,$haveletter)) return false;
					
					
					
					$sql = "UPDATE tbl_code_inventory SET n_status = 4 WHERE codeid  IN ({$arrstring}) AND userid={$this->uid}  AND n_status = 3 LIMIT {$limit}";
					$updatecodeletter = $this->apps->query($sql);
					// sleep(1);
					$this->logger->log($sql);
					// prq($sql);
						
					
					
					$sql = "SELECT id, image, detail, stock FROM  marlborohunt_merchandise WHERE id = {$idMerchan} AND stock <> 0 AND n_status = 1 LIMIT 1";
					// pr($sql);
					$res = $this->apps->fetch($sql);
					if ($res){
						
						$stock = $res['stock'] -1;
						$sql = "UPDATE marlborohunt_merchandise SET stock = {$stock} WHERE id = {$res['id']}";
						$result = $this->apps->query($sql);
						if($result){
								$date = date('Y-m-d H:i:s');
								$sql = "INSERT INTO my_merchandise (userid, merchandiseid, redeemdate, name, address, phonenumber, email, merhcandise_type, n_status ) VALUES ({$this->apps->user->id}, {$idMerchan}, '{$date}', '{$this->apps->user->name}  {$this->apps->user->last_name}', \"{$this->apps->user->StreetName}, {$this->apps->user->barangay}\", '{$this->apps->user->phone_number}', '{$this->apps->user->email}', 0, 1)";
								$this->apps->query($sql);
						}
						$this->apps->log('redeemcode', $this->apps->user->name. " redeemed \"{$res['detail']}\" ");
						
						return $res;
					}
					
					
				}else{
					
					return false;
				}
				
				
			// }else{
			
				// return false;
			// }
			
		}else{
			
			return false;
		}
	}
	
	function redeemAjaxHelp(){
		
		$user = $this->apps->user;
		
		$sql = "UPDATE tbl_code_inventory SET n_status = 2 WHERE userid = {$user->id}";
		$result = $this->query($sql);
	}
	
	function getRedeemPhrase() {
		
			$sql = "SELECT * FROM marlborohunt_merchandise WHERE letter = 'DONT' AND stock <> 0 AND n_status = 1";
			// pr($sql);
			$res = $this->apps->fetch($sql,1);
			if ($res){
				$merchan['DONT'] = $res;
				
			}
			
		
			$sql = "SELECT * FROM marlborohunt_merchandise WHERE letter = 'DONTBE' AND stock <> 0 AND n_status = 1";
			// pr($sql);
			$res = $this->apps->fetch($sql,1);
			if ($res){
				$merchan['DONTBE'] = $res;
				
			}
			
		
			$sql = "SELECT * FROM marlborohunt_merchandise WHERE letter = 'DONTBEA' AND stock <> 0 AND n_status = 1";
			// pr($sql);
			$res = $this->apps->fetch($sql,1);
			if ($res){
				$merchan['DONTBEA'] = $res;
				
			}
			
			$sql = "SELECT * FROM marlborohunt_merchandise WHERE letter = 'DONTBEAMAYBE' AND stock <> 0 AND n_status = 1";
			// pr($sql);
			$res = $this->apps->fetch($sql,1);
			if ($res){
				$merchan['DONTBEAMAYBE'] = $res;
				
			}
			
			// pr($merchan);
			return $merchan;
		
	}
	
	function getMyLetterToredeem() {
		$sql = "SELECT my.codeid, code.codename FROM tbl_code_inventory AS my LEFT JOIN tbl_code_detail AS code ON my.codeid = code.id WHERE my.userid = {$this->apps->user->id} AND my.n_status = 3";
		
		$res = $this->apps->fetch($sql,1);
		if ($res){
			
			// pr($res);
			$no = 1;
			foreach ($res as $value){
				$data[$value['codeid']] = $value['codename'];
				$codeid[] = $value['codeid'];
				/*
				if (($no == 4) or ($no > 4)){
					// $codeidDont[] = $value['codeid'];
					$codeidDont[] = array(1,2,3,4);
					
				}
				if (($no == 6) or ($no > 6)){
					// $codeidDontbe[] = $value['codeid'];
					$codeidDont[] = array(1,2,3,4,5,6);
				}
				if (($no == 7) or ($no > 7)){
					// $codeidDontbea[] = $value['codeid'];
					$codeidDont[] = array(1,2,3,4,5,6,7);
				}
				
				if ($no = 12){
					// $codeidDontbeamaybe[] = $value['codeid'];
					$codeidDont[] = array(1,2,3,4,5,6,7,8,9,10,11,12);
				}*/
				$no++;
			}
			
			$codeidDont = array(1,2,3,4);
			$codeidDontbe = array(1,2,3,4,5,6);
			$codeidDontbea = array(1,2,3,4,5,6,7);
			$codeidDontbeamaybe = array(1,2,3,4,5,6,7,8,9,10,11,12);
			
			$count = count($codeid);
			
			if(($count == 4) or ($count > 4)){
				$youWin['dont'] = 1;
				$youWin['dontbe'] = 0;
				$youWin['dontbea'] = 0;
				$youWin['dontData'] = $codeidDont;
				
			}
			
			if(($count == 6) or ($count > 6)){
				$youWin['dont'] = 1;
				$youWin['dontData'] = $codeidDont;
				$youWin['dontbe'] = 1;
				$youWin['dontbea'] = 0;
				$youWin['dontbeData'] = $codeidDontbe;
			}
			
			if(($count == 7) or ($count > 7)){
				$youWin['dont'] = 1;
				$youWin['dontData'] = $codeidDont;
				$youWin['dontbe'] = 1;
				$youWin['dontbeData'] = $codeidDontbe;
				$youWin['dontbea'] = 1;
				$youWin['dontbeaData'] = $codeidDontbea;
			}
			
			if($count == 12){
				$youWin['dont'] = 1;
				$youWin['dontData'] = $codeidDont;
				$youWin['dontbe'] = 1;
				$youWin['dontbeData'] = $codeidDontbe;
				$youWin['dontbea'] = 1;
				$youWin['dontbeamaybe'] = 1;
				$youWin['dontbeaData'] = $codeidDontbea;
				$youWin['dontbeamaybeData'] = $codeidDontbeamaybe;
			}
			
			if (!isset($youWin)){
				$youWin['dont'] = 0;
				$youWin['dontbe'] = 0;
				$youWin['dontbea'] = 0;
				$youWin['dontbeamaybe'] = 0;
			}
			return $youWin;
			
		}
		
		return false;
	}
	
	function userhasThismerchandise($stridmerch=false){
		if($stridmerch==false) return false;
		
		$sql = "SELECT * FROM my_merchandise WHERE merhcandise_type=0 AND merchandiseid IN ({$stridmerch}) AND userid = {$this->uid} ";
		$res = $this->apps->fetch($sql,1);
		
		if($res){
			$data = false;
				foreach($res as $val){
					$data[$val['merchandiseid']] = true;
				}
			return $data;
		}	
		return false;
	}
	
	function getRedeemDont() {
		// $getData = $this->redeemPrize();
		$getData = $this->getMyLetterToredeem();
		// pr($getData);
		// pr($getData1);
		$id = intval($this->apps->_p('id'));
		$qUser = "";
		if($id!=0) $qUser = " AND id={$id} ";
		
		
		$sql = "SELECT * FROM marlborohunt_merchandise WHERE letter = 'DONT' AND stock <> 0 AND n_status = 1 {$qUser} ";
		
		// pr($sql);
		$res = $this->apps->fetch($sql);
		if ($res){
			//check user has this merchandise
			$checkhasmerchandise = $this->userhasThismerchandise($res['id']);
			$res['has'] = false;
			if($checkhasmerchandise)if(array_key_exists($res['id'],$checkhasmerchandise))$res['has'] = true;
			$merchan['DONT'] = $res;
			$merchan['validate'] = $getData['dont'];
			
		}
		
	
		// $sql = "SELECT * FROM marlborohunt_merchandise WHERE letter = 'DONTBE' AND stock <> 0 AND n_status = 1";
		$sql = "SELECT * FROM marlborohunt_merchandise WHERE letter = 'DONT' AND stock <> 0 AND n_status = 1";
		// pr($sql);
		$res = $this->apps->fetch($sql,1);
		if ($res){
			$arrmerchid = false;
			foreach($res  as $val ){
				$arrmerchid[$val['id']] = $val['id'];
			}
			$strmerchid = false;
			if($arrmerchid) $strmerchid = implode(',',$arrmerchid);
			//check user has this merchandise
			$checkhasmerchandise = $this->userhasThismerchandise($strmerchid);
			foreach($res as $key => $val ){
				$res[$key]['has'] = false;
				if($strmerchid)if($checkhasmerchandise)if(array_key_exists($val['id'],$checkhasmerchandise))$res[$key]['has'] = true;
			}
			
			$merchan['DONTBE'] = $res;
			
		}
		// pr($merchan);
		return $merchan;
	}
	
	function getRedeemDontBe() {
		// $getData = $this->redeemPrize();
		$getData = $this->getMyLetterToredeem();
		$id = intval($this->apps->_p('id'));
		$qUser = "";
		if($id!=0) $qUser = " AND id={$id} ";
		
		$sql = "SELECT * FROM marlborohunt_merchandise WHERE letter = 'DONTBE' AND stock <> 0 AND n_status = 1 {$qUser} ";
		
		// pr($sql);
		$res = $this->apps->fetch($sql,1);
		if ($res){
				//check user has this merchandise
			
			foreach($res as $key => $val ){
				$idMerchan[] = $val['id'];
			}
			
			$idMerchandise = implode(',',$idMerchan);
			$checkhasmerchandise = $this->userhasThismerchandise($idMerchandise);
			// pr($value['id']);
			$res['has'] = false;
			if($checkhasmerchandise){
				foreach($res as $value ){
					if(array_key_exists($value['id'],$checkhasmerchandise))$res['has'] = true;
				}
				
			}
			$merchan['DONTBE'] = $res;
			$merchan['validate'] = $getData['dontbe'];
				
			
			// pr($res);
			// pr($res['id']);
			
			// $res['has'] = false;
			// if($checkhasmerchandise)if(array_key_exists($res['id'],$checkhasmerchandise))$res['has'] = true;
			// $merchan['DONTBE'] = $res;
			// $merchan['validate'] = $getData['dontbe'];
			
		}
	
		// $sql = "SELECT * FROM marlborohunt_merchandise WHERE letter = 'DONTBEA' AND stock <> 0 AND n_status = 1";
		$sql = "SELECT * FROM marlborohunt_merchandise WHERE letter = 'DONTBE' AND stock <> 0 AND n_status = 1";
		// pr($sql);
		$res = $this->apps->fetch($sql,1);
		if ($res){
			$arrmerchid = false;
			foreach($res  as $val ){
						$arrmerchid[$val['id']] = $val['id'];
			}
			$strmerchid = false;
			if($arrmerchid) $strmerchid = implode(',',$arrmerchid);
			//check user has this merchandise
			$checkhasmerchandise = $this->userhasThismerchandise($strmerchid);
			foreach($res as $key => $val ){
				$res[$key]['has'] = false;
				if($strmerchid)if($checkhasmerchandise)if(array_key_exists($val['id'],$checkhasmerchandise))$res[$key]['has'] = true;
			}
			
			$merchan['DONTBEA'] = $res;
			
		}
		// pr($merchan);
		return $merchan;
	}
	
	function getRedeemDontBeA() {
		// $getData = $this->redeemPrize();
		$getData = $this->getMyLetterToredeem();
		
		$id = intval($this->apps->_p('id'));
		$qUser = "";
		if($id!=0) $qUser = " AND id={$id} ";
		
		$sql = "SELECT * FROM marlborohunt_merchandise WHERE letter = 'DONTBEA' AND stock <> 0 AND n_status = 1 {$qUser} ";
		
		// pr($getData);
		$res = $this->apps->fetch($sql,1);
		if ($res){
			//check user has this merchandise
			
			foreach($res as $key => $val ){
				$idMerchan[] = $val['id'];
			}
			
			$idMerchandise = implode(',',$idMerchan);
			$checkhasmerchandise = $this->userhasThismerchandise($idMerchandise);
			// pr($value['id']);
			$res['has'] = false;
			if($checkhasmerchandise){
				foreach($res as $value ){
					if(array_key_exists($value['id'],$checkhasmerchandise))$res['has'] = true;
				}
				
			}
			$merchan['DONTBEA'] = $res;
			$merchan['validate'] = $getData['dontbea'];
		}
	
		// $sql = "SELECT * FROM marlborohunt_merchandise WHERE letter = 'DONTBEAMAYBE' AND stock <> 0 AND n_status = 1";
		$sql = "SELECT * FROM marlborohunt_merchandise WHERE letter = 'DONTBEA' AND stock <> 0 AND n_status = 1";
		// pr($sql);
		$res = $this->apps->fetch($sql,1);
		if ($res){
			$arrmerchid = false;
			foreach($res  as $val ){
						$arrmerchid[$val['id']] = $val['id'];
			}
			$strmerchid = false;
			if($arrmerchid) $strmerchid = implode(',',$arrmerchid);
			//check user has this merchandise
			$checkhasmerchandise = $this->userhasThismerchandise($strmerchid);
			foreach($res as $key => $val ){
				$res[$key]['has'] = false;
				if($strmerchid)if($checkhasmerchandise)if(array_key_exists($val['id'],$checkhasmerchandise))$res[$key]['has'] = true;
			}
			
			$merchan['DONTBEAMAYBE'] = $res;
			
		}else{
			$merchan['DONTBEAMAYBE'] = false;
		}
		// pr($merchan);
		return $merchan;
	}
	
	function getRedeemDontBeAMaybe() {
		// $getData = $this->redeemPrize();
		$getData = $this->getMyLetterToredeem();
		
		$id = intval($this->apps->_p('id'));
		$qUser = "";
		if($id!=0) $qUser = " AND id={$id} ";
		
		$sql = "SELECT * FROM marlborohunt_merchandise WHERE letter = 'DONTBEAMAYBE' AND stock <> 0 AND n_status = 1 {$qUser} ";
		
		// pr($getData);
		$res = $this->apps->fetch($sql,1);
		if ($res){
			//check user has this merchandise
			
			foreach($res as $key => $val ){
				$idMerchan[] = $val['id'];
			}
			
			$idMerchandise = implode(',',$idMerchan);
			$checkhasmerchandise = $this->userhasThismerchandise($idMerchandise);
			// pr($value['id']);
			$res['has'] = false;
			if($checkhasmerchandise){
				foreach($res as $value ){
					if(array_key_exists($value['id'],$checkhasmerchandise))$res['has'] = true;
				}
				
			}
			$merchan['DONTBEAMAYBE'] = $res;
			$merchan['validate'] = $getData['dontbeamaybe'];
			
		}
		
	
		// $sql = "SELECT * FROM marlborohunt_merchandise WHERE letter = 'DONTBEAMAYBE' AND stock <> 0 AND n_status = 1";
		$sql = "SELECT * FROM marlborohunt_merchandise WHERE letter = 'DONTBEAMAYBE' AND stock <> 0 AND n_status = 1";
		// pr($sql);
		$res = $this->apps->fetch($sql,1);
		if ($res){
			$arrmerchid = false;
			foreach($res  as $val ){
						$arrmerchid[$val['id']] = $val['id'];
			}
			$strmerchid = false;
			if($arrmerchid) $strmerchid = implode(',',$arrmerchid);
			//check user has this merchandise
			$checkhasmerchandise = $this->userhasThismerchandise($strmerchid);
			foreach($res as $key => $val ){
				$res[$key]['has'] = false;
				if($strmerchid)if($checkhasmerchandise)if(array_key_exists($val['id'],$checkhasmerchandise))$res[$key]['has'] = true;
			}
			
			$merchan['COMPLETE'] = $res;
			
		}else{
			$merchan['COMPLETE'] = false;
		}
		// pr($merchan);
		return $merchan;
	}
	
	function setMyLetter() {
		$codeID = intval($this->apps->_p('id'));
		
		// cek code before update n_status 
		
		$sql = "SELECT COUNT(codeid) AS total FROM tbl_code_inventory WHERE n_status = 3 AND userid = {$this->apps->user->id} AND codeid = {$codeID}";
		$result = $this->apps->fetch($sql);
		if ($result){
			if ($result['total']>0){
				return false;
			}else{
				$sql = "UPDATE tbl_code_inventory SET n_status = 3 WHERE codeid = {$codeID} AND userid = {$this->apps->user->id} AND n_status NOT IN (1,2,4) LIMIT 1";
				$res = $this->apps->query($sql);
				if ($res){
					return true;
				}
				
				return false;
			}
		}
	}
	
	function isuserjoinpursuit() {
		$sql = "SELECT COUNT(id) AS jumlah FROM tbl_activity_log WHERE user_id = {$this->apps->user->id} AND action_id = 40 LIMIT 1";
		$res = $this->apps->fetch($sql);
		// pr($res);
		if ($res['jumlah']){
			return true;
		}
		return false;
	}
	
	function completetask($taskID=false){
		if(!$taskID)$taskID = intval($this->apps->_p('taskid'));
		
		$date = date('Y-m-d H:i:s');
		$sql = "INSERT INTO my_task (userid, taskid, taskdate, n_status)
				VALUES ({$this->uid}, {$taskID}, '{$date}', 1)";
		// pr($sql);exit;
		$hasil = $this->apps->query($sql);
		$this->logger->log($sql);
		return true;
	}
	
	function uncompletetask($taskID=false){
		if(!$taskID)$taskID = intval($this->apps->_p('taskid'));
		
		$date = date('Y-m-d H:i:s');
		$sql = "INSERT INTO my_task (userid, taskid, taskdate, n_status)
				VALUES ({$this->uid}, {$taskID}, '{$date}', 0)";
		// pr($sql);
		$hasil = $this->apps->query($sql);
		// $this->logger->log($sql);
		return true;
	}
	
	function getUserProfile() {
		$sql = "SELECT * FROM social_member WHERE id = {$this->apps->user->id} LIMIT 1";
		$res = $this->apps->fetch($sql);
		if ($res) return $res;
		return false;
	}
	
	function getMovefwd($id=false) {
	
		if ($id) $filter = " AND nc.id = {$id}";
		else $filter = "";
		
 		$sql = "
		SELECT nc.*,IF(nc.categoryid=0,'Current',cate.category) categoryname
		FROM {$this->dbshema}_news_content nc
		LEFT JOIN {$this->dbshema}_news_content_category cate ON cate.id=nc.categoryid
		WHERE nc.n_status IN (1,0) 
		AND nc.articleType = 30 
		{$filter} 
		ORDER BY nc.n_status DESC ";
		// pr($sql);
		$res = $this->apps->fetch($sql,1);
			// pr($res);
		if ($res) {
			
			
			return $res;
		}
		return false;
	}

	function getLatestGallery($ctype=null){
		if($ctype){
			$sql="SELECT * FROM my_post
				WHERE n_status = 1
				AND content_id = {$ctype}
				ORDER BY datetime DESC LIMIT 1";

			$res = $this->apps->fetch($sql);
				//pr($res);exit;
			if ($res) {
				
				
				return $res;
			}
			return false;
		}
		
		return false;
	}
	
	function getMyPost($content_id=false)
	{
		if ($content_id) $filter = " AND p.content_id = {$content_id}";
		else $filter = "";
		
		$sql = "SELECT p.*, sm.id user_id, sm.name, if (sm.photo_moderation=1, sm.image_profile, 'default.jpg') image_profile FROM my_post p LEFT JOIN social_member sm 
				ON p.userid = sm.id WHERE p.n_status = 1 AND p.parent IS NULL {$filter} ORDER BY p.datetime DESC";
		// pr($sql);exit;
		$res = $this->apps->fetch($sql,1);
		if ($res){
			
			
			foreach ($res as $key => $value){
				
				list($date, $time) = explode(' ',$value['datetime']);
				$explDate = explode('-',$date);
				$currentDateTime = $value['datetime'];
				$res[$key]['newDate'] = $explDate[1].'/'.$explDate[2].'/'.$explDate[0];
				$res[$key]['newTime'] = date('h:i A', strtotime($currentDateTime));
				
				
				
				$sqlsub = "SELECT p.*, sm.id user_id, sm.name, if (sm.photo_moderation=1, sm.image_profile, 'default.jpg') image_profile FROM my_post p LEFT JOIN social_member sm
							ON p.userid = sm.id WHERE p.n_status = 1 AND p.parent = {$value['id']} ORDER BY p.datetime DESC";
				// pr($sqlsub);
				$ressub = $this->apps->fetch($sqlsub,1);
				if ($ressub){
					$subcomment = array();
					
					foreach ($ressub as $keys => $val){
					
					
						list($date, $time) = explode(' ',$val['datetime']);
						$explDate = explode('-',$date);
						$currentDateTime = $val['datetime'];
						$ressub[$keys]['newDate'] = $explDate[1].'/'.$explDate[2].'/'.$explDate[0];
						$ressub[$keys]['newTime'] = date('h:i A', strtotime($currentDateTime));
						
						
						
						
						$sqlsubsub = "SELECT p.*, sm.id user_id, sm.name, if (sm.photo_moderation=1, sm.image_profile, 'default.jpg') image_profile FROM my_post p LEFT JOIN social_member sm
									ON p.userid = sm.id WHERE p.n_status = 1 AND p.parent = {$val['id']} ORDER BY p.datetime DESC";
						// pr($sqlsubsub);
						$ressubsub = $this->apps->fetch($sqlsubsub,1);
						
						
						
						if ($ressubsub){
						
							foreach ($ressubsub as $k => $values){
							
								list($date, $time) = explode(' ',$values['datetime']);
								$explDate = explode('-',$date);
								$currentDateTime = $values['datetime'];
								$ressubsub[$k]['newDate'] = $explDate[1].'/'.$explDate[2].'/'.$explDate[0];
								$ressubsub[$k]['newTime'] = date('h:i A', strtotime($currentDateTime));
							
							}
							
							
							$subcomment = $ressubsub;
							$ressub[$keys]['reply'] = $subcomment;
							
						}
						
						$res[$key]['comment'] = $ressub;
						// $res[$key]['comment'][$keys]['reply'] = $subcomment;
						
					}
					
				}
				
			}
			
			// pr($res);exit;
			return $res;
		}
		
		return false;
	}
	
	function getMoveFwdCommand($id=false)
	{
		if (!$id) return false;
		
		$sqlsub = "SELECT p.*, sm.name, sm.image_profile FROM my_post p LEFT JOIN social_member sm
							ON p.userid = sm.id WHERE p.n_status = 1 AND p.parent = {$id} ORDER BY p.datetime";
		// pr($sqlsub);	
		$ressub = $this->apps->fetch($sqlsub,1);
		if ($ressub){
			
			foreach ($ressub as $key => $value){
				list($date, $time) = explode(' ',$value['datetime']);
				$explDate = explode('-',$date);
				$currentDateTime = $value['datetime'];
				$ressub[$key]['newDate'] = $explDate[2].'/'.$explDate[1].'/'.$explDate[0];
				$ressub[$key]['newTime'] = date('h:i A', strtotime($currentDateTime));
				
				$sqlsubcomment = "SELECT p.*, sm.name, sm.image_profile FROM my_post p LEFT JOIN social_member sm
									ON p.userid = sm.id WHERE p.n_status = 1 AND p.parent = {$value['id']} ORDER BY p.datetime";
				// pr($sqlsub);
				$ressubcomment = $this->apps->fetch($sqlsubcomment,1);
				if ($ressubcomment){
					
					foreach ($ressubcomment as $keys => $val){
						list($date, $time) = explode(' ',$val['datetime']);
						$explDate = explode('-',$date);
						$currentDateTime = $val['datetime'];
						$ressubcomment[$key]['newDate'] = $explDate[2].'/'.$explDate[1].'/'.$explDate[0];
						$ressubcomment[$key]['newTime'] = date('h:i A', strtotime($currentDateTime));
						
					}
				}
				
				$ressub[$key]['reply'] = $ressubcomment;
			}
			
			// pr($ressub);
			return $ressub;
		}
		
		return false;
	}
	
	function saveStatusMoveFwd($data=false)
	{
		
		$field = false;
		
		$postType = intval($this->apps->_p('post_type'));
		$mystatus = strip_tags($this->apps->_p('mystatus'));
		$content_id = intval($this->apps->_p('content_id'));
		
		if (!$content_id) return false;
		
		
		if ($data){
			
			if($data['filename']['filename']) $blobdata = $data['filename']['filename'];
			else $blobdata = "";
			
			if (isset($data['type'])){
				if ($data['type']==1){
					$field[] = "image";
					$value[] = "'{$blobdata}'";
				}else{
					$field[] = "video";
					$value[] = "'{$blobdata}'";
				}
			}
			
		}
		
		$date = date('Y-m-d H:i:s');
		
		$field[] = "userid";
		$field[] = "post_type";
		$field[] = "content_id";
		$field[] = "content";
		$field[] = "datetime";
		$field[] = "n_status";
		
		$value[] = "{$this->apps->user->id}";
		$value[] = "{$postType}";
		$value[] = "{$content_id}";
		$value[] = "'{$mystatus}'";
		$value[] = "'{$date}'";
		$value[] = 0;
		
		$arrField = implode(',',$field);
		$arrValue = implode(',',$value);
		
		$sql = "INSERT INTO my_post ({$arrField}) 
				VALUES ({$arrValue})";
		// pr($sql);
		// exit;
		$res = $this->apps->query($sql);
		if ($res) return true;
		return false;
	}
	
	function isPostMoveFwdExist($id=false)
	{
		if (!$id) return false;
		
		$sql = "SELECT id FROM {$this->dbshema}_news_content WHERE n_status IN (1,0) AND articleType = 30 AND id = {$id} LIMIT 1";
		$res = $this->apps->fetch($sql);
		if ($res['id']) return true;
		return false;
	}
	
	function saveMoveFwdCommand()
	{
		
		$parent = intval($this->apps->_p('postid'));
		$content_id = intval($this->apps->_p('contentid'));
		$content = strip_tags($this->apps->_p('content'));
		$date = date('Y-m-d H:i:s');
		
		$field[] = "userid";
		$field[] = "parent";
		$field[] = "post_type";
		$field[] = "content_id";
		$field[] = "content";
		$field[] = "datetime";
		$field[] = "n_status";
		
		$value[] = "{$this->apps->user->id}";
		$value[] = "{$parent}";
		$value[] = "1";
		$value[] = "{$content_id}";
		$value[] = "'{$content}'";
		$value[] = "'{$date}'";
		$value[] = 0;
		
		$arrField = implode(',',$field);
		$arrValue = implode(',',$value);
		
		$sql = "INSERT INTO my_post ({$arrField}) 
				VALUES ({$arrValue})";
		// pr($sql);
		$res = $this->apps->query($sql);
		if ($res) return true;
		return false;
	}
	
	
	
	function getLastParticipant($cid,$start,$limit)
	{
		
		$data = array();
		$sql = "SELECT categoryid FROM {$this->dbshema}_news_content where id = {$cid} and n_status = 1";
		$res = $this->apps->fetch($sql);
		if ($res){
			
			$sql = "SELECT id FROM {$this->dbshema}_news_content where categoryid = {$res['categoryid']} and n_status IN (0,1)";
			$result = $this->apps->fetch($sql,1);
			// id = 1, id = 2
			if ($result){
				foreach ($result as $val){
					$bucketidtmp[] = $val['id'];
				}
				
				$bucketid = implode(',',$bucketidtmp);
			}
			// pr($bucketid);
			if (empty($bucketid)) $bucketid = $cid;
			
			$qStatus = "SELECT id FROM my_bucket WHERE bucketid IN ({$bucketid}) AND n_status = 1";
			$rStatus = $this->apps->fetch($qStatus,1);
			if ($rStatus){
				foreach ($rStatus as $value){
					$bucketidtmpfix[] = $value['id'];
				}
				
				$bucketidfix = implode(',',$bucketidtmpfix);
			}
			
			if (empty($bucketidfix)) $bucketidfix = $cid;
			
			$countBucket = "SELECT COUNT( total ) total
							FROM (
							SELECT COUNT( * ) total
							FROM my_bucket
							WHERE id IN ({$bucketidfix})
							AND n_status = 1
							GROUP BY userid
							)a";
			$total = $this->apps->fetch($countBucket);
			$data['total'] = $total['total'];
			// pr($data);
			if ($data['total']>0){
				
				$sql = "SELECT DISTINCT(mb.userid) FROM my_bucket mb LEFT JOIN {$this->dbshema}_news_content_repo cr ON mb.id = cr.otherid where mb.n_status = 1 AND cr.n_status = 1 AND mb.id IN ({$bucketidfix}) LIMIT {$start}, {$limit}";
				// pr($sql);
				$result = $this->apps->fetch($sql,1);
				// pr($sql);
				
				if ($result){
					foreach ($result as $val){
						$usertmp[] = $val['userid'];
					}
				}
				
				$userid = implode(',',$usertmp);
				
				$master = "SELECT id, name, email, if (photo_moderation=1, image_profile, 'default.jpg') image_profile FROM social_member WHERE id IN ({$userid})";
				
				// pr($master);
				$data['res'] = $this->apps->fetch($master,1);
			}
			
			$data['total'] = $total;
			return $data;
		}
	}
	
	function listParticipant($cid=null,$all=false,$start=null,$limit=2)
	{
		
		if($start==null)$start = intval($this->apps->_request('start'));
		if (!$cid) return false;
		
		/* request prepare 
		*/
		$data = $this->getLastParticipant($cid,$start, $limit);
		
		if ($data)return $data;
		return false;
		exit;
		
		$sqlTotal = "SELECT COUNT( total ) total
					FROM (
					SELECT COUNT( * ) total
					FROM my_bucket
					WHERE bucketid = {$cid}
					GROUP BY userid
					)a";
		// pr($sqlTotal);exit;
		$data['total'] = $this->apps->fetch($sqlTotal);
		// pr($data);exit;
		if ($data['total']>0){
			// $sql = "SELECT id, name, email, if (photo_moderation=1, image_profile, 'default.jpg') image_profile FROM social_member WHERE id IN (  SELECT DISTINCT(userid) FROM my_post WHERE content_id = {$cid} ORDER BY id ASC ) LIMIT {$start}, {$limit}";
			// pr($sql);exit;
			
			$cid = 32;
			$sql = "SELECT DISTINCT(mb.userid) FROM my_bucket mb LEFT JOIN {$this->dbshema}_news_content_repo cr ON mb.id = cr.otherid where mb.n_status = 1 AND cr.n_status = 1 AND mb.bucketid = {$cid} LIMIT {$start}, {$limit}";
			
			$result = $this->apps->fetch($sql,1);
			// pr($sql);
			
			if ($result){
				foreach ($result as $val){
					$usertmp[] = $val['userid'];
				}
			}
			
			$userid = implode(',',$usertmp);
			
			$master = "SELECT id, name, email, if (photo_moderation=1, image_profile, 'default.jpg') image_profile FROM social_member WHERE id IN ({$userid})";
			
			// pr($master);
			$data['res'] = $this->apps->fetch($master,1);
			if ($data['res'])return $data;
		}
		
		return false;
	}
	
	
	function getPrize()
	{
		$data = false;
		$sql = "SELECT * FROM {$this->dbshema}_merchandise WHERE category_id = 0 AND n_status = 1";
		$res = $this->apps->fetch($sql,1);
		if ($res){
			// pr($res);
			
			foreach ($res as $value){
				$sql = "SELECT * FROM {$this->dbshema}_merchandise WHERE category_id = {$value['id']} AND n_status = 1";
				$result = $this->apps->fetch($sql,1);
				if ($result){
					$data['category'][$value['id']] = $value;
					$data['list'][$value['id']] = $result;
				}
			}
			
			return $data;
		}
		return false;
		
	}
	
	function getEventDesc($id=0)
	{
		if ($id==0) $id = intval($this->apps->_p('eventID'));
		
		if ($id !==0){
			$sql = "SELECT id, title FROM {$this->dbshema}_news_content WHERE id = {$id} AND articleType = {$this->eventType}";
		}else{
			$sql = "SELECT id, title FROM {$this->dbshema}_news_content WHERE articleType = {$this->eventType}
					ORDER BY posted_date DESC LIMIT 1";
		}
		
		$res = $this->apps->fetch($sql);
		if ($res) return $res;
		return false;
	}
	
	function saveDataSubmission($file=null)
	{
	
		if (!$file) return false;
		
		$date = date('Y-m-d H:i:s');
		$userid = intval($this->uid);
		$files = $file['filename'];
		$title = $file['title'];
		$typealbum = $file['typealbum'];
		$contentid = $file['contentid'];
		
		$sql = "INSERT INTO {$this->dbshema}_news_content_repo (title, typealbum, gallerytype,files, fromwho, otherid, userid,	created_date, n_status) VAlUES ('{$title}', {$typealbum}, 1, '{$files}', 1, {$contentid}, {$userid}, '{$date}', 2)";
		$res = $this->apps->query($sql);
		if ($res) return true;
		return false;
	}
	
	function challengeTaskComplete($bucketid=false)
	{
		
		if (!$bucketid) return false;
		
		$sql = "SELECT * FROM my_bucket WHERE userid = {$this->apps->user->id} AND bucketid = {$bucketid}
				AND dateverified <> '0000-00-00' AND n_status = 1 ORDER BY id";
		// pr($sql);
		$res = $this->apps->fetch($sql,1);
		if ($res){
		
			return $res;
		}
		
		return false;
		
	}
	
	function isCompleteChallenge($bucketid)
	{
		
		$sql = "SELECT id FROM  question WHERE question_type= 4 AND otherid = {$bucketid} AND n_status = 1";
		$res = $this->apps->fetch($sql,1);
		
		$sqlUser = "SELECT taskid,dochallenge, point FROM my_bucket 
					WHERE userid = {$this->apps->user->id} AND bucketid = {$bucketid}
					AND dateverified <> '0000-00-00' AND n_status = 1";
		$resUser = $this->apps->fetch($sqlUser,1);		
		if ($res){
			
			foreach ($res as $val){
				$taskID[] = $val['id'];
				
			}
			
			$dochallenge = array();
			if ($resUser){
				foreach ($resUser as $val){
					$taskUser[] = $val['taskid'];
					if ($val['dochallenge']>0 and $val['point']>0){
						$dochallenge[] = true;
					}
				}
				
				// pr($dochallenge);
				if (count($dochallenge)>0) return true;
				$count = count(array_diff($taskID, $taskUser)) === 0;
				if($count) return true;
			}
			
		
		}
		
		
		
		return false;
	}
	
	function challengeCount($id)
	{
		
		global $CONFIG;
		
		if (!$id) return false;
		$multitask = array();
		$singletask = array();
		
		$bucket = "SELECT userid, count(userid) total FROM my_bucket WHERE bucketid = {$id} 
					AND dochallenge = 0 AND n_status = 1 GROUP BY userid";
		$resbucket = $this->apps->fetch($bucket,1);
		// pr($resbucket);
		if ($resbucket){
			
			foreach ($resbucket as $val){
				if ($val['total']>=$CONFIG['BUCKETLISTTASK']){
					$multitask[] = $val['userid'];
				}
			}
			
		}
		
		$sql = "SELECT userid, count(userid) total FROM my_bucket WHERE bucketid = {$id} 
					AND dochallenge = 1 AND n_status = 1 AND point > 0 GROUP BY userid";
		$res = $this->apps->fetch($sql,1);
		if ($res){
			
			foreach ($res as $val){
				if ($val['total']>=0){
					$singletask[] = $val['userid'];
				}
			}
			
		}
		
		$countChallenge = intval(count($multitask)) + intval(count($singletask)) ;
		if (intval($countChallenge) >= intval($this->challengeLimit)){
			
			return array('status'=>true);
		}else{
			$leftCount = $this->challengeLimit - $countChallenge;
			return array('rec'=>$leftCount, 'status'=>false);
			
		}
		
		
	}
	
	function getChallengeTask($cid=false)
	{
		if (!$cid) return false;
		
		$sql = "SELECT q.id, q.question, q.other_type, con.id bucketid, con.title, con.point FROM question q
				LEFT JOIN {$this->dbshema}_news_content con ON q.otherid = con.id
				WHERE q.question_type = 4 
				AND q.otherid = {$cid} AND q.n_status = 1 ORDER BY q.id";
		// pr($sql);
		$res = $this->apps->fetch($sql,1);
		
		if ($res){
			
			
			foreach ($res as $key => $value){
				$taskid[] = $value['id'];
				
			}
			
			
			$getCompleteTask = $this->challengeTaskComplete($cid);
			// pr($getCompleteTask);
			$uncompleteTask = array();
			$completeTaskTmp = array();
			$pledge = false;
			
			if ($getCompleteTask){
				
				foreach ($getCompleteTask as $value){
					$completetaskid[] = $value['taskid'];
					
					if ($value['dochallenge']==1 and $value['point']>0){
						$pledge = true;
					}
				}
				
				if ($completetaskid){
				
					foreach ($taskid as $val){
						
						if (!in_array($val, $completetaskid)){
							$uncompleteTaskTmp[] = $val;
						}
						
						if (in_array($val, $completetaskid)){
							$completeTaskTmp[] = $val;
						}
						
					}
					
					if (isset($uncompleteTaskTmp))$uncompleteTask = $uncompleteTaskTmp[0];
						
				}else{
					$uncompleteTask = $taskid[0];
					
				}
			}else{
				$uncompleteTask = $taskid[0];
			}
			
			
			// pr($completeTaskTmp);
			foreach ($res as $key => $value){
				

				if (in_array($value['id'], $completeTaskTmp)){
					$res[$key]['donetask'] = true;
				}
				
				if ($value['id'] == $uncompleteTask){
				
					$pending = $this->isUserExistTask($value['id']);
					if ($pending){
						if ($pending['n_status']==0){
							$res[$key]['opentask'] = true;
							$res[$key]['is_pending'] = true;
							
						}
					}else{
						$res[$key]['opentask'] = true;
					}
					
				}else{
					$res[$key]['opentask'] = false;
				}
				
			}
			
			$data['title'] = $res[0]['title'];
			$data['id'] = $res[0]['bucketid'];
			$data['taskid'] = $uncompleteTask;
			$data['point'] = $res[0]['point'];
			$data['task'] = $res;
			$data['pledge'] = $pledge;
			
			// pr($data);
			// exit;
			return $data;
		}
		return false;
	}
	
	function saveChallenge($bucketid=false,$taskid=false, $files=false,$typealbum=0, $dochallenge=0,$point=0)
	{
		
		if (!$bucketid) return false;
		
		$date = date('Y-m-d H:i:s');
		
		if($dochallenge==0){
			
			if (!$taskid) return false;
			if (!$files) return false;
			
			$dateverified = "";
			$n_status = 0;
			
		}else{
			$taskid = 0;
			$n_status = 1;
			$dateverified = $date;
		}
		
		
		
		$bucketid = intval($bucketid);
		$taskid = intval($taskid);
		
		$embedLink = null;
		if ($this->apps->_p('embedLink'))$embedLink = strip_tags($this->apps->_p('embedLink'));
		
		$sql = "INSERT INTO  my_bucket (userid, bucketid, taskid, datetime, dateverified, dochallenge, point, n_status) 
				VALUES ({$this->apps->user->id}, {$bucketid}, {$taskid}, '{$date}', '{$dateverified}', {$dochallenge}, {$point}, {$n_status})";
		// pr($sql);
		$res = $this->apps->query($sql);
		if ($res){
			
			if($dochallenge==0){
			
				$challengeId = $this->apps->getLastInsertId();
				$sql = "SELECT title, content FROM {$this->dbshema}_news_content WHERE id = {$bucketid} LIMIT 1";
				// pr($sql);
				$result = $this->apps->fetch($sql);
				if ($result){
				
					$sql = "INSERT INTO  {$this->dbshema}_news_content_repo (title, content, typealbum, gallerytype, files, userid, otherid, created_date, link, n_status) 
							VALUES ('Bucket List', '{$result['title']}', {$typealbum}, 2, '{$files}', {$this->apps->user->id}, {$challengeId}, '{$date}', '{$embedLink}', 1)";
					// pr($sql);
					$res = $this->apps->query($sql);
					if ($res) return true;
				}
				return false;
			}
			
			
			return true;
			
			
		}
		
		return false;
	}
	
	
	
	function getTaskList($bucketid=false)
	{
		if (!$bucketid) return false;
		
		$sql = "SELECT * FROM question where otherid = {$bucketid} and n_status =1";
		// pr($sql);
		$res = $this->apps->fetch($sql,1);
		if ($res){
		
			return $res;
		}
		
		return false;
	}
	
	
	function allChallengeComplete($userid=false)
	{
		
		if (!$userid) return false;
		
		$movefwd = $this->getMovefwd();
		
		if ($movefwd){
			foreach ($movefwd as $key => $value){
				
				$taskForthis = $this->getTaskList($value['id']);
				
				$isComplete = $this->challengeTaskComplete($value['id'], $userid);
				// pr($isComplete);
				if ($isComplete){
				
					if ($isComplete[0]['dochallenge']==1 and $isComplete[0]['point']>0){
						$movefwd[$key]['complete'] = true;
						
					}else{
						if (intval(count($isComplete)) == intval(count($taskForthis))){
							$movefwd[$key]['complete'] = true;
						}else{
							$movefwd[$key]['complete'] = false;
						}
					}
					
				}else{
					$movefwd[$key]['complete'] = false;
				}
			
				
			}
			// pr($movefwd);exit;
			foreach ($movefwd as $value){
				
				if ($value['complete']){
					$completeAll[] = $value['id'];
				}
			}
			
			// complete all challenge 
			if (count($completeAll) == count($movefwd)) return true;
			
			return false;
		}
		
		
		return false;
		
	}
	
	function challengePoint($bucketid=false)
	{
		if (!$bucketid) return false;
		 
		$sql = "SELECT title, point FROM {$this->dbshema}_news_content WHERE id ={$bucketid} LIMIT 1";
		$result = $this->apps->fetch($sql);
		if ($result){
			
			return $result;
		}
		
		return false;
	}
	
	function getMoveForwardGallery($bucketid=false){
		
		if (!$bucketid) return false;
		$sql = "SELECT id, title, content, files, userid FROM marlborohunt_news_content_repo
				WHERE gallerytype = {$this->bucketType} AND n_status = 2";
		$result = $this->apps->fetch($sql);
		if ($result)return $result;
		return false;
	}
	
	
	function isExistChallenge($bucketid=false)
	{
		
		
		if (!$bucketid) return false;
		
		
		
		$sqlUser = "SELECT taskid,dochallenge, point FROM my_bucket 
					WHERE userid = {$this->apps->user->id} AND bucketid = {$bucketid}
					AND n_status = 0 LIMIT 1";
		// pr($sqlUser);
		$resUser = $this->apps->fetch($sqlUser);		
		if ($resUser){
			if ($resUser['taskid']) return true;
		}else{
			return false;
		}
	}
	
	
	function isUserExistTask($taskid=false)
	{
	
		if (!$taskid) return false;
		
		$sqlUser = "SELECT * FROM my_bucket 
					WHERE userid = {$this->apps->user->id} AND taskid = {$taskid}
					AND n_status IN (0,1)";
		// pr($sqlUser);
		$resUser = $this->apps->fetch($sqlUser);		
		if ($resUser){
			return $resUser;
		}else{
			return false;
		}
	}
	
	function isUserExistChallenge($bucketid=false)
	{
	
		if (!$bucketid) return false;
		
		$sqlUser = "SELECT * FROM my_bucket 
					WHERE userid = {$this->apps->user->id} AND bucketid = {$bucketid}
					AND n_status IN (0,1)";
		// pr($sqlUser);
		$resUser = $this->apps->fetch($sqlUser);		
		if ($resUser){
			return $resUser;
		}else{
			return false;
		}
	}
	
	function searchnameofalluser(){
		$keywords = strip_tags($this->apps->_p('q'));
		$sql = "
					SELECT name,last_name,img ,id,email
					FROM social_member 
					WHERE 
					n_status = 1 
					AND 
					(
					name like '{$keywords}%'
					AND 
					last_name like '%{$keywords}%'
					)	 
					ORDER BY name ASC, last_name ASC
					LIMIT 10";
		// pr($sql);
		$qData = $this->apps->fetch($sql,1);		
		if ($qData){
			return $qData;
		}else{
			return false;
		}
	}
	
	function checkiftaskexist($bucketid, $taskid)
	{
		
		if (!$bucketid) return false;
		if (!$taskid) return false;
		
		$sql = "SELECT count(*) total FROM my_bucket where userid = {$this->apps->user->id}
				AND bucketid = {$bucketid} AND taskid = {$taskid} AND n_status IN (0,1) ";
		$res = $this->apps->fetch($sql);	
		if ($res['total']>0) return true;
		return false;
	}
	
	function getVideoEmbededUrl($val=null){

		if($val==null) return false;
		if(!is_array($val)) return false;
		$video_thumbnail = false;
		$link = false;
		
		if($val['url']!='') {
			//parser url and get param data
			$parseUrl = parse_url($val['url']);
			// pr($parseUrl);

			if(array_key_exists('host',$parseUrl)) {
				if(strtolower($parseUrl['host'])!="youtu.be") $link = true;
			}
			if($link){
				if(array_key_exists('query',$parseUrl)) parse_str($parseUrl['query'],$parseQuery);
				else $parseQuery = false;
				if($parseQuery) {
					if(array_key_exists('v',$parseQuery))$video_thumbnail = $parseQuery['v'];
				} 
			}else{
				if(array_key_exists('path',$parseUrl)){
					$arrVideoUrl = explode("/",$parseUrl['path']);
					if(is_array($arrVideoUrl)){
					$video_thumbnail = $arrVideoUrl[1];
					}
				}
			} 
		} 

		return $video_thumbnail;
	}
	
}
?>