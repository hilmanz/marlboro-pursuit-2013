<?php
class login extends App{
		
	function beforeFilter(){
		$this->loginHelper = $this->useHelper('loginHelper');
		$this->mopHelper = $this->useHelper('mopHelper');
		$this->registerHelper = $this->useHelper('registerHelper');
		
		
	}
	
	function main(){
		return $this->local();
		exit;
		global $CONFIG,$logger;
		$basedomain = $CONFIG['BASE_DOMAIN'];
		$this->assign('basedomain',$basedomain);
		
		$sessionid = strip_tags($this->_g('id'));
		
		if($sessionid!=0){
	
			$res = $this->loginHelper->mopsessionlogin($sessionid);
				
			if($res){
					$this->log('login','welcome');
					$this->assign("msg","login-in.. please wait");
					$this->assign("link","{$CONFIG['BASE_DOMAIN']}{$CONFIG['DINAMIC_MODULE']}");
					sendRedirect("{$CONFIG['BASE_DOMAIN']}{$CONFIG['DINAMIC_MODULE']}");
					return $this->out(TEMPLATE_DOMAIN_WEB . '/login_message.html');
					die();
			}
		}

		sendRedirect("{$CONFIG['LOGIN_PAGE']}");
		//return $this->out(TEMPLATE_DOMAIN_WEB . '/login_message.html');
		die();
	}
	
	function local(){
		
		global $CONFIG,$logger;
		$basedomain = $CONFIG['BASE_DOMAIN'];
		$this->assign('basedomain',$basedomain);
		
		// $res = false;
		//pr($_POST);
		/* fix bug login page after login */
		if($this->isUserOnline()){
			sendRedirect("{$CONFIG['BASE_DOMAIN']}");
			exit;		
		}
			$loginstat = intval($this->_p('login'));
			if ($loginstat){
			
				$result = 0;
				if($loginstat==1) {
					if (isset($_POST['colors'])){
						$tick = $_POST['colors'];
						$count = count($tick);
						
						if ($count < 2){
							$this->assign('musttick',true);
						}else{
							$res = $this->loginHelper->loginSession();
							if($res) $result = 1;
							else $result = 2 ;
						}
						
						
					}else{
						$this->assign('musttick',false);
					}
					
				}
				
				if($result==2) 	$this->assign('wronglogin',true);
				else $this->assign('wronglogin',false);
		
				//exit;
				if($result==1){
				// pr($_POST);
				// pr($ref);exit;
					$refEnc = strip_tags($this->_p('ref'));
					// $ref = urlencode64(serialize($dataReset));
					$ref = @unserialize(urldecode64($refEnc));
					
					
					
						$this->log('login','welcome');
						$this->assign("msg","login-in.. please wait");
						$this->assign("link","{$CONFIG['BASE_DOMAIN']}{$CONFIG['DINAMIC_MODULE']}");
						//sendRedirect("{$CONFIG['BASE_DOMAIN']}{$CONFIG['DINAMIC_MODULE']}");
						//$data = json_decode(urldecode64($this->session->get($CONFIG['SESSION_NAME'])));
						$data = $this->session->getSession($CONFIG['SESSION_NAME'],"WEB");
					
						//exit;
						
						$ondoing = $data->description;
						
						if ($data->login_count == 0 ){
							
							// sendRedirect("{$CONFIG['BASE_DOMAIN']}tnc");
							sendRedirect("{$CONFIG['BASE_DOMAIN']}account/changePassword");
							//echo 'masul';
						}else{
							if($ondoing=='changepassword'){
								// sendRedirect("{$CONFIG['BASE_DOMAIN']}tnc");
								sendRedirect("{$CONFIG['BASE_DOMAIN']}account/changePassword");
							}else{
								
								$logincount = $this->loginHelper->add_stat_login($data->id);
								
								if ($ref){
									$storeRef = $this->loginHelper->storeDataFromLink($data);
									sendRedirect("{$CONFIG['BASE_DOMAIN']}{$ref['page']}");
								}else{
									sendRedirect("{$CONFIG['BASE_DOMAIN']}{$CONFIG['DINAMIC_MODULE']}");
								}
								
								
								
							}
						}
						//sendRedirect("{$CONFIG['BASE_DOMAIN']}{$CONFIG['DINAMIC_MODULE']}");
						return $this->out(TEMPLATE_DOMAIN_WEB . '/login_message.html');
						die();
					
				}
			}
		
		// $this->View->assign('choose_btn',$this->setWidgets('choose_btn'));
		
		
		$this->View->assign('login_form',$this->setWidgets('login_form'));
			
		
		return $this->View->toString(TEMPLATE_DOMAIN_WEB .'/login.html');
	}
	
	function brand()
	{
		global $CONFIG;
		if (($this->_p('submit')) AND ($this->_p('token') == 1)){
			
			$saveSurvey = $this->loginHelper->saveSurvey();
			if ($saveSurvey){
				sendRedirect("{$CONFIG['BASE_DOMAIN']}login/newpassword");
				die();
			}
		}
		$this->View->assign('register_survey',$this->setWidgets('survey'));
		// pr('ini halaman unverified');exit;
		return $this->View->toString(TEMPLATE_DOMAIN_WEB .'login.html');
	}
	
}
?>