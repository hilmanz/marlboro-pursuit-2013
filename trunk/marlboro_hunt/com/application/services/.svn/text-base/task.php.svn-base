<?php
class task extends ServiceAPI{
		
	function beforeFilter(){
			$this->contentHelper = $this->useHelper('contentHelper');
			$this->activityHelper = $this->useHelper('activityHelper');
			$this->userHelper = $this->useHelper('userHelper');
			$this->newsHelper = $this->useHelper('newsHelper');
			
			
	}
	
	function main(){
	
		$data['status'] = false;
		$data['data']['task']= false;
		$data['data']['notification']= false;
		
		$getTaskList = $this->contentHelper->taskList();
		$getPursuitUpdate = $this->activityHelper->getNotification();
		
		if ($getTaskList){
				
				foreach($getTaskList['rec'] as $key => $val){
					$thetask[$key]['id'] = $val['id'];
					$thetask[$key]['title']= $val['title'];
					$thetask[$key]['description']= $val['content'];
					$thetask[$key]['type']= $val['articleType'];
					
					// $thetask[$key]['level']= 'hard';
					// if($val['topcontent']==0) $thetask[$key]['level']= 'easy';
					// if($val['topcontent']==1) $thetask[$key]['level']= 'medium';
					
				}
			
				$data['status'] = 200;
				$data['data']['task']= $thetask;
				$data['data']['notification']= count($getPursuitUpdate['content']);
		}else{
				$data['status'] = 200;
				$data['data']['task']= false;
				$data['data']['notification']= count($getPursuitUpdate['content']);
		}
		
		if($data['status']) return $data;
		
		print  $this->error_400();exit;
		
	}
	
	function lists(){
	
		$data['status'] = false;
		$data['data']['task']= false;
		$data['data']['notification']= false;
		
		$getTaskList = $this->contentHelper->taskList();
		$getPursuitUpdate = $this->activityHelper->getNotification();
		// pr($getPursuitUpdate);exit;
		if ($getTaskList){
				
				foreach($getTaskList['rec'] as $key => $val){
					$thetask[$key]['id'] = $val['id'];
					$thetask[$key]['title']= $val['title'];
					$thetask[$key]['description']= $val['content'];
					$thetask[$key]['type']= $val['articleType'];
					
					// $thetask[$key]['level']= 'hard';
					// if($val['topcontent']==0) $thetask[$key]['level']= 'easy';
					// if($val['topcontent']==1) $thetask[$key]['level']= 'medium';
					
				}
			
				$data['status'] = 200;
				$data['data']['task']= $thetask;
				$data['data']['notification']= count($getPursuitUpdate['content']);
		}else{
				$data['status'] = 200;
				$data['data']['task']= false;
				$data['data']['notification']= count($getPursuitUpdate['content']);
		}
		
		if($data['status']) return $data;
		
		print  $this->error_400();exit;
		
	}
	
	function complete(){
	
		$data['status'] = false;
		$data['data']['task']= false;
		$data['data']['notification']= false;
		
		$getTaskAcompalishedList = $this->contentHelper->accomplishedTask();
		$getPursuitUpdate = $this->activityHelper->getNotification();
		// pr($getTaskAcompalishedList);exit;
		if ($getTaskAcompalishedList){
		
		
				foreach($getTaskAcompalishedList as $key => $val){
					$thetask[$key]['id'] = $val['id'];
					$thetask[$key]['title']= $val['title'];
					$thetask[$key]['description']= $val['content'];
					$thetask[$key]['type']= $val['articleType'];
					
					// $thetask[$key]['level']= 'hard';
					// if($val['topcontent']==0) $thetask[$key]['level']= 'easy';
					// if($val['topcontent']==1) $thetask[$key]['level']= 'medium';
					
				}
			
				$data['status'] = 200;
				$data['data']['task']= $thetask;
				$data['data']['notification']= count($getPursuitUpdate['content']);
		}else{
				$data['status'] = 200;
				$data['data']['task']= false;
				$data['data']['notification']= count($getPursuitUpdate['content']);
		}
		
		if($data['status']) return $data;
		
		print  $this->error_400();exit;
	
		
	}
	
}
?>