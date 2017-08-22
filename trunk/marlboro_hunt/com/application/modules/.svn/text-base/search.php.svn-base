<?php
class search extends App{

	
	function beforeFilter(){
	
		$this->contentHelper = $this->useHelper('contentHelper');
		$this->searchHelper = $this->useHelper('searchHelper');
		$this->userHelper = $this->useHelper('userHelper');
		global $LOCALE,$CONFIG;
		$this->assign('basedomain', $CONFIG['BASE_DOMAIN']);
		$this->assign('assets_domain', $CONFIG['ASSETS_DOMAIN_WEB']);
		
		$this->assign('pages', strip_tags($this->_g('page')));
		$this->assign('locale', $LOCALE[1]);
		
	}
	
	function main(){
		
		$keywords =  strip_tags($this->_p('q'));
		
		$this->View->assign('side_banner',$this->setWidgets('side_banner'));
		$this->assign('keywords',$keywords);
		$result = $this->searchHelper->search(null,10,null,$keywords);
		$this->assign('total', intval($result['total']));
		$this->assign('start', intval($this->_request('start')));
		if($result){
			$this->assign('article', $result['result']);		
		}
			$this->log('surf','search');
		return $this->View->toString(TEMPLATE_DOMAIN_WEB .'/apps/search.html'); 
	}
	
	function friends(){
		
		$data = $this->userHelper->getSearchFriendPursuit();
		if($data){
			$index = 0;
			foreach ($data as $value){
				$data[$index]['id'] = $value['id'];
				$data[$index]['name'] = $value['name'];
				if (($value['image_profile']) and ($value['photo_moderation'] == 1)){
					$data[$index]['image_profile'] = $value['image_profile'];
				}else{
					$data[$index]['image_profile'] = "";
				}
				
				// $data[$index]['photo_moderation'] = $value['photo_moderation'];
				$index++;
			}
					
			print json_encode(array('status'=>true, 'rec'=>$data));
		}else{
			print json_encode(array('status'=>false));
		}
		
		exit;
	
	}
	
	function tradingfloor(){
		
		$data = $this->contentHelper->getSearchTradingFloor();
		if($data){
			print json_encode(array('status'=>true, 'rec'=>$data));
		}else{
			print json_encode(array('status'=>false));
		}
		
		exit;
	
	}
	
	function redirecting(){
		global $CONFIG;
		$link  = strip_tags($this->_g('link'));
		$keywords = strip_tags($this->_g('keywords'));
		$contentid = intval($this->_g('contentid'));
		$fromwhere = intval($this->_g('fromwhere'));

		if(strpos($link,"|")) {
			$link = preg_replace("/\|/","/",$link);
			if(strpos($link,"_")) $link = preg_replace("/_/",".",$link); 
		}

		$result = $this->searchHelper->addKeywords($keywords,$contentid,$fromwhere,$link);
		if($result) sendRedirect($link);
		else sendRedirect($CONFIG['BASE_DOMAIN']."search/".$keywords);
		exit;
	}
	
	function ajax(){
		$keywords =  strip_tags($this->_p('q'));		
		$result = $this->searchHelper->search(null,10,null,$keywords);	
		print json_encode($result);exit;		
		
	}
	
}
?>