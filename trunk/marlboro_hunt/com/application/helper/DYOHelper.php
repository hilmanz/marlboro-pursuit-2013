<?php 

class DYOHelper {

	function __construct($apps){
		global $logger,$CONFIG;
		$this->config = $CONFIG;
		$this->logger = $logger;
		$this->apps = $apps;
		if(is_object($this->apps->user)) $this->uid = intval($this->apps->user->id);

		$this->dbshema = "marlborohunt";	
		
		$this->week = 7;
		$week = intval($this->apps->_request('weeks'));
		if($week!=0) $this->week = $week;
		
		$this->startweekcampaign = "2013-09-23";
		$this->datetimes = date("Y-m-d H:i:s");
		// pr($this->apps->_request('week'));
	}

	
	
	function saveImage($attendartype=0){	
	
		// $this->uid = 1;
		$checkdyo = $this->checkBeforeCreateDyo();
		if(!$checkdyo) return false;
		// $data['filename'] = sha1(date("Ymdhis").$this->uid).'.jpg';
		$data =  $this->apps->uploadHelper->putcontent("user/dyo/");
		// $data =  $this->apps->uploadHelper->httprawpostdata("user/dyo/");
		// $clothsize = strip_tags($this->apps->_p('clothsize'));
		if($data['result']){
			$datetimes = date("Y-m-d H:i:s");
			$sql = "INSERT INTO my_dyo (userid,image,datetime,n_status,size) VALUES ({$this->uid},'{$data['filename']}','{$this->datetimes}',1,'')";
			$this->apps->query($sql);
			$mydyoid['id'] = intval($this->apps->getLastInsertId());
			$this->apps->session->setSession($this->config['SESSION_NAME'],"DYOUSER",$mydyoid);
		}
		return $data;
	}
	
	function saveclothsize(){	
	
		// $this->uid = 1;
		$checkdyo = $this->checkBeforeCreateDyoCounter();
	 
		$clothsize = strip_tags($this->apps->_request('clothsize'));
		
	 	if($clothsize=='')  return array("result"=>false,"message"=>"failed save size image, you must define size cloth ","count"=>$checkdyo);
		$mydyo = @$this->apps->session->getSession($this->config['SESSION_NAME'],"DYOUSER");
		$mydyoid = intval($mydyo->id);
		// pr($mydyoid);
		// $mydyoid = 1;
		if($mydyoid==0)  return array("result"=>false,"message"=>"failed save size image, you must save image first","count"=>$checkdyo);
		$sql = "UPDATE my_dyo SET size='{$clothsize}' WHERE id={$mydyoid} LIMIT 1";
		// pr($sql);
		$this->logger->log($sql);
		$data = $this->apps->query($sql);
		if($data) return array("result"=>true,"message"=>"success save size image","count"=>$checkdyo);
		else  return array("result"=>false,"message"=>"failed save size image","count"=>$checkdyo);
	}
	
	function getGallery($user=false,$limit=5){	
		
		// $this->uid = 1;
		if($user==false){
			$sql = "SELECT * FROM my_dyo WHERE n_status=1 ORDER BY datetime DESC LIMIT {$limit} ";
		}else{
			$sql = "SELECT * FROM my_dyo WHERE userid={$this->uid} AND n_status=1 ORDER BY datetime DESC LIMIT {$limit} ";
		}		
		$data = $this->apps->fetch($sql,1);
		if($data) return $data;
		else return false;
	}
	
	function getGaleryDyo($start=null,$limit=5,$n_status="1,2")
	{
		$filter ="";
		$ORDER ="";
		$sub ="";
		
		if($start==null)$start = intval($this->apps->_request('start'));
		$week = intval($this->apps->_g('weeks'));
		$most = intval($this->apps->_g('most'));
		// pr($_GET);
		
		$sub .= "(SELECT COUNT( * ) totalview,action_value dyoid FROM tbl_activity_log WHERE action_id = 16 GROUP BY action_value ) tlv ON tlv.dyoid = a.id";
		if($week!=0) $filter .= "AND WEEK(a.datetime) = WEEK(DATE_ADD('{$this->startweekcampaign}', INTERVAL {$this->week} DAY )) ";
		
		if($most!=0) {		
			$ORDER .= " ORDER BY totalview DESC ";
		}else {
			$ORDER .= " ORDER BY a.n_status DESC, a.datetime DESC ";
			
		}
		
		$sql = "
		SELECT count(*) total FROM my_dyo
		";
		$total = $this->apps->fetch($sql);
		 
		if($total['total']==0)return false;
		
		$sql = "SELECT a.*, b.name  ,totalview
				FROM my_dyo AS a 
				LEFT JOIN social_member AS b ON a.userid = b.id 
				LEFT JOIN {$sub}
				WHERE b.name IS NOT NULL AND a.n_status IN ({$n_status})
				{$filter} {$ORDER}
				LIMIT {$start},{$limit}";		
		
		$res = $this->apps->fetch($sql,1);
		// pr($sql);
		if ($res){
		$arrdyoid = false;
			foreach($res as $key =>  $val){
					$res[$key]['vote'] =  0 ;
					$arrdyoid[$val['id']] = $val['id'];
			}	
			if($arrdyoid){
				$strdyoid = implode(',',$arrdyoid);
				
				$sql = "
					SELECT count(*) vote, contentid dyoid
					FROM {$this->dbshema}_news_content_favorite  
					WHERE contentid IN ({$strdyoid}) 
					GROUP BY dyoid
				";
				$voteData = $this->apps->fetch($sql,1);
				if($voteData){
					$vote =false;
					foreach($voteData as $val){
						$vote[$val['dyoid']] = $val['vote'];
					}
					if($vote){
						foreach($res as $key => $val){
							if(array_key_exists($val['id'],$vote)) $res[$key]['vote'] = $vote[$val['id']];						
						}	
					}			
				}			
			}
			
			
			$sqlGetWeek = "SELECT ceil( abs( DATEDIFF( '".date('Y-m-d')."', '{$this->startweekcampaign}' ) ) /7 ) AS week";
			// $sqlGetWeek = "SELECT ceil( abs( DATEDIFF( '2013-05-10', '2013-05-28' ) ) /7 ) AS week";
			$resGetWeek = $this->apps->fetch($sqlGetWeek);
			
			if ($resGetWeek['week']){
				$num = 7;
				for ($i = 1; $i<=$resGetWeek['week']; $i++){
					if ($i == 1){
						$data['weekevent'][$i] = 1;
					}else{
						$data['weekevent'][$i] = $num;
						$num = $num+7;
					}
					
					
				}
			}
			
			$data['result'] = $res;
			$data['total'] = $total['total'];
			
			return $data;
		}else{
			return FALSE;
		}
		
		
	}
	
	function addFavorite(){
		
		if(!$this->uid) return false;
		$cid = intval($this->apps->_p("cid"));
		if($cid==0) return false;
		
		$date = date('Y-m-d');
		$sql = "
		SELECT count(*) total 
		FROM {$this->dbshema}_news_content_favorite
		WHERE userid={$this->uid} AND DATE(date) = '{$date}' LIMIT 1";
		
		$qData = $this->apps->fetch($sql);
		
		if($qData['total']>0) return false;		
		
		$sql=" 
				INSERT INTO 
				{$this->dbshema}_news_content_favorite 	(userid ,	contentid 	,likes, 	date ,	n_status  ) 
				VALUES ({$this->uid},{$cid},1,'{$this->datetimes}',1)
				";
		$this->apps->query($sql);
		if($this->apps->getLastInsertId()>0) return true;
		return false;
	
	}
	
	function checkBeforeCreateDyo()
	{
		$datetime = date('Y-m-d');
		$qData['total'] = 0;
		$sql = "SELECT COUNT(*) total FROM my_dyo WHERE userid={$this->uid} AND  n_status IN (1,2) AND WEEK(datetime)=WEEK('{$datetime}') GROUP BY userid  ";
		$this->logger->log($sql);
		$data = $this->apps->fetch($sql);
		// if(!$qData) return false;
		if($data) $qData['total'] = intval($data['total']);
		// pr($qData);
		$this->logger->log(" total dyo this day : ".json_encode($qData));
		if(intval($qData['total']) >= 6){
		
			return false;
		}else{
			return true;
		}
	}
	function checkBeforeCreateDyoCounter()
	{
		$datetime = date('Y-m-d');
		$qData['total'] = 0;
		$sql = "SELECT COUNT(*) total FROM my_dyo WHERE userid={$this->uid} AND  n_status IN (1,2) AND WEEK(datetime)=WEEK('{$datetime}') GROUP BY userid  ";
		$this->logger->log($sql);
		$data = $this->apps->fetch($sql);
		// if(!$qData) return false;
		if($data) $qData['total'] = intval($data['total']);
		// pr($qData);
		$this->logger->log(" total dyo this day : ".json_encode($qData));
		
		return intval($qData['total']);
		 
	}
	
 
	function myGalleryDyo($start=null,$limit=6,$n_status="1,2", $most=0)
	{
		// $datetime = date('Y-m-d');
		// $sql = "SELECT * FROM my_dyo WHERE userid = {$this->apps->user->id} AND WEEK(datetime)=WEEK('{$datetime}') LIMIT 3";
		// pr($sql);
		// $res = $this->apps->fetch($sql,1);
		// if ($res) return $res;
		// return false;
	
 
		$filter ="";
		$ORDER ="";
		$sub ="";
		
		if($start==null)$start = intval($this->apps->_request('start'));
		$week = intval($this->apps->_p('weeks'));
		
		$sub .= "(SELECT COUNT( * ) totalview,action_value dyoid FROM tbl_activity_log WHERE action_id = 16 GROUP BY action_value ) tlv ON tlv.dyoid = a.id";
		if($week!=0) $filter .= "AND WEEK(a.datetime) = WEEK(DATE_ADD('{$this->startweekcampaign}', INTERVAL {$this->week} DAY )) ";
		else $filter .= "AND WEEK(a.datetime) = WEEK(DATE_ADD('{$this->startweekcampaign}', INTERVAL 1 DAY )) ";
		
		if($most!=0) {		
			$ORDER .= " ORDER BY totalview DESC ";
		}else {
			$ORDER .= " ORDER BY a.n_status DESC, a.datetime DESC ";
			
		}
		
		$sql = "
		SELECT count(*) total FROM my_dyo
		";
		$total = $this->apps->fetch($sql);
		 
		if($total['total']==0)return false;
		
		$sql = "SELECT a.*
				FROM my_dyo AS a 
				WHERE a.n_status IN ({$n_status}) 
				AND a.userid = {$this->apps->user->id}
				{$filter} {$ORDER}
				LIMIT {$start},{$limit}";		
		
		$res = $this->apps->fetch($sql,1);
		// pr($sql);
		if ($res){
			$data['mygallery'] = $res;
		}
		
		$sqlGetWeek = "SELECT ceil( abs( DATEDIFF( '".date('Y-m-d')."', '{$this->startweekcampaign}' ) ) /6 ) AS week";
		// $sqlGetWeek = "SELECT ceil( abs( DATEDIFF( '2013-05-10', '2013-05-28' ) ) /7 ) AS week";
		$resGetWeek = $this->apps->fetch($sqlGetWeek);
		// pr($sqlGetWeek);
		if ($resGetWeek['week']){
			$num = 7;
			for ($i = 1; $i<=$resGetWeek['week']; $i++){
				if ($i == 1){
					$data['weekevent'][$i] = 1;
				}else{
					$data['weekevent'][$i] = $num;
					$num = $num+7;
				}
				
				
			}
		}
		
		return $data;
	}
	
	
}

?>

