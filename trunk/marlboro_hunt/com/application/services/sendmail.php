<?php
class sendmail extends ServiceAPI{
		
	function beforeFilter(){
			$this->contentHelper = $this->useHelper('contentHelper');
			$this->activityHelper = $this->useHelper('activityHelper');
			$this->outboundHelper = $this->useHelper('outboundHelper');
			$this->botHelper = $this->useHelper('botHelper');
			
			
	}
	
	function main(){
		global $logger;
		
		// for testing only 
		
		// $getUserMail[] = array('letter'=>'DONT', 'email'=>'ovan89@gmail.com', 'name'=>'Ovan', 'last_name'=>'Pulu');
		$getUserMail = $this->botHelper->sendUserMailviaServices();
		// pr($getUserMail);
		// exit;
		if ($getUserMail){
			
			// pr($getUserMail);
			foreach ($getUserMail as $val){
				$template = 'redeem'.strtolower($val['letter']);
				
				$userdata['email'] = $val['email'];
				$userdata['firstname'] = $val['name'];
				$userdata['lastname'] = $val['last_name'];
				
				$sendMail = $this->outboundHelper->getEmailTemplate($template,$userdata,'send');
				
				
				$logger->log('send mail to '.$val['email']);
				$logger->log($sendMail['addRecipeForSilverPop']);
			
				
			}
			
			print  json_encode(array('status'=>true));exit;
		}else{
			echo 'empty param';
			print  $this->error_400();exit;
		}
		
		
		// echo 'ada';
		
	
		
	}
	
}
?>