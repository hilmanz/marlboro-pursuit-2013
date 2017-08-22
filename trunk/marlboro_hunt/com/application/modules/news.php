<?php
class news extends App{
	function beforeFilter(){
		
		$this->contentHelper = $this->useHelper('contentHelper');
		$this->activityHelper = $this->useHelper('activityHelper');
		$this->userHelper = $this->useHelper('userHelper');
		$this->newsHelper = $this->useHelper('newsHelper');
		$this->uploadHelper = $this->useHelper('uploadHelper');
		global $LOCALE,$CONFIG;
		$this->assign('basedomain', $CONFIG['BASE_DOMAIN']);
		$this->assign('assets_domain', $CONFIG['ASSETS_DOMAIN_WEB']);
			$this->assign('pages', strip_tags($this->_g('page')));
		$this->assign('locale', $LOCALE[1]);
	}
	
	function main(){
		
		$this->View->assign('popup_game',$this->setWidgets('popup_game'));
		return $this->View->toString(TEMPLATE_DOMAIN_WEB .'/apps/news-home.html');
		
	}
	
	function landing(){
		$this->log('surf', 'news');
		
		$landingData = $this->newsHelper->newsEventLanding();
		// pr($landingData);
		if ($landingData){
			$this->View->assign('landingData',$landingData);
		}
		
		$this->View->assign('popup_game',$this->setWidgets('popup_game'));
		return $this->View->toString(TEMPLATE_DOMAIN_WEB .'/apps/news-landing.html');
	}
	
	function video(){
		
		$this->log('surf', 'be marlboro video');
		return $this->View->toString(TEMPLATE_DOMAIN_WEB .'/apps/video-pages.html');
	}
	
	function decision(){
		// $this->log('surf', 'be marlboro video');
		return $this->View->toString(TEMPLATE_DOMAIN_WEB .'/apps/decision_pages.html');
	}
	
	function detail(){

		$this->log('surf',"list news");
		//widget list
		$this->View->assign('news_list',$this->setWidgets('news_list'));
		// widget detail
		// $this->View->assign('news_detail',$this->setWidgets('news_detail'));
		
		return $this->View->toString(TEMPLATE_DOMAIN_WEB .'/apps/news.html');
	}
	
	function detailDevel(){

		$this->log('surf',"list news");
		//widget list
		$this->View->assign('news_list',$this->setWidgets('news_list_devel'));
		// widget detail
		// $this->View->assign('news_detail',$this->setWidgets('news_detail'));
		
		return $this->View->toString(TEMPLATE_DOMAIN_WEB .'/apps/news.html');
	}
	
	function showPopup()
	{
		
		if ($this->_p('isvalid')){
		
			$cid = intval($this->_p('cid'));
			$this->log('read article',"news_{$cid}");
			$getNews = $this->newsHelper->newsDetail();
			// pr($getNews);
			if($getNews){
				
				$getComment = $this->contentHelper->getComment($cid);
				
				print json_encode(array('status'=>true, 'rec'=>$getNews[0], 'comment'=>$getComment[$cid]));
			}else{
				print json_encode(array('status'=>false));
			}
			
		}
		
		exit;
	}
	
	function comment()
	{
		
		if (!$this->_p('isvalid')){print json_encode(array('status'=>false)); exit;}
		
		$cid = intval($this->_p('cid'));
		
		$comment = strip_tags($this->_p('comment'));
		$storeComment = $this->contentHelper->addComment();
		$getComment = $this->contentHelper->getComment($cid);
		// pr($getComment);
		$this->log('add comments', $this->user->name. " comment article". $cid);
		// pr($storeComment);
		if ($storeComment){
			
			// $this->botHelper->setPointgift('COMMENT', "commentpoint_{$storeComment['cid']}_",2);
			
			if ($getComment){
				// pr($getComment[$cid]);
				print json_encode(array('status'=>true, 'rec'=>$getComment[$cid]));
			}else{
				print json_encode(array('status'=>false));
			}
			
			
			// pr($data[0]);
			// print json_encode(array('status'=>true, 'name'=>$this->user->name, 'comment'=>$comment));
			
		}else{
			print json_encode(array('status'=>false));
		}
		
		exit;
	}
}
?>