<?php

class loginHelper {
	
	var $_mainLayout="";
	
	var $login = false;
	
	function __construct($apps){
		global $logger,$CONFIG;
		$this->logger = $logger;
		$this->apps = $apps;
		
		$this->config = $CONFIG;
		if( $this->apps->session->getSession($this->config['SESSION_NAME'],"WEB") ){
			
			$this->login = true;
		
		}
	
	}
	
	function checkLogin(){
		
		return $this->login;
	}
	
	function mopsessionlogin($sessionid=false){
		if(!$sessionid) return false;
		
		$ok = false;
		$MOP_PROFILE = $this->apps->mopHelper->mop();	
		
		if($this->mopLogin($MOP_PROFILE)){		
			return true;
		}else return false;
				
	}
	
	
	function mopLogin($MOP_SESSION=false){
		if($MOP_SESSION==false) return false;
		
		foreach($MOP_SESSION['UserProfile'] as $key => $val){
			$$key = $val;
		}		
		
		$sql = "SELECT * FROM social_member WHERE registerid='{$RegistrationID}' LIMIT 1";
		$rs = $this->apps->fetch($sql);
		$this->logger->log($sql);
			
 		if($rs){
			$rs['hasband'] = false;
			$rs['hasdj'] = false;
			$rs['pageid'] = false;
			$rs['immember'] = false;
			$rs['ownerid'] = false;
			$this->logger->log('can login');
			$id = $rs['id'];
			$this->add_stat_login($id);
			$pagestat = $this->getPagesStat($id);
			if($pagestat)	$rs = array_merge($rs,$pagestat);
			
			$memberstat = $this->getMemberPagesStat($id);
			if($memberstat)	$rs = array_merge($rs,$memberstat);
			
				$this->apps->session->setSession($this->config['SESSION_NAME'],"WEB",$rs);
			
			$this->login = true;
			return true;
		}else {
			$this->logger->log("cannot login, password or username not exists ");

			return false;
		}
	
	}
	
	
	/* below function used as local login only: on development phase without MOP */
	
	function loginSession($token=false){
		$ok = false;
		
		if($this->apps->_p('login')==1){
			$data = $this->goLogin();
			
			if($data){
		
				return true;
			}else{
			
				return false;
			}
		}
		
		
		return false;
	}
	
	
	
	function goLogin(){
		global $logger, $APP_PATH;
			
		$username = strip_tags(trim($this->apps->_request('username')));
		$password = strip_tags(trim($this->apps->_request('password')));
		$logger->log($password.'-'.$username);
		$sql = "SELECT * FROM social_member WHERE email='{$username}' AND n_status = 1 LIMIT 1"; 
		$rs = $this->apps->fetch($sql);
		$logger->log($sql);
	
		if ($rs['login_count'] == 0){
			$hash = $password;
		}else{			
			// $hash = sha1($rs['salt'].$password);
			$hash = sha1($password.'{'.$rs['salt'].'}');
			
		}
		
		
 		if($rs['password']==$hash){
			
			$this->setdatasessionuser($rs); 
			// pr($rs);exit;
			// $this->add_stat_login($rs['id']);
			$logger->log('can login');
			$this->login = true;
			return true;
		}else {
			$logger->log("cannot login, password or username not exists ".$password.'-'.$username);

			return false;
		}
	
	}
	
	function updateLoginUser()
	{
		global $CONFIG;
		$password = strip_tags(trim($this->apps->_p('password')));
		$repassword = strip_tags(trim($this->apps->_p('repassword')));

		$data = $this->apps->session->getSession($this->config['SESSION_NAME'],"WEB");
		
		if(!preg_match("/^(?=.{8,20})(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])/",$password)) return false;
		
		if ($password === $repassword) $new_password = $password;
		else return FALSE;
		
		$getInfo = "SELECT login_count, salt FROM social_member WHERE id = {$data->id}";
		$resInfo = $this->apps->fetch($getInfo);
		
		// $new_password = sha1($resInfo['salt'].$password);
		$new_password = sha1($password.'{'.$resInfo['salt'].'}');
		
		$login_count = $resInfo['login_count'] + 1;
		$sql = "UPDATE social_member SET password = \"{$new_password}\", login_count = {$login_count},description='' WHERE id = {$data->id}";
		// pr($sql);
		$res = $this->apps->query($sql);
		
		if ($res) return TRUE;
		return FALSE;
	}
	
	function simpleLogin()
	{
		global $logger, $APP_PATH;
			
		$username = strip_tags(trim($this->apps->_p('username')));
		$password = strip_tags(trim($this->apps->_p('password')));
		$id = intval($_SESSION['userid']);
		if($id==0) return false;
		$sql = "SELECT * FROM social_member WHERE id ='{$id}' LIMIT 1";
		$rs = $this->apps->fetch($sql);
		$logger->log($sql);
		
		
		// $hash = sha1($rs['salt'].$password);
		$hash = sha1(concat($password,'{',$rs['salt'],'}'));
		//pr($sql);
		//$hash = sha1($rs['salt'].$password.$rs['salt']);
		//check password and phonumber , each field must be same of input value (higher security)
		
 		if($rs['password']==$hash){ 
			$this->setdatasessionuser($rs); 
			// $_SESSION['user_ses'] = true;
			
			
			$logger->log('can login');
			$this->login = true;
			
			
			return array('count'=>$rs['count'], 'result'=>true, 'verified'=>$rs['verified']);
		}else {
			$logger->log("cannot login, password or username not exists ");

			return false;
		}
	}
	
	function term_and_condition()
	{

		global $CONFIG;
		$data = $this->apps->session->getSession($CONFIG['SESSION_NAME'],"WEB");

		
		$sql = "UPDATE social_member SET term_and_condition = 1 WHERE id = {$data->id}";
		
		$res = $this->apps->query($sql);
		if ($res) return TRUE;
		return FALSE;
		
	}
	
	function getBrands()
	{
		$sql = "SELECT * FROM brands ORDER BY brands_name";
		$res = $this->apps->fetch($sql, 1);
		if ($res) return $res;
		return FALSE;
	}
	
	
	function setdatasessionuser($rs=false){
		
		if(!$rs) return false;
	
		$id = $rs['id'];

		// $this->apps->session->setSession($CONFIG['SESSION_NAME'],'LOGIN',$rs);
		
		$this->apps->session->setSession($this->config['SESSION_NAME'],"WEB",$rs);
		
		
	}
	
	function getPagesStat($user_id=null){
			if($user_id==null) return false;
			
			$sql = "SELECT * FROM my_pages WHERE n_status=1 AND ownerid ={$user_id} LIMIT 1";
			$qData = $this->apps->fetch($sql);
			
			if(!$qData) return false;
			$data = false;
			if($qData['type']==1) $data['hasband'] = true;
			if($qData['type']==4) $data['hasdj'] = true;
			if(!$data) return false;
			$data['pageid'] = $qData['id'];
			$data['ownerid'] = $qData['ownerid'];
			$data['immember'] = false;				
			return $data;
	}
	
	function getMemberPagesStat($user_id=null){
			if($user_id==null) return false;
			
			$sql = "SELECT * FROM my_pages_member WHERE mypagestype=1 AND mymember={$user_id} LIMIT 1";
			$qData = $this->apps->fetch($sql);
			if(!$qData) return false;
			$data = false;
			if($qData['mypagestype']==1) $data['hasband'] = true;
			if($qData['mypagestype']==4) $data['hasdj'] = true;
			if(!$data) return false;
			$data['pageid'] = $qData['myid'];
			$data['ownerid'] = false;			
			$data['immember'] = true;			
			return $data;
	}
	
	function add_stat_login($user_id){
	
	
	$sql ="UPDATE social_member SET last_login=now(),login_count=login_count+1 WHERE id={$user_id} LIMIT 1";
	$rs = $this->apps->query($sql);

	
	}
	
	function getProfile(){
	
		$user = $this->apps->session->getSession($this->config['SESSION_NAME'],"WEB");
		
		return $user;
	
	}
	
	
	
	function saveSurvey()
	{
		$survey = $_POST['survey'];
		if (is_array($survey)){
			$dataSurvey = implode(',',$survey);
		}
		
		$consent = $_POST['consent'];
		if (is_array($consent)){
			$dataConsent = implode(',',$consent);
		}
		
		$POST = array('survey'=>$dataSurvey, 'consent'=>$dataConsent);
		$sql = "UPDATE social_brand_preferences SET survey = '{$POST['survey']}', consent = '{$POST['consent']}'";
		$result = $this->apps->query($sql);
		if ($result) return TRUE;
		return FALSE;
	}
	
	function storeDataFromLink($data)
	{
		$ref = strip_tags($this->apps->_p('ref'));
		
		$date = date('Y-m-d H:i:s');
		$sql = "INSERT INTO my_pages (ownerid, name, description, type, created_date, n_status)
				VALUES ({$data->id}, '{$data->name}', '{$ref}', 2, '{$date}', 1)";
		// pr($sql);
		$res = $this->apps->query($sql);
		if ($res) return true;
		return false;
	}
	
}
