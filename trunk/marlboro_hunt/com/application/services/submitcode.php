<?php
class submitcode extends ServiceAPI{
		
	function beforeFilter(){
			$this->contentHelper = $this->useHelper('contentHelper');
			$this->activityHelper = $this->useHelper('activityHelper');
			$this->userHelper = $this->useHelper('userHelper');
			$this->newsHelper = $this->useHelper('newsHelper');
			
			
	}
	
	function main(){
		global $logger;
		
		$data['status'] = false;
		$data['data']= false;
		$data['data']['notification']= false;
		$_captcha = intval($this->_p('captcha'));
		$_salting = strip_tags($this->_p('salt'));
		$logcapt = md5($_captcha);
		$mycaptcha = sha1(md5($_captcha).date('Ymd').$this->user->id);
		$getPursuitUpdate = $this->activityHelper->getNotification();
		// pr($this->user->id);
		// token = sha1(userid+date+"{"+code+"}" );
		$logger->log("yourcaptcha : {$logcapt} , mycaptcha : {$mycaptcha} , salting :{$_salting} ");
		$logger->log("captcha : {$_captcha} , date : ".date('Ymd')." , userid :{$this->user->id} ");
		
		if($_captcha!=0){
		// sha1(session+date('YmdHi')+userid);
		
			$_valid = ( $_salting == $mycaptcha ) ? true : false;
						
			if($_valid) {
				$logger->log(" captcha approve ");
				$inputCode = $this->contentHelper->inputCodeFromMenu();
				
				$code = strip_tags($this->_p('code'));
				$logger->log(" try to insert code {$code}");
				if ($inputCode){
					$logger->log(" insert code success ");
					$getMasterCode = $this->contentHelper->getMasterCode();
					$logger->log(" try to get code master ");
					if ($getMasterCode){
						$logger->log(" success get code master ");
						$mastercode = false;
						$logger->log(" try to remap master code success ");
						foreach ($getMasterCode as $value){
							$mastercode[$value['codename']] = $value['id'];
						}
					}
					$logger->log(" try to remap master code success ");
					if($mastercode){
						$logger->log(" success get remap master code success ");
						$data['status'] = 200;
						$data['data']['success'] = 1;
						$data['data']['letter'] = $mastercode[$inputCode];
						$data['data']['notification']= count($getPursuitUpdate['content']);
					}
				}else{
					$data['status'] = 200;
					$data['data']['success'] = 0;
					$data['data']['letter'] = '';
					$data['data']['message'] = 'error code, please insert the right code';
					$data['data']['notification']= count($getPursuitUpdate['content']);
				}
			}else{
				$data['status'] = 200;
				$data['data']['success'] = 0;
				$data['data']['letter'] = '';
				$data['data']['message'] = 'wrong captcha';
				$data['data']['notification']= count($getPursuitUpdate['content']);
			}
		}else{
				$data['status'] = 404;
				$data['data']['success'] = 0;
				$data['data']['letter'] = '';
				$data['data']['message'] = 'please first insert captcha';
				$data['data']['notification']= count($getPursuitUpdate['content']);
		}
		
		if($data['status']) return $data;
		
		print  $this->error_400();exit;
	
		
	}
	
	function captcha(){			
		
		global $CONFIG;
		print "<img src='{$CONFIG['BASE_DOMAIN_PATH']}assets/media/captcha.php' />";
		exit;
		
	}
}
?>