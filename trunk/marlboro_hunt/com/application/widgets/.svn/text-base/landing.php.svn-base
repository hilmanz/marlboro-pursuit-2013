<?php
class landing{
	
	function __construct($apps=null){
		$this->apps = $apps;	
		global $LOCALE,$CONFIG;
		$this->apps->assign('basedomain', $CONFIG['BASE_DOMAIN']);
		$this->apps->assign('assets_domain', $CONFIG['ASSETS_DOMAIN_WEB']);
		$this->apps->assign('locale',$LOCALE[$this->apps->lid]);
	}

	function main(){
		global $CONFIG;
		$data = $this->apps->session->getSession($CONFIG['SESSION_NAME'],"WEB");	
		
		if ($data){
			if($data->usertype==2){
					if ($data->login_count == 1) {
						$this->apps->log('home', 'video welcome');
						if(!isset($_SESSION['popvideowelcome']))$this->apps->View->assign('popvideowelcome',true);
						$_SESSION['popvideowelcome'] = true;
					}
					if ($data->login_count == 2) {
						$this->apps->log('home', 'video labor');
						if(!isset($_SESSION['popvideolabor']))$this->apps->View->assign('popvideolabor',true);
						$_SESSION['popvideolabor'] = true;
					}
			}else{
				if ($data->login_count == 0) {
						$this->apps->log('home', 'video welcome');
						if(!isset($_SESSION['popvideowelcome']))	$this->apps->View->assign('popvideowelcome',true);
						$_SESSION['popvideowelcome'] = true;
					}
				if ($data->login_count == 1) {
						$this->apps->log('home', 'video labour');
						if(!isset($_SESSION['popvideolabor']))$this->apps->View->assign('popvideolabor',true);
						$_SESSION['popvideolabor'] = true;
					}
			}
		}
		

		return $this->apps->View->toString(TEMPLATE_DOMAIN_WEB ."widgets/landing_phase3.html"); 
		
	}
}
?>