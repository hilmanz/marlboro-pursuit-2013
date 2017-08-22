<?php
class notification extends ServiceAPI{
		
	function beforeFilter(){
			$this->contentHelper = $this->useHelper('contentHelper');
			$this->activityHelper = $this->useHelper('activityHelper');
			$this->userHelper = $this->useHelper('userHelper');
			$this->newsHelper = $this->useHelper('newsHelper');
			
			
	}
	
	function main(){
	
		$data['status'] = false;
		$data['data']= false;
		
				
		$getPursuitUpdate = $this->activityHelper->getNotification();
		if ($getPursuitUpdate){
				$data['status'] = 200;
				$data['data']['notif']= $getPursuitUpdate['content'];
				$data['data']['notification']= count($getPursuitUpdate['content']);
		}else{
				$data['status'] = 200;
				$data['data']['notif']= false;
				$data['data']['notification']= 0;
		}
		
			
		if($data['status']) return $data;
		
		print  $this->error_400();exit;
		
	}

	
}
?>