<?php
class login_form{
	
	function __construct($apps=null){
		$this->apps = $apps;	
		global $LOCALE,$CONFIG;
		$this->apps->assign('basedomain', $CONFIG['BASE_DOMAIN']);
		$this->apps->assign('assets_domain', $CONFIG['ASSETS_DOMAIN_WEB']);
		$this->apps->assign('locale',$LOCALE[$this->apps->lid]);
	}

	function main(){
		global $LOCALE,$CONFIG;
		$this->apps->assign('basedomain', $CONFIG['BASE_DOMAIN']);
		// pr($_GET);
		
		// $data = array('page'=>'news/detail','token'=>'12345');
		// $ref = urlencode64(serialize($data));
		// pr($ref);exit;
		// l-pPJmnFQ0sbZZtIS4A2OxVtAniC81_xMYiqC9n1VxqYeGXM5wA7KPgHrIzmpOHNlKDXJqqV7HdUj0nuYUz9fw..
		// l-pPJmnFQ0sbZZtIS4A2O3AFVzskQ3nI7QL5XIOEv0ff-KK4uW0YnH1J0SCsskMD-AesjOak4c2UoNcmqpXsd1SPSe5hTP1_
		// l-pPJmnFQ0sbZZtIS4A2O42Rksa2gfUl7QL5XIOEv0fkw6qkHE0kknooNcy3JocGdsdEkSea_nE606dUfyumLKeiFaBOfqeR
		if ($this->apps->_g('ref')) $this->apps->assign('ref',strip_tags($this->apps->_g('ref')));
		return $this->apps->View->toString(TEMPLATE_DOMAIN_WEB ."widgets/login-form.html"); 
		
	}
}
?>