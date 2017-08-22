<?php
global $APP_PATH;

class ServiceAPI extends Application{
	
	var $_mainLayout=""; 
	var $user = array();
	var $ACL;
	var $userHelper;
	var $contentHelper;
	var $userpage;
	function __construct(){
		parent::__construct();
		
		$page= strip_tags($this->_g('page'));	
		$arrNotloginpage = array('login','outbound','inbound','leaderboardgame');
		if(!in_array($page,$arrNotloginpage)){
			if($this->isUserOnline()){
					$this->user = $this->getUserOnline();
					$this->userHelper = $this->useHelper('userHelper');
			}else{
				print  $this->error_404();exit;
			}
		}else{
			$this->user = @$this->getUserOnline();
			$this->userHelper = @$this->useHelper('userHelper');
		}
		
	}

	
	function main(){
		global $CONFIG,$APP_PATH;
		
		$page = strip_tags($this->Request->getParam('page'));
		$act = strip_tags($this->Request->getParam('act'));
		$access_token = strip_tags($this->Request->getParam('access_token'));	
		
		if( $page != '' ){
				
				$service = $page;
				if(is_file( $APP_PATH . APPLICATION . '/services/'. $service . '.php' ) ){	
			
					require_once 'services/'. $service . '.php';
					$content = new $service();
					
					$content->beforeFilter();
					
					if( $act == '' ){
						if(method_exists($content, 'main') ){
					
							print  $this->toJson($content->main()); 
							exit;
						}else{
							print  $this->error_404();exit;
						}
					}
					
					if( $act != '' ){
						if(method_exists($content, $act) ){
					
							print  $this->toJson($content->$act()); 
							exit;
						}else{
							print  $this->error_404();exit;
						}
					}
				}
			
		}
		
		print  $this->error_401();exit;
	}
	
	
	function toJson($data){
		header('Cache-Control: no-cache, must-revalidate');
		header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
		header('Content-type: application/json');		
		print json_encode($data);
		exit;
	}
	
	function is_valid_access_token($access_token){
		global $CONFIG;
		$info = read_access_token($access_token);
		
		if($info['api_key']!=null&&$info['user_id']!=null){
			if($info['api_key']==$CONFIG['SERVICE_KEY']){
				$this->access_info = $info;
				return true;
			}
		}else if($info['api_key']!=null&$this->Request->getParam('nouser')){
			if($info['api_key']==$CONFIG['SERVICE_KEY']){
				$this->access_info = $info;
				return true;
			}
		}else{}
	}
	
	function error_404(){
		return json_encode(array("status"=>404,"message"=>"function not found"));
	}
	
	function error_400(){
		return json_encode(array("status"=>400,"message"=>"invalid param"));
	}
	
	function error_401(){
		return json_encode(array("status"=>401,"message"=>"you not verified"));
	}
	
	function error_417(){
		return json_encode(array("status"=>417,"message"=>"failed"));
	}
	
	function error_406(){
		return json_encode(array("status"=>406,"message"=>"not complete"));
	}
	
	function setWidgets($class=null,$path=null){
		GLOBAL $APP_PATH;
		
		if($class==null) return false;
			if( !is_file( $APP_PATH .WIDGET_DOMAIN_WEB. $path . $class .'.php' ) ){
			
				if( is_file( '../templates/'. WIDGET_DOMAIN_WEB . $path  . $class .'.html' ) ){
					return $this->View->toString(WIDGET_DOMAIN_WEB .$path. $class .'.html');
				}
			}else{
			// pr($class);
				include_once $APP_PATH . WIDGET_DOMAIN_WEB . $path. $class .'.php';
				$widgetsContent = new $class($this);
				return $widgetsContent->main();
			}
	}
	
	
	function useHelper($class=null,$path=null){
		GLOBAL $APP_PATH,$DEVELOPMENT_MODE;
		if($class==null) return false;
		if(file_exists($APP_PATH . HELPER_DOMAIN_WEB. $path. $class .'.php')){
			include_once $APP_PATH . HELPER_DOMAIN_WEB. $path. $class .'.php';
			$helper = new $class($this);
			return $helper;
		}else{
			if($DEVELOPMENT_MODE){
				print "please define : ".$APP_PATH . HELPER_DOMAIN_WEB. $path. $class .'.php';
				die();
			}
		}
	}
	
	/*
	 *	Mengatur setiap paramater di alihkan ke class yang mengaturnya
	 *
	 *	Urutan paramater:
	 *	- page			(nama class) 
	 *	- act				(nama method)
	 *	- optional		(paramater selanjutnya optional, tergantung kebutuhan)
	 */
	
	
	function birthday($birthday){
		$birth = explode(' ',$birthday);
		list($year,$month,$day) = explode("-",$birth[0]);
		$year_diff  = date("Y") - $year;
		$month_diff = date("m") - $month;
		$day_diff   = date("d") - $day;
		if ($day_diff < 0 || $month_diff < 0)
		  $year_diff--;
		return $year_diff;
	}
	
	function is_valid_email($email) {
	  $result = TRUE;
	  if(!eregi("^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,4})$", $email)) {
		$result = FALSE;
	  }
	  return $result;
	}
	
	function is_admin(){
	
		$sql = "SELECT count(*) as total 
			FROM tbl_front_admin
			WHERE
			user_id='".mysql_escape_string(intval($_SESSION['user_id']))."' 
			AND fb_id='".mysql_escape_string(intval($_SESSION['user_login_id']))."'
			LIMIT 1
			;";
		
		$this->open(0);
		$checkAdmin = $this->fetch($sql);
		$this->close();	
		// print_r($sql);			
		if($checkAdmin) {
		$is_admin = ($checkAdmin['total']>=1) ? true : false ;
		}else $is_admin = false;
		
		return $is_admin;
	}
	function objectToArray($object) {
		//print_r($object);exit;
		
		 if (is_object($object)) {
		    foreach ($object as $key => $value) {
		        $array[$key] = $value;
		    }
		}
		else {
		    $array = $object;
		}
		return $array;
		
	}
	
}
?>