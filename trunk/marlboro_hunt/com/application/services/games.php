<?php
class games  extends ServiceAPI{
			

	function beforeFilter(){
		
		$this->contentHelper = $this->useHelper('contentHelper');
		$this->gamesHelper = $this->useHelper('gamesHelper');
	
		global $LOCALE,$CONFIG;
		$this->assign('basedomain', $CONFIG['BASE_DOMAIN']);
		$this->assign('assets_domain', $CONFIG['ASSETS_DOMAIN_WEB']);
		$this->assign('locale', $LOCALE[1]);		
		$this->assign('pages', strip_tags($this->_g('page')));		
		
	}
	
	function getUser(){		
		// $this->Request->setParam('uid',1);
		$user = $this->userHelper->getUserProfile(); 
		if($user){
			$profile['id'] = $user['id'];
			$profile['name'] = $user['name'];
			$profile['last_name'] = $user['last_name'];
		}else return false;
		$lastoken = $this->gamesHelper->getLastOldToken(); 
		$data['profile'] = $profile;	
		$data['playingat'] = date("YmdHi").$lastoken;	
		return $data;
	}
	
		
	function savedata(){
		GLOBAL $CONFIG;
		$data['result'] = false;
		$data['letter'] = false;
		$data['message'] = "failed";
		$result =  $this->gamesHelper->checkstatus();
		
		if(!$this->gamesHelper->checkuserplaygames()) {	
		
				$data['message'] = "already get letter this day";
		}
		
		if($result){ 
			$getMasterCode = $this->contentHelper->getMasterCode();
			$mastercode = false;
			if ($getMasterCode){
				foreach ($getMasterCode as $value){
							$mastercode[$value['id']] = $value['codename'];
						}
			}
			
			$data['result'] = true;
			$data['letter'] = @$mastercode[$result['codeid']];			
			$data['message'] = "success";
			
		}
		
		
		return $data;
	}
	
	
}
?>
