<?php
class about extends App{
	
	
	function beforeFilter(){
		global $LOCALE,$CONFIG;
		
		$this->contentHelper = $this->useHelper('contentHelper');
		$this->activityHelper = $this->useHelper('activityHelper');
		$this->userHelper = $this->useHelper('userHelper');
		$this->newsHelper = $this->useHelper('newsHelper');
		$this->searchHelper = $this->useHelper('searchHelper');
		$this->uploadHelper = $this->useHelper('uploadHelper');
		$this->botHelper = $this->useHelper('botHelper');
		
		$this->assign('basedomain', $CONFIG['BASE_DOMAIN']);
		$this->assign('assets_domain', $CONFIG['ASSETS_DOMAIN_WEB']);
		$this->assign('locale', $LOCALE[1]);
		$this->assign('pages', strip_tags($this->_g('page')));
	}
	
	function main(){
		
	}
	
	
	function video(){
		
		$this->log('surf', 'about video');
		$getEvent = $this->newsHelper->videoEvent(false,true);
		
		// pr($getEvent);exit;
		if ($getEvent){
			 
			
			$this->View->assign('dataEvent',$getEvent);
		}
		
		// pr($getEvent);exit;
		
		
		
		return $this->View->toString(TEMPLATE_DOMAIN_WEB .'apps/about-video.html');
	
	}
	
	function videoDevel(){
		
		
		$getEvent = $this->newsHelper->videoEvent(false,true);
		
		// pr($getEvent);exit;
		if ($getEvent){
			 
			
			$this->View->assign('dataEvent',$getEvent);
		}
		
		// pr($getEvent);exit;
		
		
		
		return $this->View->toString(TEMPLATE_DOMAIN_WEB .'test/about-video-devel.html');
	
	}
	
	function upcoming(){
		
		$this->View->assign('event_upcoming_list',$this->setWidgets('event_upcoming_list'));
		$this->View->assign('event_upcoming_detail',$this->setWidgets('event_upcoming_detail'));
		return $this->View->toString(TEMPLATE_DOMAIN_WEB .'apps/upcoming-event.html');

	}
	
	function posts(){
		$this->log('surf', 'event upload fotovideo');
		return $this->View->toString(TEMPLATE_DOMAIN_WEB .'/apps/upload-video.html');
	}
	
	function upload(){
		global $CONFIG;
		
		$data['result'] = false;
		$data['filename'] = false;
		$idevent = intval($this->_p('idevent'));
		//var_dump($_FILES['image']);exit;
		if (isset($_FILES['image']) && $_FILES['image']['name']!=NULL) {
			$path = $CONFIG['LOCAL_PUBLIC_ASSET']."gallery/";
			//var_dump($_FILES['image']['size']);exit;
			if (isset($_FILES['image']) && $_FILES['image']['size'] <= 20000000) {
				$res = $this->uploadHelper->uploadThisImage($_FILES['image'],$path,700,true);
				//var_dump($res);exit;
				if ($res['arrImage']!=NULL) {
					// $this->log('update profile photo');

					$data['result'] = true;
					$data['filename'] = $res['arrImage']['filename'];
					$data['nosave'] = false;
					
					if ($data['filename']){
						
						
						$getEvent = $this->contentHelper->getEventDesc($idevent);
						if ($getEvent){
							$files['filename'] = $data['filename'];
							$files['title'] = $getEvent['title'];
							$files['typealbum'] = 2;
							$files['contentid'] = $getEvent['id'];
							
							$saveData = $this->contentHelper->saveDataSubmission($files);
							// $this->uploadHelper->eventImageUpload($idevent);
							if ($saveData){
								print json_encode(array('status'=>true));
							}else{
								print json_encode(array('status'=>false));
							}
							// if ($saveData) $this->botHelper->setPointgift('EVENTSUBMISSION', 'eventsubmissionpoint',2,'day');
						}else{
							print json_encode(array('status'=>false));
						}
						
					}
					
				}
			}
		}
		
		/*
		if (isset($_FILES['video']) && $_FILES['video']['name']!=NULL) {
			$path = $CONFIG['LOCAL_PUBLIC_ASSET']."event/video/";
			if (isset($_FILES['video']) && $_FILES['video']['size'] <= 20448160) {
				$res = $this->uploadHelper->uploadThisVideo($_FILES['video'],$path,220,true);
				if ($res['arrVideo']!=NULL) {
					// $this->log('update profile photo');
					$data['result'] = true;
					$data['filename'] = $res['arrVideo'];
					$data['nosave'] = false;
					if ($data['filename']){
						
						
						$getEvent = $this->contentHelper->getEventDesc($idevent);
						if ($getEvent){
							$files['filename'] = $data['filename'];
							$files['title'] = $getEvent['title'];
							$files['typealbum'] = $this->_p('typealbum');
							$files['contentid'] = $getEvent['id'];
							
							$saveData = $this->contentHelper->saveDataSubmission($files);
							if ($saveData) $this->botHelper->setPointgift('EVENTSUBMISSION', 'eventsubmissionpoint',2,'day');
						}
						
					}
				
				}
			}
		}
		*/
		// sendRedirect("{$CONFIG['BASE_DOMAIN']}event/post");
		// print json_encode($data);
		exit;
	}
	
	function ajax(){
		$type = $this->_g('type');
		
		
		if($type == 'foto'){
			
			$varFoto = $this->newsHelper->pastEvent();
			if($varFoto){
				print json_encode(array('status'=>TRUE,'hasil'=>$varFoto));
			}	
		}
		
		if($type == 'video'){
			$varVid = $this->newsHelper->getVideo();
			if($varVid){
				print json_encode(array('status' =>TRUE, 'hasil' => $varVid));
			}
		}
		
		if ($this->_p('getPhoto')){
		
			$start = $this->_p('start');
			$end = $this->_p('end');
			$type = $this->_p('type');
			$id = $this->_p('id');
			
			$detailevent = $this->getGallery($start, $end, $type, $id);
			// pr($detailevent);
			if ($detailevent){
				print json_encode(array('status' =>true, 'rec' => $detailevent));
			}else{
				print json_encode(array('status' =>false));
			}
		}
		
		if ($this->_p('getVideo')){
		
			$start = $this->_p('start');
			$end = $this->_p('end');
			$type = $this->_p('type');
			$id = $this->_p('id');
			
			$detailevent = $this->getGallery($start, $end, $type, $id);
			// echo 'ada';
			// pr($detailevent);
			if ($detailevent){
				print json_encode(array('status' =>true, 'rec' => $detailevent));
			}else{
				print json_encode(array('status' =>false));
			}
		}
		exit; 
	}
	
	function ajaxgallery()
	{
		$start = $this->_p('start');
		$end = $this->_p('end');
		$type = $this->_p('type');
		$id = $this->_p('id');
		$detailevent = $this->getGallery($start, $end, $type,$id);
		// pr($detailevent);
		if ($detailevent){
			print json_encode(array('status' =>true, 'rec' => $detailevent));
		}else{
			print json_encode(array('status' =>false));
		}
		
		exit;
		
	}
	
	function getGallery($start=0, $limit=4, $type=2, $id=false)
	{
		
		$id = $id;
		$detailevent = $this->newsHelper->pastEvent(false, false, $id);
		
		
		if ($detailevent){
			$detailevent[0]['total'] = count($detailevent[0]['gallery']);
			// pr($detailevent);
			
			
				
				if ($detailevent[0]['gallery']){
					
					if ($type == 2){
						// pr($detailevent[0]['gallery']);
						foreach ($detailevent[0]['gallery'] as $key => $value){
							if ($value['typealbum'] == 2){
								// $detailevent[0]['gallery'][$key] = $value;
								$detailevent[0]['myGalery'][$key] = $value;
							}
							
						}
					}else{
						foreach ($detailevent[0]['gallery'] as $key => $value){
							if ($value['typealbum'] == 3){
								$detailevent[0]['myGalery'][$key] = $value;
							}
							
						}
					}
					
					// pr($detailevent[0]);
					if ($detailevent[0]['total'] > 5){
					
						foreach ($detailevent[0]['myGalery'] as $key => $value){
							
							$detailevent[0]['gallery_sort'][] = $value;
						}
						
						// pr($detailevent[0]['gallery_sort']);
						// echo $start.'<br>';
						// echo $limit;
						// exit;
						for($i = $start; $i<=$limit; $i++){
							if (isset($detailevent[0]['gallery_sort'][$i])){
								$detailevent[0]['gallery_paging'][] = $detailevent[0]['gallery_sort'][$i];
							}
							
						}
						
						// pr($detailevent[0]['gallery_paging']);exit;
						foreach ($detailevent[0]['gallery_paging'] as $key => $value){
							
							list($dateFormat, $timeFormat) = explode(' ',$value['created_date']);
							list($year, $month, $date) = explode('-',$dateFormat);
							
							$detailevent[0]['gallery_paging'][$key]['date'] = $date.'/'.$month.'/'.$year;
						}
						// pr($detailevent[0]['gallery_paging']);
					}else{
						
						
						// pr($detailevent[0]);
						if (isset($detailevent[0]['myGalery'])){
							foreach ($detailevent[0]['myGalery'] as $key => $value){
								
								$detailevent[0]['gallery_sort'][] = $value;
							}
						}
						
						
						for($i = $start; $i<=$limit; $i++){
							if (isset($detailevent[0]['gallery_sort'][$i])){
								$detailevent[0]['gallery_paging'][] = $detailevent[0]['gallery_sort'][$i];
							}
							
						}
						
						if (isset($detailevent[0]['gallery_paging'])){
							foreach ($detailevent[0]['gallery_paging'] as $key => $value){
								
								list($dateFormat, $timeFormat) = explode(' ',$value['created_date']);
								list($year, $month, $date) = explode('-',$dateFormat);
								
								$detailevent[0]['gallery_paging'][$key]['date'] = $date.'/'.$month.'/'.$year;
							}
						}
					}
				}
				
			return $detailevent;
		}
		
		return false;
		
	}
	
	function showPopup()
	{
		
		if ($this->_p('isvalid')){
		
			$cid = intval($this->_p('cid'));
			$this->log('read article',"event_{$cid}");
			$getEvent = $this->newsHelper->pastEvent(false,false,$cid,true);
			// pr($getEvent);
			if($getEvent){
				print json_encode(array('status'=>true, 'rec'=>$getEvent[0]));
			}else{
				print json_encode(array('status'=>false));
			}
			
		}
		
		exit;
	}
	
	
	function sendtotempimages(){
	global $CONFIG;
					$data['result'] = false;
					$data['filename'] = "";
					$data['message'] = " noooo ";
			 
			if (isset($_FILES['image']) && $_FILES['image']['name']!=NULL) {
				$path = $CONFIG['LOCAL_PUBLIC_ASSET']."temp/";
				$data['message'] = json_encode($_FILES['image']);
			 
				$res = $this->uploadHelper->uploadThisImage($_FILES['image'],$path,700,true);
				 // $data['message'] =json_encode($res);
				if ($res['arrImage']!=NULL) {
				 

					$data['result'] = true;
					$data['filename'] = $CONFIG['BASE_DOMAIN'].'public_assets/temp/'.$res['arrImage']['filename'];
				 	$data['message'] = "yesss";
					
					}
				 
			}
			
			if (isset($_FILES['dataChallenge']) && $_FILES['dataChallenge']['name']!=NULL) {
				$path = $CONFIG['LOCAL_PUBLIC_ASSET']."temp/";
				$data['message'] = json_encode($_FILES['dataChallenge']);
			 
				$res = $this->uploadHelper->uploadThisImage($_FILES['dataChallenge'],$path,700,true);
				 // $data['message'] =json_encode($res);
				if ($res['arrImage']!=NULL) {
				 

					$data['result'] = true;
					$data['filename'] = $CONFIG['BASE_DOMAIN'].'public_assets/temp/'.$res['arrImage']['filename'];
				 	$data['message'] = "yesss";
					
					}
				 
			}
			
			print json_encode($data);exit;
	}
	
}
?>