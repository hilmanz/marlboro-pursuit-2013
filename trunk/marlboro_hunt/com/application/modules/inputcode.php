<?php
class inputcode extends App{
	
	
	function beforeFilter(){

		global $LOCALE,$CONFIG;
		$this->contentHelper = $this->useHelper('contentHelper');
		// $this->uploadHelper = $this->useHelper('uploadHelper');
		// $this->eventHelper = $this->useHelper('eventHelper');
		$this->assign('basedomain', $CONFIG['BASE_DOMAIN']);
		$this->assign('assets_domain', $CONFIG['ASSETS_DOMAIN_WEB']);
		
	}
	
	function main(){
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
		
		return $this->View->toString(TEMPLATE_DOMAIN_WEB .'/apps/input-code.html');
	}
	
	function ajax()
	{
		if ($this->_p('inputCode')){
						
			$_captcha = intval($this->_p('captcha'));
			$_valid = (md5($_captcha) == $_SESSION['simplecaptcha']) ? true : false;
			$_SESSION['simplecaptcha'] = "bed" . rand(00000000,99999999) . "bed";

			if($_valid) {
				$inputCode = $this->contentHelper->inputCodeFromMenu();
				
				if ($inputCode){
					if(strlen($inputCode) >1){
						
						$type = substr($inputCode, 0,1).'2';
					}else{
						$letterDouble = array('A','B','E');
						if (in_array($inputCode, $letterDouble)){
							$type = $inputCode.'1';
						}else{
							$type = $inputCode;
						}
						
						
					}
					print json_encode(array('status'=>true, 'result'=>$inputCode, 'type'=>$type));
				}else{
					print json_encode(array('status'=>false,'result'=>'wrong wrong'));
				}
				
			}else print json_encode(array('status'=>false,'result'=>'wrong captcha'));
		}
		
		exit;
	}
	
	
}
?>