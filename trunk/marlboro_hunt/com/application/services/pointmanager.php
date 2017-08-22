<?php
class pointmanager extends ServiceAPI{
			

	function beforeFilter(){
		
		// $this->inboundHelper = $this->useHelper('inboundHelper');
		$this->botHelper = $this->useHelper('botHelper');
		
		global $LOCALE,$CONFIG;
		$this->assign('basedomain', $CONFIG['BASE_DOMAIN']);
		$this->assign('assets_domain', $CONFIG['ASSETS_DOMAIN_WEB']);

		
	}
	
	
	function runservice(){
		global $CONFIG;
		// pr($_GET);
		if($this->_g('userid')){
			$userid = intval($this->_g('userid'));
			$updateUser = $this->botHelper->memberUpdateStatus($userid);
			if ($updateUser){
				$recoverPass = $this->botHelper->setPointByUser("SUCCES REGISTER", "registerpoint", 3,null,$userid);
				print json_encode(array('status'=>true));
			}else{
				print json_encode(array('status'=>false));
			}
		}
		
		// print json_encode(array('status'=>'no data'));
		
		exit;		
	}
}
?>
