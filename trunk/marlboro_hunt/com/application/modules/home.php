<?php
class home extends App{
	
	
	function beforeFilter(){
	
		$this->contentHelper = $this->useHelper('contentHelper');
		$this->activityHelper = $this->useHelper('activityHelper');
		$this->userHelper = $this->useHelper('userHelper');
		global $LOCALE,$CONFIG;
		$this->assign('basedomain', $CONFIG['BASE_DOMAIN']);
		$this->assign('assets_domain', $CONFIG['ASSETS_DOMAIN_WEB']);
		
		$this->assign('locale', $LOCALE[1]);
		
	}
	
	function main(){
		
		$this->View->assign('popup_game',$this->setWidgets('popup_game'));
		$this->View->assign('landing',$this->setWidgets('landing'));
		
		$hastracker = 0;
		$openwelcome = 'not';
		
		
		$sql ="SELECT COUNT(*) total FROM tbl_activity_log WHERE user_id={$this->user->id} AND  action_value like '%tracker event weekly on home 3%' LIMIT 1 ";
		// pr($sql);
		$qData = $this->fetch($sql);
		// pr($qData);
		
		if($qData)$hastracker = intval($qData['total']);
		if($hastracker==0) {
			$openwelcome =  'ok';			 
			$this->log('surf',"tracker event weekly on home 3");
			sleep(1);
		}	
		$this->assign('openwelcome',$openwelcome);
		
		if(strip_tags($this->_g('page'))=='home') $this->log('surf','home');
		return $this->View->toString(TEMPLATE_DOMAIN_WEB .'/home.html');
		
	}
	
	function ajax(){
		if(strip_tags($this->_p('action'))=="a360activity") {
			$maxrecord = 2;
			$start = intval($this->_p('start'));
			$data = $this->activityHelper->getA360activity($start,$maxrecord);
			print json_encode($data['content']); exit;
		}
		
		if ($this->_p('popup') !=""){
			$id = $this->_p('popup');
			if ($id == 1){
				$this->log('surf','video-kickoff');
			}
			
			if ($id == 2){
				$this->log('surf','DYO page');
			}
			
			if ($id == 3){
				$this->log('surf','video-laborday');
			}
			
		}
		
		if ($this->_p('deletemessage')){
			$deletemessage = $this->messageHelper->deleteMessage();
			if ($deletemessage){
				print json_encode(array('status'=>true));
			}else{
				print json_encode(array('status'=>false));
			}
			
		}
		
		exit;
	}
	
	function newsfeed()
	{
	
		if ($this->_p('feed')){
			$getNews = $this->activityHelper->getNewsFeed();
			if ($getNews){
				print json_encode(array('status'=>true, 'rec'=>$getNews));
			}else{
				print json_encode(array('status'=>false));
			}	
		}
		
		
		exit;
	}
	
	function getPointNotif()
	{
		$getPoint =  $this->userHelper->getUserPoint();
		
		if (isset($getPoint['point'])) print json_encode(array('data'=>$getPoint['point']));
		else print json_encode(array('data'=>0));
		
		exit;
	}
	
	function task()
	{
		if ($this->_p('getGiftBirthday')){
			
			
			$getGift = $this->userHelper->getBirthdayGift();
			$province = $this->contentHelper->getProvince();
			// pr($getGift);
			if ($getGift){
				print json_encode(array('status'=>true,'rec'=>$getGift,'provinceList'=>$province));
			}else{
				print json_encode(array('status'=>false));
			}
			
		}
		
		if ($this->_p('birthdayGift')){
			
		
			$getClaim = $this->userHelper->getMyClaimBirthday();
			if ($getClaim){
				print json_encode(array('status'=>true));
			}else{
				print json_encode(array('status'=>false));
			}
			
		}
		
		exit;
	}
	
}
?>