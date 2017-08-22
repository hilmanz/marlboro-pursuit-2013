<?php

global $ENGINE_PATH;
include_once $ENGINE_PATH."Utility/Paginate.php";
		
class letterX extends Admin{
	var $category;
	var $type;
	function __construct(){	
		parent::__construct();	
		

		$this->folder =  'letterX';
		$this->dbclass = 'marlborohunt';
		$this->pagetype = 1;
		$this->total_per_page = 20;
		
	}
	
	function admin(){
		
		global $CONFIG;
	
		//get admin role
		foreach($this->roler as $key => $val){
		$this->View->assign($key,$val);
		}
		//get specified admin role if true
		if($this->specified_role){
			foreach($this->specified_role as $val){
				$type[] = $val['type'];
				$category[] = $val['category'];
			}
			if($type) $this->type = implode(',',$type);
			else return false;
			if($category) $this->category = implode(',',$category);
			else return false;
		}
		//helper
	
		$this->View->assign('folder',$this->folder);
		
		$this->View->assign('baseurl',$CONFIG['BASE_DOMAIN_PATH']);
		$act = $this->_g('act');
		if($act){
			return $this->$act();
		} else {
			return $this->home();
		}
	}

	function home(){
	
		if((string)$this->_request('st')!="") $start = $this->_request('st');
		else $start = 0;
		$listCode = $this->getListCode( false, $this->total_per_page);
		$total = $this->getListCode( true );
		$total = intval($total[0]['total']);
		

		
		if ($listCode){
			$no = 1;
			foreach ($listCode as $key => $value){
				$listCode[$key]['no'] = $start+$no;
				$no++;
			}
		}
		
		
		// pr($listCode);
		
		$this->View->assign('code',$listCode);
	
		$this->Paging = new Paginate();
		
		$this->View->assign("paging",$this->Paging->getAdminPaging($start, $this->total_per_page, $total, "?s={$this->folder}"));
		return $this->View->toString("application/admin/{$this->folder}/{$this->folder}.html");
	}
	
	function generateCode()
	{
	
		$totalcode = $this->_p('iterasi');
		$location = $this->_p('location');
		$channel = $this->_p('channel');
		$posteddate = $this->_p('posteddate');
		$expireddate = $this->_p('expireddate');
		$group = $this->_p('group');
		if($totalcode==0) return false;
		
		$posteddate = date('Y-m-d H:i:s');
		// pr('masuk');
		
		$this->log->sendActivity("generate code","EXLUSIVE");
		$loop = intval($totalcode);
		$sql = "SELECT * FROM tbl_code_detail WHERE n_status = 1 AND codetype=1 ";
		$masterCode = $this->fetch($sql,1);
		$datetime = date("Y-m-d H:i:s");
		$getres = false;
		
		for ($i = 1; $i <= $loop; $i++){
			foreach ($masterCode as $key => $value){
				$popprob = ($value['prob'] * ($value['prob'] * (rand(1,12))));
				$code[$value['id']] = $popprob;
				$data[$value['id']] = $value['codename'];
				$masterCode[$key]['popprob'] = $popprob;

			}
			$maxCode = max($code);
			$key = array_search($maxCode, $code);
			$codevalue = $data[$key];
			$letters  = "ABCDEFGHJKMNPQRSTUVWXYZ23456789";
			$maskcode = substr(str_shuffle($letters), 0, 8);
			

			$sql = "INSERT INTO tbl_code_publicity 
					(maskcode, codeid, codetype, location, channel, codevalue, datetime, grouptype, n_status, used,posted_date,expired_date)
					VALUES 
					('{$maskcode}', {$key}, '1', '{$location}', 'elusive', '{$codevalue}', '{$datetime}', '{$group}', 1,0,'{$posteddate}','{$expireddate}')";
			// pr($sql);
			$res = $this->query($sql);
			if($this->getLastInsertId()){
				$getres[$maskcode] = 1;
			}else $getres[$maskcode] = 0;
			
		}
		
		if($getres){
			$success = 0;
			$failed = 0;
			foreach($getres as $key => $val){
				if($val==1) $success++;
				else $failed++;			
			}
		
			/* for log generator */
			$this->logthisgenerator($success,$failed,$datetime,$masterCode);
				
				return true;
		}
		
				
			return false;
	}
	
	function getListCode($all= false, $limit=20)
	{
	
		if((string)$this->_request('st')!="") $start = $this->_request('st');
		else $start = 0;
		if($all){
			$sql = "SELECT (*) total
				FROM tbl_code_publicity
				WHERE n_status = 1 AND codetype=1 
				
				";
	
		}else{
		$sql = "SELECT *
				FROM tbl_code_publicity
				WHERE n_status = 1 AND codetype=1 
				LIMIT {$start},{$limit}
				";
		}
		$res = $this->fetch($sql, 1);
		
		// pr($res);
		if ($res){
			return $res;
		}
		
		return false;
		
	}
	

	function getLetterDetail()
	{
		$sql = "SELECT * FROM tbl_code_detail WHERE n_status = 1 AND codetype=1  ";
		$masterCode = $this->fetch($sql,1);
		if ($masterCode){
			return $masterCode;
		}
		return false;
			
	}
	
	function detail(){
		$listLetter = $this->getLetterDetail();
		// pr($listLetter);
		foreach ($listLetter as $key => $value){
			$listLetter[$key]['persen'] = $value['prob'] * 100;
		}
		$this->View->assign('letter',$listLetter);
		return $this->View->toString("application/admin/{$this->folder}/{$this->folder}-detail.html");
	}
	
	function generateform(){
		
		
		
		// pr($listLetter);
		if ($this->_p('token')){
			if ($this->_p('iterasi')){
		

				$generateCode = $this->generateCode();
				
				
				if ($generateCode) return $this->View->showMessage('Success', "index.php?s={$this->folder}");
				return $this->View->showMessage('Failed', "index.php?s={$this->folder}");
			}else{
				return $this->View->showMessage('Failed', "index.php?s={$this->folder}");
			}
		}
		
		$loggenerator = $this->getlogofgenerator();

		
		$this->View->assign("code",$loggenerator);
		
		return $this->View->toString("application/admin/{$this->folder}/{$this->folder}-form.html");
		
	}
	
	function logthisgenerator($success=0,$failed=0,$datetime=null,$data=false){
	
		if($datetime==null) return false;
		if($data==false) return false;
		
		
		$data = serialize($data);
		
		$sql = " INSERT INTO tbl_code_log (success,failed,datetime,data) VALUES ({$success},{$failed},'{$datetime}','{$data}')";		
		
		$this->query($sql);
		if($this->getLastInsertId()){
			return true;
		}else return false;
	
	}
	
	function getlogofgenerator(){
		$sql = "SELECT * FROM  tbl_code_log ORDER BY datetime DESC ";
		$res = $this->fetch($sql, 1);
		
		if ($res){
		$no = 1;
			foreach ($res as $key => $value){
				$res[$key]['no'] = $no;
				$no++;
			}
	
			return $res;
		}else return false;
	}
	
	function ajax()
	{
		// pr($_POST);
		$prob = $this->_p('prob');
		$id = $this->_p('id');
		
		$prob = $prob / 100;
		$sql = "UPDATE tbl_code_detail SET prob = '{$prob}' WHERE id = {$id}";
		$res = $this->query($sql);
		if ($res){
			print json_encode(array('status'=>true));
		}else{
			print json_encode(array('status'=>false));
		}
		
		
		exit;
	}
	
	

}