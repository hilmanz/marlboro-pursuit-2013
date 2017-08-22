<?php

global $ENGINE_PATH;
include_once $ENGINE_PATH."Utility/Paginate.php";
		
class hiddenCode extends Admin{
	var $category;
	var $type;
	function __construct(){	
		parent::__construct();	
		

		$this->folder =  'hiddenCode';
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
		
		$filter = "";
		$search = $this->_g("search") == NULL ? '' : $this->_g("search");
		$startdate = $this->_g("startdate") == NULL ? '' : $this->_g("startdate");
		$enddate = $this->_g("enddate") == NULL ? '' : $this->_g("enddate");
		$filter .= $startdate=='' ? "" : "AND con.posted_date >= '{$startdate}' ";
		$filter .= $enddate=='' ? "" : "AND con.posted_date < '{$enddate}' ";		
		$filter .= $search=='' ? "" : "AND (con.maskcode LIKE '%{$search}%' OR con.location LIKE '%{$search}%' OR con.channel LIKE '%{$search}%') ";
		$this->View->assign('search',$search);
		$this->View->assign('startdate',$startdate);
		$this->View->assign('enddate',$enddate);
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
		
		$this->View->assign("paging",$this->Paging->getAdminPaging($start, $this->total_per_page, $total, "?s={$this->folder}&startdate={$startdate}&enddate={$enddate}"));
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
		
		$this->log->sendActivity("generate code","");
		$loop = intval($totalcode);
		$sql = "SELECT * FROM tbl_code_detail WHERE n_status = 1 AND codetype=0 ";
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
					(maskcode, codeid, location, channel, codevalue, datetime, grouptype, n_status, used,posted_date,expired_date)
					VALUES 
					('{$maskcode}', '{$key}', '{$location}', '{$channel}', '{$codevalue}', '{$datetime}', '{$group}', 1,0,'{$posteddate}','{$expireddate}')";
			// pr($sql);exit;
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
	
		$filter = "";
		$search = $this->_g("search") == NULL ? '' : $this->_g("search");
		$startdate = $this->_g("startdate") == NULL ? '' : $this->_g("startdate");
		$enddate = $this->_g("enddate") == NULL ? '' : $this->_g("enddate");
		$filter .= $startdate=='' ? "" : "AND con.posted_date >= '{$startdate}' ";
		$filter .= $enddate=='' ? "" : "AND con.posted_date < '{$enddate}' ";		
		$filter .= $search=='' ? "" : "AND (con.maskcode LIKE '%{$search}%' OR con.location LIKE '%{$search}%' OR con.channel LIKE '%{$search}%') ";
		$this->View->assign('search',$search);
		$this->View->assign('startdate',$startdate);
		$this->View->assign('enddate',$enddate);
	
		if((string)$this->_request('st')!="") $start = $this->_request('st');
		else $start = 0;
		if($all){
			$sql = "SELECT count(*) total
				FROM tbl_code_publicity con
				WHERE n_status = 1 AND channel ='games hidden code' 
				
				";
	
		}else{
		$sql = "SELECT *
				FROM tbl_code_publicity con
				WHERE n_status =1 AND channel ='games hidden code' {$filter}
				LIMIT {$start},{$limit}
				";
		}
		$res = $this->fetch($sql, 1);
		
		if ($res){
			return $res;
		}
		
		return false;
		
	}
	

	function getLetterDetail()
	{
		$sql = "SELECT * FROM tbl_code_detail WHERE n_status = 1";
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
		$sql = "UPDATE tbl_code_detail SET prob = '{$prob}' WHERE id = {$id} AND codetype= 0 ";
		$res = $this->query($sql);
		if ($res){
			print json_encode(array('status'=>true));
		}else{
			print json_encode(array('status'=>false));
		}
		
		
		exit;
	}
	
	function getletterreport(){
		
		$filename = "hidden_code_report_".date('Ymd_gia').".xls";
		header("Content-Type:   application/vnd.ms-excel; charset=utf-8");
		header("Content-Disposition: attachment; filename=$filename");  //File name extension was wrong
		header("Expires: 0");
		header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
		header("Cache-Control: private",false);
		// echo "Some Text"; //no ending ; here
		$resReport = $this->reportQuery();
		$this->log->sendActivity("user printing letter report");
		// pr($resReport);
		echo "<table border='1'>";
		echo "<tr>";
			echo "<td>No</td>";
			echo "<td>Mask Code</td>";
			echo "<td>Location</td>";
			echo "<td>Channel</td>";
			echo "<td>Generate Date</td>";
		echo "</tr>";
		foreach ($resReport as $key => $val){			
			echo "<tr>";
				echo "<td>$val[no]</td>";
				echo "<td>$val[maskcode]</td>";
				echo "<td>$val[location]</td>";
				echo "<td>$val[channel]</td>";
				echo "<td>$val[datetime]</td>";
			echo "</tr>";
		}
		echo "</table>";
		exit;
	}
	
	function reportQuery(){
		
		$sql = "SELECT * FROM tbl_code_publicity WHERE n_status =1";
		$qData = $this->fetch($sql,1);
		
		if($qData){
			$no = 1;
			foreach ($qData as $key => $value){
				$qData[$key]['no'] = $no;
				$no++;
			}
		}
		
		return $qData;
	
	}
	
	

}