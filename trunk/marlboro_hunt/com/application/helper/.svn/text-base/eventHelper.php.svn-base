<?php 
class eventHelper {

	function __construct($apps){
		global $logger,$CONFIG;
		$this->logger = $logger;
		$this->apps = $apps;
			if($this->apps->isUserOnline())  {
					if(is_object($this->apps->user)) $this->uid = intval($this->apps->user->id);
			}
		else $this->uid = 0;
		$this->dbshema = "marlborohunt";
		$this->thisorthatid = 23;
		
	}
	
	function checkcurrentevent($articletype=3){
		
		$datetimes = date('Y-m-d');
		$sql = "SELECT * FROM {$this->dbshema}_news_content WHERE articleType = {$articletype} AND n_status = 1 
				AND title IS NOT NULL AND title <> '' AND posted_date <= '{$datetimes}' AND expired_date >= '{$datetimes}' GROUP BY posted_date ORDER BY posted_date DESC LIMIT 1";
				 // pr($sql);
		$qData = $this->apps->fetch($sql);
		if($qData)	return $qData;
		return false;
	}
	
	function postData($data){
			
		$qData =$this->checkcurrentevent(4);
		// $qData =$this->checkcurrentevent(21);
		
		$date = date('Y-m-d H:i:s');
		// pr($qData);exit;
		if($qData){	
			
			$insertQuery = "INSERT INTO {$this->dbshema}_news_content_repo 
					(content, typealbum, gallerytype, files, fromwho, otherid, userid, created_date, n_status ) 
					VALUES ('{$qData['content']}',2,0,'{$data['filename']}',
					1,'{$qData['id']}','{$this->uid}','{$date}','{$qData['n_status']}')
				";
			$res = $this->apps->query($insertQuery);
			// pr($insertQuery);
		} 
	}
	
	function postVideo($data){

		$qData =$this->checkcurrentevent(4);
		// $qData =$this->checkcurrentevent(21);
		
		if($qData){		
			$date = date('Y-m-d H:i:s');
			$insertvideo = "INSERT INTO {$this->dbshema}_news_content_repo 
					(content, typealbum, gallerytype, files, fromwho, otherid, userid, created_date, n_status ) 
					VALUES ('{$qData['content']}',3,0,'{$data['filename']}',
					1,'{$qData['id']}','{$this->uid}','{$date}','{$qData['n_status']}')
				";
			// pr($insertvideo);
			$resVid = $this->apps->query($insertvideo);
		} 
	}
	
	function postDataTask($data){
			
		$qData =$this->checkcurrentevent(21);
		
		// $qData =$this->checkcurrentevent(21);
		
		$date = date('Y-m-d H:i:s');
		// pr($qData);exit;
		if($qData){	
			
			$insertQuery = "INSERT INTO {$this->dbshema}_news_content_repo 
					(content, typealbum, gallerytype, files, fromwho, otherid, userid, created_date, n_status ) 
					VALUES ('{$qData['content']}',2,0,'{$data['filename']}',
					1,'{$qData['id']}','{$this->uid}','{$date}','{$qData['n_status']}')
				";
			$res = $this->apps->query($insertQuery);
			// pr($insertQuery);
		}
	}
	
	function checkcurrentTask($articletype=21){
		
		$datetimes = date('Y-m-d');
		$sql = "SELECT * FROM {$this->dbshema}_news_content WHERE articleType = {$articletype} AND n_status = 1 
				AND title IS NOT NULL AND title <> '' GROUP BY posted_date ORDER BY posted_date DESC LIMIT 1";
				 // pr($sql);
		$qData = $this->apps->fetch($sql);
		if($qData)	return $qData;
		return false;
	}
	
	
	function saveTaskUploadPhoto($data=null)
	{
		$date = date('Y-m-d H:i:s');
		if($data==null) return false;
		$id = intval($this->apps->_p('cid'));
		
		// $qData =$this->checkcurrentTask(21);
		
		if($id !=0){
			$insertQuery = "INSERT INTO {$this->dbshema}_news_content_repo 
					(content, typealbum, gallerytype, files, fromwho, otherid, userid, created_date, n_status ) 
					VALUES ('',2,0,'{$data['filename']}',
					1,'{$id}','{$this->uid}','{$date}','1')
				";
			// pr($insertQuery);
			$res = $this->apps->query($insertQuery);
			if($res) return array('status'=>true, 'idcontent'=>$id);
			
		}
		return false;
			
	}
	
	function thisorthatwinner($start=0,$limit=1,$n_status="2")
	{
		$start = intval($this->apps->_g('start'));
		
		if($start==null)$start = 1;
		else $start=$start-1;
		
		$datetimes = date('Y-m-d');
		$sql = "SELECT news.title, repo.userid, repo.files, user.name, user.last_name
				FROM {$this->dbshema}_news_content AS news
				LEFT JOIN {$this->dbshema}_news_content_repo AS repo ON news.id = repo.otherid
				LEFT JOIN social_member AS user ON repo.userid = user.id
				WHERE news.articleType IN ({$this->thisorthatid})
				AND repo.n_status =2
				ORDER BY repo.winning_date DESC 
				LIMIT {$start},{$limit}";
				 // pr($sql);
		$qData = $this->apps->fetch($sql);
		
		$sqlTotal = "SELECT COUNT(repo.id) AS total
				FROM {$this->dbshema}_news_content AS news
				LEFT JOIN {$this->dbshema}_news_content_repo AS repo ON news.id = repo.otherid
				LEFT JOIN social_member AS user ON repo.userid = user.id
				WHERE news.articleType IN ({$this->thisorthatid})
				AND repo.n_status =2
				ORDER BY repo.winning_date DESC 
				";
				 // pr($sql);
		$qDataTotal = $this->apps->fetch($sqlTotal);
		
		
		if($qData)	return array('res'=>$qData, 'total'=>$qDataTotal['total']);
		return false;
	}
}
?>