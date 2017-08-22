<?php
global $APP_PATH;

class App extends Application{
	
	var $_mainLayout=""; 
	var $user = array();
	var $ACL;
	var $userHelper;
	var $contentHelper;
	var $userpage;
	function __construct(){
		parent::__construct();
		
		$this->setVar();
	
	}
	/**
	 * warning : do not put db query here.
	 */
	function setVar(){
		global $CONFIG;
			
		if(isset($_SESSION['lid'])) $this->lid = intval($_SESSION['lid']);
		else $this->lid = 1;
		if($this->lid=='')$this->lid=1;
		// exit;
		$this->userpage = $CONFIG['DINAMIC_MODULE'];
		$this->assign('userpage',$this->userpage);
		
		$page= strip_tags($this->_g('page'));	
		$act= strip_tags($this->_g('act'));	
		$ref= '/'.strip_tags($this->_g('ref'));	
		
		// pr($_GET);exit;
		if($page!='login'&&$page!='landing'&&$page!='register'&&$page!='forgotpassword'){
			// pr($this->isUserOnline());exit;
			
			if($this->isUserOnline()){
				
				$this->user = $this->getUserOnline();
				
				$this->userHelper = $this->useHelper('userHelper');
				$this->messageHelper = $this->useHelper('messageHelper');
				$this->botHelper = $this->useHelper('botHelper');


			}else{			
				
					if($CONFIG['BASE_DOMAIN']."login/".$act!=$CONFIG['LOGIN_PAGE']&&$page!=$CONFIG['REGISTER_PAGE']) {
						
						if ($this->_g('ref')){
							// pr($_GET);
							if ($act)sendRedirect("{$CONFIG['LOGIN_PAGE']}/local{$ref}");
						}else {
							sendRedirect("{$CONFIG['LOGIN_PAGE']}");
						}
						exit;
					
					}
			}
	
		}else{
			
		
			if($this->isUserOnline()){
				
				$this->user = $this->getUserOnline();
				$this->userHelper = $this->useHelper('userHelper');
			
			}
		
			if($page=='register'){
				
					
					$this->user = $this->getUserOnline();
					
					$this->userHelper = $this->useHelper('userHelper');


				
			}
			
			if($page=='forgotpassword'){
				
					
					$this->user = $this->getUserOnline();
					
					$this->userHelper = $this->useHelper('userHelper');


				
			}
		}
		
	}
	
	function main(){
		global $CONFIG,$LOCALE;
		global $FB;
		
		if($this->isUserOnline()) $this->hiddencode();
		if($this->isUserOnline()) $this->getMessageUser();
		if($this->isUserOnline()) $this->getPointNotif();
		
		// exit;
		$this->assign('locale',$LOCALE[1]);
		$this->assign('lifetime',$CONFIG['LIFETIME']);
		
		if($CONFIG['CLOSED_WEB']==true){
				sendRedirect($CONFIG['TEASER_DOMAIN']);
				die();
		}
		if($CONFIG['MAINTENANCE']==true){
			$this->assign('meta',$this->View->toString(TEMPLATE_DOMAIN_WEB . "/meta.html"));
			$this->assign('mainContent', $this->View->toString(TEMPLATE_DOMAIN_WEB . '/under-maintenance.html'));
			$this->mainLayout(TEMPLATE_DOMAIN_WEB . '/master.html');
		}else{
				// pr($this->user);
			
				$str = $this->run();
				
				$this->afterFilter();
				
				$this->assign('basedomain', $CONFIG['BASE_DOMAIN']);
				$this->assign('pages',  strip_tags($this->_g('page')));
				$this->assign('acts',  strip_tags($this->_g('act')));
				$this->assign('assets_domain', $CONFIG['ASSETS_DOMAIN_WEB']);
			
				
				//encrypt URL
				$this->assign('nexturl',urlencode($_SERVER['REQUEST_URI']));
				$this->assign('getUserData',$this->Request->encrypt_params(array("page"=>"contentDownload","act"=>"getUserData")));
				
				$this->assign('isLogin',$this->isUserOnline());
			
				if($this->isUserOnline()) {
					$this->assign('user',$this->user);
					if($this->_g('page')=='home') {
						$this->checkbirthday();
						// $this->get20thlogin();
						// $this->giveLetterWhenLogin();
						$this->setPointWhenLogin();
						
					}
					
				}
				// pr($this->user);
				
				// pr(strip_tags($this->_g('page')));
				$this->assign('currentpage',strip_tags($this->_g('page')));
				$this->assign('coorporate_base_path',$CONFIG['COORPORATE_DOMAIN']);
				$this->assign('meta',$this->View->toString(TEMPLATE_DOMAIN_WEB . "/meta.html"));
				
				$this->assign('header',$this->View->toString(TEMPLATE_DOMAIN_WEB . "/header.html"));
				$this->assign('footer',$this->View->toString(TEMPLATE_DOMAIN_WEB . "/footer.html"));
				$this->assign('fbID',$FB['appID']);
				
				$this->assign('mainContent',$str);
				$this->beforeRender();
				$this->mainLayout(TEMPLATE_DOMAIN_WEB . '/master.html');
				
		}
	}
	
	function hiddencode(){
		global $CONFIG;
		$this->gamesHelper = $this->useHelper('gamesHelper');
		
		$dataCode = $this->gamesHelper->getTodayHiddenCode();
		// pr($dataCode);exit;
		if($dataCode){
			$rangeprob = intval($CONFIG['HIDDENCODERANGE']); // 10% show up 
			$randomize = rand(0,100);
			if($rangeprob >= $randomize) {
				$data['code'] = $dataCode;
				$data['userid'] = $this->user->id;
				$data['token'] = sha1($this->user->id.date('YmdHi').$dataCode['codeid']);
				$dataEncrypted = urlencode64(serialize($data));
				$this->assign('showhiddencode',$dataEncrypted);
			}
		}
		
	}
	
	function getMessageUser()
	{
		$this->messageHelper = $this->useHelper('messageHelper');
		
		$dataCode = $this->messageHelper->getMessage(true);
		$dataInbox = $this->messageHelper->getInbox(true);
		
		$dataCodeInt = 0;
		$dataInboxInt = 0;
		if($dataCode) $dataCodeInt = count($dataCode);
		if($dataInbox) $dataInboxInt= count($dataInbox);
			
			$this->assign('usermessage',$dataCodeInt);
			$this->assign('inboxcount',$dataInboxInt);
		 
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
	function run(){
		global $APP_PATH,$CONFIG;
		
		//ini me-return variable $page dan $act
		if($this->Request->getParam("req")) $this->Request->decrypt_params($this->Request->getParam("req"));
		$page = $this->Request->getParam('page');
		
		$act = $this->Request->getParam('act');		
			/* unverified go here */
			
			
			if($this->isUserOnline()) if($this->user->n_status==0&&!in_array($page,$CONFIG['access-unverified'])) {
				
				$this->View->assign('basedomain', $CONFIG['BASE_DOMAIN']);
				$this->View->assign('pages',  strip_tags($this->_g('page')));
				$this->View->assign('assets_domain', $CONFIG['ASSETS_DOMAIN_WEB']);
				
				return $this->View->toString(TEMPLATE_DOMAIN_WEB .'/apps/unverified-pages.html');
			}
		
			/* fisrt timer go here */
		
			if($this->isUserOnline()) if($this->user->login_count==0&&$page!='login') {
				
				// sendRedirect($CONFIG['BASE_DOMAIN']."login/tnc");
				// exit;
			}
		
		if( $page != '' ){
			if( !is_file( $APP_PATH . MODULES_DOMAIN_WEB . $page . '.php' ) ){
		
				if( is_file( '../templates/'. TEMPLATE_DOMAIN_WEB . '/'. $page . '.html' ) ){
					return $this->View->toString(TEMPLATE_DOMAIN_WEB.'/'.$page.'.html');
				}else{
					sendRedirect($CONFIG['BASE_DOMAIN']."index.php");
					die();
				}
			}else{
					
				include_once MODULES_DOMAIN_WEB. $page.'.php';
				
				$content = new $page();
				
				$content->beforeFilter();
				
				if( $act != '' ){
				
					if( method_exists($content, $act) )	return $content->$act();
					else return $content->main();
				}else return $content->main();
			}
			
		}else{
			
			include_once MODULES_DOMAIN_WEB . $CONFIG['DEFAULT_MODULES'];
			$content = new home();
			$content->beforeFilter();
			return $content->main();
		}
	}
	
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
	
	function checkbirthday(){
		$datetimes = date('Y-m-d');
		$years = date('Y');
		
		list($year, $month, $date) = explode('-',$this->user->birthday);
	
		$firstday30 = $years.'-'.$month.'-'.$date;
		$lastday30 = date("Y-m-d",strtotime($years.'-'.$month.'-'.$date. ' + 30 days'));
	
		// if($this->user->birthday==$datetimes){
		if($firstday30<=$datetimes&&$datetimes<=$lastday30) {
				
				$this->assign('mybirthday',true);
			
		}
			
	}
	
	function get20thlogin(){
		return false;
		global $LOCALE;
		$result =  $this->botHelper->get20logingift();
	
			
		if($result)  {
			$mastercode =  $this->botHelper->getMasterCode();
			// pr($result);
			if($mastercode) $this->assign('login20thletter',strtolower($mastercode[$result['data']['codeid']]));	
			sleep(1);
			$this->log('logingift', $LOCALE[1]['20thlogin']);
			$this->assign('get20login',true);
		}
		
	}
	
	function giveLetterWhenLogin()
	{
		return false;
		global $LOCALE;
		$result =  $this->botHelper->getuserfirstloginletter();
		
		// pr($result);
		// exit;
			if($result['status']){
				
				if ($result['type'] == 2){
					sleep(1);
					// echo 'sleep1';
					// $this->messageHelper->createMessage($this->user->id,"Congratulations!, You've got your first. 2 Letters for joining The Pursuit");
					$this->log('logingift', $LOCALE[1]['letterforexistinguser']);
				}else{
					sleep(1);
					// echo 'sleep2';
					// $this->messageHelper->createMessage($this->user->id,"congratulation.. you got 1 letter");
					$this->log('logingift', $LOCALE[1]['letterfornewuser']);
				}
					
			}
		
	}
	
	function getPointNotif()
	{
		$getPoint =  $this->userHelper->getUserPoint();
		
		$this->assign('userpoint',$getPoint['point']);
	}
	
	function setPointWhenLogin()
	{
		global $LOCALE;
		$setGift =  $this->botHelper->setPointgift("LOGIN GIFT", "loginpoint", 1, 'day');
		
	}
	
}
?>