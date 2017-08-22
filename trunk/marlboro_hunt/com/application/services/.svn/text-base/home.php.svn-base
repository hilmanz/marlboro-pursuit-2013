<?php
class home extends ServiceAPI{
		
	function beforeFilter(){
			$this->contentHelper = $this->useHelper('contentHelper');
			$this->activityHelper = $this->useHelper('activityHelper');
			$this->userHelper = $this->useHelper('userHelper');
			$this->newsHelper = $this->useHelper('newsHelper');
			
			
	}
	
	function main(){
	
		$data['status'] = false;
		$data['data']= false;
		$data['data']['notification']= false;
		
		$getMyLetter = $this->contentHelper->getMyLetterDetail();
		$getPursuitUpdate = $this->activityHelper->getNotification();
		$getMyLetterOnSet = $this->contentHelper->getMyLetter();
		
		if ($getMyLetter){
				$remapletter = false;
				$virid = 0;
				foreach($getMyLetter as $key => $val){
					$getMyLetter[$key]['id'] = $virid++;
				}
				$getLetterOnSet = false;
				foreach($getMyLetterOnSet as $key => $val){
					
					$getLetterOnSet[$key]['letter'] =$val;
					$getLetterOnSet[$key]['id'] =$key-1;
				}
				$data['status'] = 200;
				$data['data']['chars']= $getMyLetter;
				$data['data']['used_chars']= $getLetterOnSet;
				
				$data['data']['notification']= count($getPursuitUpdate['content']);
		}else{
			$data['status'] = 200;
			$data['data']['chars']= false;
			$data['data']['notification']= count($getPursuitUpdate['content']);
		}
		
				
		if($data['status']) return $data;
		
		print  $this->error_400();exit;
		
	}

	
}
?>