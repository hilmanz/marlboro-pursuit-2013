<?php 
class activityHelper {

	var $uid;
	var $osDetect;
	
	function __construct($apps){
		global $logger,$CONFIG;
		$this->logger = $logger;

		$this->apps = $apps;
		if($this->apps->isUserOnline())  {
			if(is_object($this->apps->user)) $this->uid = intval($this->apps->user->id);
			
		}		
		$this->lid = 1;
		$this->server = intval($CONFIG['VIEW_ON']);

		$this->schemadb = "marlborohunt";
		$this->feedType = '22,55';
		
	}
	
	
	
	function getA360Pagesactivity($start=0,$limit=2,$user=false){
		
		$bandMember = $this->apps->bandHelper->getMember();
		
		$qUser = "";
		if($bandMember) {
			foreach ($bandMember['result'] as $k => $v) {
				$arrMemberId[$v['id']] = $v['id'];
			}
			$strMemberId = implode(',',$arrMemberId);
			$qUser = " AND user_id IN ({$strMemberId}) ";
		} else return false;
		
		return $this->getA360activity($start,10,false,$qUser,false);
		
	}
	
	function getNotification(){
		/*  
		[total] => 85
		[content] => Array
        (
            [0] => Array
                (
                    [id] => 21658
                    [title] => aruka  aruka Get a letter A
                )

            [1] => Array
                (
                    [id] => 21657
                    [title] => aruka  aruka Accomplished task
                )
		*/
		$data = $this->apps->contentHelper->getPursuitUpdate();
		$res = false;
		if($data){
			$res['total'] = $data['total'];
			$result = $data['rec'];
			
			foreach($result as $key => $val){
				$res['content'][$key]['id'] =$key;
				if($val['action_id']=='22') $res['content'][$key]['title'] =$val['name']." accomplished ".$val['action_value'];
				else  $res['content'][$key]['title'] = $val['action_value'];
			}
			
					
		}
		
		return $res;
		
	}
	
	function getA360activity($start=0,$limit=5,$user=false,$thisBand=false,$followeronly=false,$n_status=3,$push=false){
		GLOBAL $LOCALE,$CONFIG;
		
				
		$data['total'] = false;
		$data['content'] = false;
		
		$activityIDarr = false;
		$theactivity = false;
		$qData = false;
		
		$articleActivity = "'tradefloor','accompalished'";
		$socialActivity = "'trading','getletter','get word','get phrase'";
		
		$theactivity = $this->apps->activityHelper->getactivitytype($articleActivity);	
		/* get article of user */
		if($theactivity) {		
			$articleActivitID = implode(',',$theactivity['id']);
		}else $articleActivitID = false;
		/* get activity social id */	
		$socialactivitydata = $this->apps->activityHelper->getactivitytype($socialActivity);
		if($socialactivitydata) {
			$socialActivityID = implode(',',$socialactivitydata['id']);
		}else $socialActivityID = false;
		
		$activityIDarr = array($articleActivitID,$socialActivityID);
		
		if($activityIDarr) {
			foreach($activityIDarr as $val){
				if($val!='')$activityIDNewArr[]=$val;
			}
			if(!$activityIDNewArr)return false;
			$qUser = " ";
			$activityId = implode(',',$activityIDNewArr);
			
			
			if($followeronly){
				$arrFriends = false;
				$friendid = false;
				$friendsData = $this->apps->userHelper->getFriends(false);
			
				if($friendsData){
					if($friendsData['result']){
						foreach($friendsData['data'] as $userfriends){
							
									$arrFriends[$userfriends['id']] = $userfriends;
						}
						// pr($arrFriends);exit;
						if($arrFriends){
							foreach($arrFriends as $key => $val){
									$friendid[$val['id']] = intval($val['id']);
							}
						}
						$friendid[$this->uid] = $this->uid;
						if(is_array($friendid)){
							$strFriendid = implode(',',$friendid);
							$qUser = " AND user_id IN ({$strFriendid}) ";
						}
					}
				}
				
			}
			
			if(!$thisBand){
			
				if($user) {
					if(strip_tags($this->apps->_g('page'))=='my') $qUser = " AND user_id = {$this->uid} ";
					else {
						$uid = intval($this->apps->_g('uid'));
						if($uid!=0) $qUser = " AND user_id = {$uid} ";					
					}
						// pr($qUser);
				}
				
			}else $qUser = $thisBand;
			
			$qfromid = "";
			$maxid = 0;
			
			$sql =" SELECT last_log_id FROM social_member WHERE id = {$this->uid} LIMIT 1";
			$lastlogid = $this->apps->fetch($sql);
			
			$maxid = intval($lastlogid['last_log_id']);
			if($maxid!=0) $qfromid = " AND id > {$maxid} ";
			
				// pr($qfromid);
			$sql = "SELECT count(*) total FROM tbl_activity_log WHERE n_status NOT IN ({$n_status}) AND action_id IN ({$activityId})  {$qUser} {$qfromid} ";		
			$total = $this->apps->fetch($sql);
			
			// if(!$total) return false;
			if(!$push) $qfromid = "";
			$sql = "SELECT *,IF ( action_id IN ({$socialActivityID}) , 'social' , 'content' ) typeofnotification 
					FROM tbl_activity_log WHERE n_status NOT IN ({$n_status}) AND  action_id IN ({$activityId})  {$qUser} {$qfromid} 
					ORDER BY date_time DESC LIMIT {$start},{$limit}";
					
			// pr($sql);
			$qData = $this->apps->fetch($sql,1);
			
			// pr($qData);
			if(!$qData) return false;
			
			$contentid = false;
			$otheruserid = false;
			$notifid = false;
			foreach($qData as $key => $val){
				//get userid
				$userid[] = $val['user_id'] ;
				//get action value
				$actionid[] = $val['action_id'];
				
				// id notif
				$notifid[] = $val['id'];
				//get content id
				$arrActionValue = false;
				
				if($val['typeofnotification'] == 'social' )	$otheruserid[] = intval($val['action_value']);
				else $contentid[] = intval($val['action_value']);
			}
			
			if($contentid) $arrContent = $this->getContentData($contentid);
			else $arrContent = false;
			
			if($otheruserid) $arrOtherUser = $this->getsocialData($otheruserid);
			else $arrOtherUser = false;
			
			if($userid) $arrUserid = $this->getsocialData($userid);
			else $arrUserid = false;
					
			if($actionid){
				$stractionid = implode(',',$actionid);
				
				//get content
				$sql = "SELECT * FROM tbl_activity_actions WHERE id IN ({$stractionid}) LIMIT {$limit}";
				$qSData = $this->apps->fetch($sql,1);
				if($qSData){
					foreach($qSData as $val){
						$arrActionid[$val['id']] = $val;
					}
				}else $arrActionid = false;			
			}
			//merge it

			foreach($qData as $key => $val){
				
					if($val['typeofnotification'] == 'social' ){
						if($arrOtherUser ){
							if(array_key_exists($val['action_value'],$arrOtherUser)) $qData[$key]['contentdetail'] = $arrOtherUser[$val['action_value']];	
							else $qData[$key]['contentdetail'] = false;
						}else $qData[$key]['contentdetail'] = false;
						$qData[$key]['contentType'] = 'social';
					}else{
						if($arrContent ){
							if(array_key_exists($val['action_value'],$arrContent)) $qData[$key]['contentdetail'] = $arrContent[$val['action_value']];	
							else $qData[$key]['contentdetail'] = false;
						}else $qData[$key]['contentdetail'] = false;
						$qData[$key]['contentType'] = 'content';
					}
					if($arrUserid){	
						if(array_key_exists($val['user_id'],$arrUserid))$qData[$key]['userdetail'] = $arrUserid[$val['user_id']];	
						else $qData[$key]['userdetail'] = false;
					}else $qData[$key]['userdetail'] = false;
					
					if($arrActionid){
						if(array_key_exists($val['action_id'],$arrActionid))$qData[$key]['actiondetail'] = $arrActionid[$val['action_id']];	
						else $qData[$key]['actiondetail'] = false;
					}else $qData[$key]['actiondetail'] = false;
				
				
			}
			$notificationdata = false;
			//can be used on views
			foreach($qData as $key => $val){
				// if($val['userdetail']) $notification[] =  $val['userdetail']['name'];
				// else  $notification[] = 'some one';
				if($val['actiondetail']) {
					if(array_key_exists($val['actiondetail']['activityName'],$LOCALE[$this->lid])) $activities = $LOCALE[$this->lid][$val['actiondetail']['activityName']]['news'];
					else $activities = false;
					$notification['activities'] = $activities;
					
				}else  $notification[] = 'has do something with';
				
				if($val['contentdetail']) {
					if($val['contentType']=='social') $notification['subjective'] = $val['contentdetail']['name'];
					else $notification['subjective'] = $val['contentdetail']['title'];
				}else  $notification['subjective'] = str_replace('_',' ',$val['action_value']);
				
				// $qData[$key]['notification'] = implode(' ',$notification);
				
				// $qData[$key]['notification'] = $notification;
				// $notificationdata[$key]['notification'] = $val['userdetail']['name']." ".$notification['activities']." ".$notification['subjective'];
				$notificationdata[$key]['id'] = $val['id'];
				$notificationdata[$key]['title'] = $val['userdetail']['name']." ".$notification['activities']." ".$notification['subjective'];
				$notification = null;
			}
			
			
				// pr($qData);
			if($qData){
				$data['total'] = intval($total['total']);
				$data['content'] = $notificationdata;
				
				if($push){
							
						$this->readnotification($notifid);
				}
				// pr($qData);
				return $data;
			}else return false;	
		}else return false;	
	}
	
	
	function readnotification($notifid=false){
		global $CONFIG;
		$data['maxid'] = intval(max($notifid));
		
		$sql =" UPDATE social_member SET last_log_id = {$data['maxid']} WHERE id = {$this->uid}  LIMIT 1";
		 $this->apps->query($sql);
	
		
		return true;
	}
	
	
	function getsocialData($userid=null){
			if($userid==null) return false;
			$struserid = implode(',',$userid);
			global $CONFIG;
			//get content
			$sql = "SELECT id,name,nickname,img,small_img,'friends' as type,last_name FROM social_member WHERE id IN ({$struserid}) LIMIT 10";
			// pr($sql);
			$qSData = $this->apps->fetch($sql,1);
			if($qSData){
				foreach($qSData as $key => $val){
					if(!is_file("{$CONFIG['LOCAL_PUBLIC_ASSET']}user/photo/{$val['img']}")) $qSData[$key]['img'] = false;
					if(is_file("{$CONFIG['LOCAL_PUBLIC_ASSET']}user/photo/tiny_{$val['img']}")) $qSData[$key]['img'] = "tiny_{$val['img']}";
					$arrUserid[$val['id']] =  $qSData[$key];
					
				}
			}else $arrUserid = false;			
			return $arrUserid;
	}
	
	function getContentData($contentid){
			if($contentid==null) return false;
			$strcontentid = implode(',',$contentid);
			
			//get content
			$sql = "SELECT anc.*,anct.type FROM {$this->schemadb}_news_content anc 
			LEFT JOIN {$this->schemadb}_news_content_type anct ON anct.id=anc.articleType
			WHERE anc.id IN ({$strcontentid}) LIMIT 10";
			// pr($sql);
			$qSData = $this->apps->fetch($sql,1);
			if($qSData){
				foreach($qSData as $val){
					$arrContent[$val['id']] = $val;
				}
			}else $arrContent = false;
			if($arrContent){
				$arrContent = $this->apps->contentHelper->getStatistictArticle($arrContent);
			}
			
			return $arrContent;
	}
	
	
	function getactivitytype($dataactivity=null,$id=false){
		if($dataactivity==null)return false;
		if($id) $qAppender = " id IN ({$dataactivity}) ";
		else $qAppender = " activityName IN ({$dataactivity})  ";
		$theactivity = false;
		/* get activity  id */	
		$sql = " SELECT * FROM tbl_activity_actions WHERE {$qAppender} ";

		$qData = $this->apps->fetch($sql,1);
			
		if($qData) {
			foreach($qData as $val){
				$theactivity['id'][$val['id']]=$val['id'];
				$theactivity['name'][$val['id']]=$val['activityName'];
				
			}
		}
		return $theactivity;
	}
	
	function getNewsFeed()
	{
		$sql = "SELECT log.*, sm.name, sm.nickname FROM tbl_activity_log log 
				LEFT JOIN social_member sm ON log.user_id = sm.id WHERE log.action_id IN ({$this->feedType}) ORDER BY log.date_time DESC LIMIT 5 ";
		$qData = $this->apps->fetch($sql,1);
		if ($qData){
			
			foreach ($qData as $key =>$val){
				
				// $tmp = preg_match("/You have accomplished the/", $val['action_value']);
				// if ($tmp){
				if ($val['action_id']==22){
					$qData[$key]['desc'] = $val['nickname']. " accomplished ". $val['action_value'];
				}else{
					$qData[$key]['desc'] = $val['nickname']. $val['action_value'];
				}	
				
				// }
			}
			
			return $qData;
		}
		
		return false;
	}
	
	
}
?>

