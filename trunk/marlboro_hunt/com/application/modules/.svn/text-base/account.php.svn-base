<?php
class account extends App{
	function beforeFilter(){
	
		$this->contentHelper = $this->useHelper('contentHelper');
		$this->activityHelper = $this->useHelper('activityHelper');
		$this->userHelper = $this->useHelper('userHelper');
		$this->loginHelper = $this->useHelper('loginHelper');
		$this->registerHelper = $this->useHelper('registerHelper');
		$this->uploadHelper = $this->useHelper('uploadHelper');
		$this->botHelper = $this->useHelper('botHelper');
		
		global $LOCALE,$CONFIG;
		$this->assign('basedomain', $CONFIG['BASE_DOMAIN']);
		$this->assign('assets_domain', $CONFIG['ASSETS_DOMAIN_WEB']);
		
		$this->assign('locale', $LOCALE[1]);
		
	}
	
	
	function main()
	{
		
		global $CONFIG;
		if ($this->_p('edit_account')){
			
			
			$update = $this->userHelper->updateDataUser();
			if ($update){
				if($update['result']==true){				
					$this->log('surf', 'update_account');
					sendRedirect("{$CONFIG['BASE_DOMAIN']}account/brands");
					return $this->out(TEMPLATE_DOMAIN_WEB . '/login_message.html');
					die();
					
				}else{					
					$this->View->assign('resultmessage',$update['message']);
				}
				
			}
		}
		
		$mystatement = $this->userHelper->getMyStatement(3,true);
		$liststatement = $this->userHelper->getMyStatement(10);
		$this->View->assign('mystatement',$mystatement);
		$this->View->assign('liststatement',$liststatement);
		$this->View->assign('account_content',$this->setWidgets('account_content'));
		$this->View->assign('popup_deactivate_account',$this->setWidgets('popup_deactivate_account'));
		$this->View->assign('popup_upload_photo_profile',$this->setWidgets('popup_upload_photo_profile'));
		// $this->View->assign('account_edit',$this->setWidgets('account_edit'));
		return $this->View->toString(TEMPLATE_DOMAIN_WEB .'/apps/account_pages.html');
	}
	
	function changePassword()
	{
		global $CONFIG;
		
		
		if ($this->_p('login')){
			
			$updateUser = $this->loginHelper->updateLoginUser();
			if ($updateUser){
				$this->log('surf','change password');
				$data = $this->session->getSession($CONFIG['SESSION_NAME'],"WEB");
				$this->log('login','welcome');
				
				if ($data->login_count == 0){
				// pr($data);exit;
					
					sendRedirect("{$CONFIG['BASE_DOMAIN']}home");
					
				}else{
					
					sendRedirect("{$CONFIG['BASE_DOMAIN']}{$CONFIG['DINAMIC_MODULE']}");
				}
				return $this->out(TEMPLATE_DOMAIN_WEB . '/login_message.html');
				die();
				
			}
			$this->assign("msg","failed to login..");
		}
		$this->View->assign('login_new_password',$this->setWidgets('login_new_password'));
		// return $this->View->toString(TEMPLATE_DOMAIN_WEB .'/login.html');
		return $this->View->toString(TEMPLATE_DOMAIN_WEB .'/apps/account_pages.html');
	}
	
	function edit_()
	{
		return false;
		global $CONFIG;
		
		if ($this->_p('edit_account')){
			
			
			$update = $this->userHelper->updateDataUser();
			if ($update){
				if($update['result']==true){				
					$this->log('surf', 'update_account');
					sendRedirect("{$CONFIG['BASE_DOMAIN']}account/brands");
					return $this->out(TEMPLATE_DOMAIN_WEB . '/login_message.html');
					die();
					
				}else{					
					$this->View->assign('resultmessage',$update['message']);
				}
				
			}else{
				sendRedirect("{$CONFIG['BASE_DOMAIN']}account");
				return $this->out(TEMPLATE_DOMAIN_WEB . '/login_message.html');
				die();
			}
		}
		$this->View->assign('account_content',$this->setWidgets('account_content'));
		
		return $this->View->toString(TEMPLATE_DOMAIN_WEB .'/apps/account_pages.html');
	}
	
	function getProvinsibyAjax()
	{
		$idProvince = intval($this->_p('id'));
		
		$province = $this->contentHelper->getCity(array('idProvince'=>$idProvince));
		print json_encode($province);exit;
	}
	
	function brands()
	{
		global $CONFIG;
		$this->View->assign('account_brands',$this->setWidgets('account_brands'));
		
		if (($this->_p('submit')) AND ($this->_p('tokenQuiz') == 1)){
			
			$saveBrands = $this->userHelper->saveUserBrands();
			if ($saveBrands){
				$this->log('surf', 'update_brands');
				
				sendRedirect("{$CONFIG['BASE_DOMAIN']}account/giid");
				return $this->out(TEMPLATE_DOMAIN_WEB . '/login_message.html');
				die();
			}
		}
		
		
		
		return $this->View->toString(TEMPLATE_DOMAIN_WEB .'/apps/account_pages.html');
	}
	
	function giid()
	{
		global $CONFIG;
		$this->View->assign('account_giid',$this->setWidgets('account_giid'));
		
		if (($this->_p('submit')) AND ($this->_p('token') == 1)){
			
			sendRedirect("{$CONFIG['BASE_DOMAIN']}account");
			return $this->out(TEMPLATE_DOMAIN_WEB . '/login_message.html');
			die();
		}
		
		
		return $this->View->toString(TEMPLATE_DOMAIN_WEB .'/apps/account_pages.html');
	}
	
	

	function deactivate(){
		global $CONFIG;
		$data = $this->userHelper->deactivate();
		print json_encode($data);
		exit;
	}
	
	function changesphoto(){
	
		global $CONFIG;
			$path = $CONFIG['LOCAL_PUBLIC_ASSET']."user/photo/";
			$data['result'] = false;
			$data['filename'] = false;
			if (isset($_FILES['image'])&&$_FILES['image']['name']!=NULL) {
				if (isset($_FILES['image'])&&$_FILES['image']['size'] <= 20000000) {
					$res = $this->uploadHelper->uploadThisImage($_FILES['image'],$path,220,true);
					if ($res['arrImage']!=NULL) {
						$result =$this->userHelper->updatephoto($res['arrImage']);
						if($result) {
							$this->log('update profile photo');
							$data['result'] = true;
							$data['filename'] = $res['arrImage'];
							$data['nosave'] = false;
						} 
					}
				}
			}
		
		// sendRedirect("{$CONFIG['BASE_DOMAIN']}account");
		print json_encode($data);
		exit;
	}
	function savetempphoto(){
	
		global $CONFIG;
			$path = $CONFIG['LOCAL_PUBLIC_ASSET']."user/photo/";
			$data['result'] = false;
			$data['filename'] = false;
			if (isset($_FILES['image'])&&$_FILES['image']['name']!=NULL) {
				if (isset($_FILES['image'])&&$_FILES['image']['size'] <= 20000000) {
					$res = $this->uploadHelper->uploadThisImage($_FILES['image'],$path,220,true);
					if ($res['arrImage']!=NULL) {
												
							$data['result'] = true;
							$data['filename'] = $res['arrImage'];
							$data['nosave'] = true;
						
					}
				}
			}
		
		// sendRedirect("{$CONFIG['BASE_DOMAIN']}account");
		print json_encode($data);
		exit;
	}
	
	function cameraphoto(){
		global $CONFIG;
		$data =  $this->uploadHelper->putcontent("user/photo/");
		if($data['result']){
			$result =$this->userHelper->updatephoto($data);
			
		}
		print json_encode($data);exit;
	}
	
	function ajax(){
		$needs = strip_tags($this->_p('needs'));
		$data = false;
		if($needs=='setstatement') $data = $this->userHelper->setMyStatement();
		if($needs=='addstatement') $data = $this->userHelper->addMyStatement();
		
		$this->botHelper->setPointgift('STATEMENT', 'statementpoint',2);
		
		print json_encode($data);exit;
	}
	
}
?>