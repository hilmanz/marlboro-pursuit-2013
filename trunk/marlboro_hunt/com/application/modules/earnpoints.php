<?php
class earnpoints extends App{
	function beforeFilter(){
	
		$this->contentHelper = $this->useHelper('contentHelper');
		$this->activityHelper = $this->useHelper('activityHelper');
		$this->userHelper = $this->useHelper('userHelper');
		$this->newsHelper = $this->useHelper('newsHelper');
		$this->messageHelper = $this->useHelper('messageHelper');
		$this->uploadHelper  = $this->useHelper('uploadHelper');
		$this->eventHelper = $this->useHelper('eventHelper');
		global $LOCALE,$CONFIG;
		$this->assign('basedomain', $CONFIG['BASE_DOMAIN']);
		$this->assign('assets_domain', $CONFIG['ASSETS_DOMAIN_WEB']);
		
		$this->assign('locale', $LOCALE[1]);
		
	}
	
	function main()
	{
		$this->log('surf', 'earnpoints');
		$getMerchandise = $this->contentHelper->getRedeemPhrase();
		// pr($getMerchandise);
		if ($getMerchandise){
			$redeem = $this->contentHelper->redeemPrize();
			
			if ($redeem){
				if ($redeem['dont']){
					$this->View->assign('redeemdont',true);
				}
				if ($redeem['dontbe']){
					$this->View->assign('redeemdontbe',true);
				}
				if ($redeem['dontbea']){
					$this->View->assign('redeemdontbea',true);
				}
			}
			
			// pr($getMerchandise);
			$this->View->assign('merchanDont',$getMerchandise['DONT']);
			$this->View->assign('merchanDontbe',$getMerchandise['DONTBE']);
			$this->View->assign('merchanDontbea',$getMerchandise['DONTBEA']);
		}
		return $this->View->toString(TEMPLATE_DOMAIN_WEB .'widgets/the_pursuit_earnpoints.html');
	}
	
	function prize()
	{
		
		$this->log('surf','pursuit_prize');
		
		$myMerchandise = array();
		
		$redeem = $this->contentHelper->getMerchandise();
		$mymerchan = $this->contentHelper->getMyMerchandise();
		// pr($redeem);
		// pr($mymerchan);
		
		if ($mymerchan){
			foreach ($mymerchan as $val){
				$myMerchandise[] = $val['merchandiseid'];
			}
			
		}
		
		foreach ($redeem as $key => $val){
			if (in_array($val['id'], $myMerchandise))
			{
				$redeem[$key]['has'] = true;
			}else{
				$redeem[$key]['has'] = false;
			}
		}
		
		// pr($redeem);
		$this->View->assign('merchandise',$redeem);
		
		
		
		
		$this->View->assign('popup_redeem_1',$this->setWidgets('popup_redeem_1'));
		$this->View->assign('popup_redeem_2',$this->setWidgets('popup_redeem_2'));
		$this->View->assign('popup_redeem_3',$this->setWidgets('popup_redeem_3'));
		$this->View->assign('popup_redeem_claim',$this->setWidgets('popup_redeem_claim'));
		
		return $this->View->toString(TEMPLATE_DOMAIN_WEB .'apps/pursuit-prize.html');
	}
	
	function checkBeforeRedeem()
	{
		if ($this->_p('checkbefore')){
			
			$idMerchan = intval($this->_p('idMerchan'));
			if ($idMerchan<=0) {print json_encode(array('status'=>false)); exit;}
			$getMyPoint = $this->userHelper->getUserPoint();
			$needPoint = $this->contentHelper->merchNeedPoint($idMerchan);
			
			if (intval($getMyPoint['point']) >= intval($needPoint['need_point'])){
				print json_encode(array('status'=>true, 'rec'=>$idMerchan));
			}else{
				print json_encode(array('status'=>false));
			}
			
		}
		
		// if ()
		exit;
	}
	
	function sendMailAfterRedeem($template=null)
	{
		
		if ($template == null) return false;
		
		$id = $this->user->id;
		$data = $this->userHelper->getUserProfileByID($id);
		$userdata = false;
		if ($data){
			$userdata['email'] = $data['email'];
			$userdata['firstname'] = $data['name'];
			$userdata['lastname'] = $data['last_name'];
			
			
			$this->userHelper->getEmailTemplate($template,$userdata,'send');
			return true;
		}else{
			return false;
		}
		
	}
	
	function ajaxconfirmRedeem()
	{
		global $LOCALE;
		
		$redeem = $this->contentHelper->redeemMerchConfirm();
		// pr($redeem);
		
		if ($redeem){
			// $this->log('redeemcode', 'Success redeem phrase DONT');
			
			print json_encode(array('status'=>true, 'rec'=>$redeem));
		}else{
		
			print json_encode(array('status'=>false));
			
		}
		exit;
		
	}
	
	function ajaxRedeem()
	{
		
		$redeem = $this->contentHelper->getMerchandise();
		if ($redeem){
			
			print json_encode(array('status'=>true, 'rec'=>$redeem[0]));
		}else{
		
			print json_encode(array('status'=>false));
			
		}
		exit;
	}
	
}
?>