<?php
class content extends App{

	
	function beforeFilter(){
		global $LOCALE,$CONFIG;
		$this->assign('basedomain', $CONFIG['BASE_DOMAIN']);
		$this->assign('assets_domain', $CONFIG['ASSETS_DOMAIN_WEB']);
		$this->contentHelper = $this->useHelper('contentHelper');

		$this->searchHelper = $this->useHelper('searchHelper');
		
	}
	
	function detail(){		
		$article = $this->contentHelper->getDetailArticle();		
		
		//pr($article);exit;	
		$this->log('read article',intval($this->_request('id')));
		$datacomment = false;
		$commentCount = 0;
		if($article) 
		{
			$commentCount = $this->contentHelper->getComment(null,true);
			$comment  = $this->contentHelper->getComment();
			//pr($comment);exit;
			if($comment){
				
				foreach($comment as $val){
					$datacomment = $val;
				}
			}
			$article['result'][0]['posted_date'] = date('m/d/Y H:i',strtotime($article['result'][0]['posted_date']));
			$data['result'] =  true;
			$data['article'] = $article['result'][0];
			$data['total'] = $commentCount[$this->_request('id')];
			if($datacomment) $data['comment'] = $datacomment;
			else $data['comment'] = false;
		}else {
			$data['result'] = false;
		}		
		 	
		print json_encode($data,JSON_FORCE_OBJECT);
		exit;
	}
	
	function comment(){
		$data['result'] = false;
		$data['comment'] = false;
		
		$data['name'] = $this->user->name;
		$data['img'] = $this->user->img;
		$data['date'] = date("Y-m-d H:i:s");
		$cid = intval($this->_p('cid'));
		$res = $this->contentHelper->addComment();
		if($res){
			
			
			$this->botHelper->setPointgift('COMMENT', "commentpoint_{$cid}_",2);
			// sleep(1);
			$this->log('add comments', $this->user->name. " comment article ". intval($this->_p('cid'))); 
			$data['result'] = true;
			$data['comment'] = strip_tags($this->_p('comment'));
			 
		}
		print json_encode($data,JSON_FORCE_OBJECT);
		exit;
	}
	
	function favorite(){
		$data = $this->contentHelper->saveFavorite();
		if($data)$this->log("add favorite", intval($this->_p('cid')));
		print json_encode($data,JSON_FORCE_OBJECT);
		exit;
	}
	
	function searchnameofalluser(){
		$data = $this->contentHelper->searchnameofalluser();
		print json_encode($data,JSON_FORCE_OBJECT);
		exit;
	}	
	
}
?>