<?php

global $ENGINE_PATH;
include_once $ENGINE_PATH."Utility/Paginate.php";
		
class merchandiseRedeem extends Admin{
	var $category;
	var $type;
	function __construct(){	
		parent::__construct();	
		
		$this->type = "1,3,4,5,6";
		$this->contentType = "0";
		$this->folder =  'merchandiseRedeem';
		$this->dbclass = 'marlborohunt';
		$this->fromwho = 0; // 0 is admin/backend
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
		$this->View->assign('basedomain',$CONFIG['BASE_DOMAIN']);
		$this->View->assign('baseurl',$CONFIG['BASE_DOMAIN_PATH']);
		$act = $this->_g('act');
		if($act){
			return $this->$act();
		} else {
			return $this->home();
		}
	}
	
	function home (){

		//filter box
		$filter = "";
		$search = $this->_g("search") == NULL ? '' : $this->_g("search");
		$startdate = $this->_g("startdate") == NULL ? '' : $this->_g("startdate");
		$enddate = $this->_g("enddate") == NULL ? '' : $this->_g("enddate");
		$filter .= $startdate=='' ? "" : "AND DATE(mm.redeemdate) >= '{$startdate}' ";
		$filter .= $enddate=='' ? "" : "AND DATE(mm.redeemdate) <= '{$enddate}' ";		
		$filter .= $search=='' ? "" : "AND (sm.name LIKE '%{$search}%' OR sm.last_name LIKE '%{$search}%' OR sm.email LIKE '%{$search}%')";
		$this->View->assign('search',$search);
		$this->View->assign('startdate',$startdate);
		$this->View->assign('enddate',$enddate);	
	
		$start = intval($this->_g('st'));
		
		/* Hitung banyak record data */
		$sql = "SELECT COUNT(*) total FROM my_merchandise mm
				LEFT JOIN social_member sm ON mm.userid = sm.id
				LEFT JOIN marlborohunt_merchandise mhm ON mm.merchandiseid = mhm.id
				WHERE mm.n_status <> 3 {$filter} ORDER BY mm.redeemdate DESC";
		$totalList = $this->fetch($sql);	
		if($totalList){
		$total = intval($totalList['total']);
		}else $total = 0;
		// pr($total);
		
		
		/* list article */
		 // DATE_FORMAT(a.datetime,'%Y-%m-%d') 
		$sql = "SELECT mm.*, sm.name, sm.last_name, mhm.name merchName, mhm.image
				FROM my_merchandise mm
				LEFT JOIN social_member sm ON mm.userid = sm.id
				LEFT JOIN marlborohunt_merchandise mhm ON mm.merchandiseid = mhm.id
				WHERE mm.n_status <> 3 {$filter}
				ORDER BY mm.redeemdate DESC LIMIT {$start},{$this->total_per_page}";		
		$list = $this->fetch($sql,1);
		// pr($sql);
		// pr($list);
		if($list){
				
			$n=$start+1;
			foreach($list as $key => $val){
					$list[$key]['no'] = $n++;
					
			}
			
		}			
		
		$this->View->assign('list',$list);
		$this->Paging = new Paginate();
	
		$this->View->assign("paging",$this->Paging->getAdminPaging($start, $this->total_per_page, $total, "?s={$this->folder}&startdate={$startdate}&enddate={$enddate}"));	
		// pr("application/admin/{$this->folder}/{$this->folder}_list.html");
		return $this->View->toString("application/admin/{$this->folder}/{$this->folder}_list.html");
	}
	
	function ajax(){
		
		$id = $this->_p('userid');
		$n_status = $this->_p('n_status');
		$idmerchan = $this->_p('idmerchan');
		$date = date("Y-m-d H:i:s");
		// pr($n_status);
		
		if ($n_status==2){
			$data[] = "n_status = {$n_status}";
			$data[] = "date_redeemed = '{$date}'";
		}else{
			$data[] = "n_status = {$n_status}";
		}
		
		$param = implode(',',$data);

		$sql = "UPDATE my_merchandise SET {$param} WHERE userid = {$id} AND merchandiseid = {$idmerchan} LIMIT 1";
		// $sql = "UPDATE my_merchandise SET n_status = {$n_status} WHERE userid = {$id} AND merchandiseid = {$idmerchan} LIMIT 1";
		// pr($sql);
		$qData = $this->query($sql);
		// pr($qData);
		if($qData){
			
			$sql = "SELECT date_redeemed FROM my_merchandise WHERE userid = {$id} AND merchandiseid = {$idmerchan} LIMIT 1";
			
			$res = $this->fetch($sql);
			// pr($res);
			if ($res) print json_encode(array('status'=>true, 'rec'=>$res));
			else print json_encode(array('status'=>true));
		}else{
			print json_encode(array('status'=>false));
		}
		
		exit;
		
		
	}
	
	function getredeemreport(){
		
		$filename = "redeem_activity_".date('Ymd_gia').".xls";
		header("Content-Type:   application/vnd.ms-excel; charset=utf-8");
		header("Content-Disposition: attachment; filename=$filename");  //File name extension was wrong
		header("Expires: 0");
		header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
		header("Cache-Control: private",false);
		// echo "Some Text"; //no ending ; here
		$resReport = $this->reportQuery();
		$this->log->sendActivity("user printing dyo report");
		// pr($resReport);
		echo "<table border='1'>";
		echo "<tr>";
			echo "<td>No</td>";
			echo "<td>Name</td>";
			echo "<td>Email</td>";
			echo "<td>Address</td>";
			echo "<td>Phone Number</td>";
			echo "<td>Merchandise</td>";
			echo "<td>Date</td>";
			echo "<td>Date Redeemed</td>";
		echo "</tr>";
		foreach ($resReport as $key => $val){			
			echo "<tr>";
				echo "<td>$val[no]</td>";
				echo "<td>$val[name] $val[last_name]</td>";
				echo "<td>$val[email]</td>";
				echo "<td>$val[address]</td>";
				echo "<td>$val[phonenumber]</td>";
				echo "<td>$val[merchName]</td>";
				echo "<td>$val[redeemdate]</td>";
				echo "<td>$val[date_redeemed]</td>";
			echo "</tr>";
		}
		echo "</table>";
		exit;
	}
	
	
	function getredeemreport_archlight(){
		
		$filename = "redeem_activity_".date('Ymd_gia').".xls";
		header("Content-Type:   application/vnd.ms-excel; charset=utf-8");
		header("Content-Disposition: attachment; filename=$filename");  //File name extension was wrong
		header("Expires: 0");
		header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
		header("Cache-Control: private",false);
		// echo "Some Text"; //no ending ; here
		$resReport = $this->reportQuery();
		$this->log->sendActivity("user printing dyo report");
		// pr($resReport);
		echo "<table border='1'>";
		echo "<tr>";
			echo "<td>No</td>";
			echo "<td>Individual ID (Archlight)</td>";
			echo "<td>First Name</td>";
			echo "<td>Last name</td>";
			echo "<td>Email</td>";
			echo "<td>Date of birth</td>";
			echo "<td>Prize Redeemed</td>";
			echo "<td>Current Brand with Brand Code</td>";
			echo "<td>Brand Name</td>";
			echo "<td>Brand Flavour</td>";
			echo "<td>Tar Level</td>";
			echo "<td>City</td>";
			echo "<td>Province</td>";
			
		echo "</tr>";
		foreach ($resReport as $key => $val){			
			echo "<tr>";
				echo "<td>$val[no]</td>";
				echo "<td>$val[registerid]</td>";
				echo "<td>$val[name]</td>";
				echo "<td>$val[last_name]</td>";
				echo "<td>$val[email]</td>";
				echo "<td>$val[birthday]</td>";
				echo "<td>$val[merchName]</td>";
				echo "<td>$val[brands_code]</td>";
				echo "<td>$val[brands_name]</td>";
				echo "<td>$val[brands_flavor]</td>";
				echo "<td>$val[brands_tar]</td>";
				echo "<td>$val[city]</td>";
				echo "<td>$val[province]</td>";
				
			echo "</tr>";
		}
		echo "</table>";
		exit;
	}
	
	function reportQuery() {
		
		$filter = "";
		$search = $this->_g("search") == NULL ? '' : $this->_g("search");
		$startdate = $this->_g("startdate") == NULL ? '' : $this->_g("startdate");
		$enddate = $this->_g("enddate") == NULL ? '' : $this->_g("enddate");
		$filter .= $startdate=='' ? "" : "AND DATE(mm.redeemdate) >= '{$startdate}' ";
		$filter .= $enddate=='' ? "" : "AND DATE(mm.redeemdate) <= '{$enddate}' ";		
		$filter .= $search=='' ? "" : "AND (sm.name LIKE '%{$search}%' OR sm.last_name LIKE '%{$search}%' OR sm.email LIKE '%{$search}%')";
		$this->View->assign('search',$search);
		$this->View->assign('startdate',$startdate);
		$this->View->assign('enddate',$enddate);	
	 	 
		/* list article */
		 // DATE_FORMAT(a.datetime,'%Y-%m-%d') 
		 $emailDev = 'impstrg@yahoo.com';
		 
		$sql = "SELECT mm.*, sm.registerid, sm.birthday, sm.name, sm.last_name, mhm.name merchName, mhm.image,
				sm.city, mm.merchandiseid
				FROM my_merchandise mm
				LEFT JOIN social_member sm ON mm.userid = sm.id
				LEFT JOIN marlborohunt_merchandise mhm ON mm.merchandiseid = mhm.id
				WHERE mm.n_status <> 3 {$filter}
				AND mm.email NOT IN ('{$emailDev}')
				ORDER BY mm.redeemdate DESC ";		
		$list = $this->fetch($sql,1);
		// pr($sql);
		// pr($list);
		if($list){
				
			$n=$start+1;
			foreach($list as $key => $val){
					$list[$key]['no'] = $n++;
				
				$sqlProv = "SELECT provinceName, city FROM marlborohunt_city_reference 
						WHERE id = {$val['city']} ";
				$resProv = $this->fetch($sqlProv);
				if ($resProv){
					$list[$key]['city'] = $resProv['city'];
					$list[$key]['province'] = $resProv['provinceName'];
				}else{
					$list[$key]['city'] = "";
					$list[$key]['province'] = "";
				}
				
				$sql = "SELECT COUNT(codeid) total FROM tbl_code_inventory WHERE userid = {$val['userid']}";
				// pr($sql);
				$res =  $this->fetch($sql);
				// pr($res);
				if ($res){
					$list[$key]['total_letter'] = $res['total'];
				}else{
					$list[$key]['total_letter'] = 0;
				}
				
				$sqlBrand = "SELECT br.brands_name, br.code, br.flavor, br.tar FROM social_brand_preferences pref 
							LEFT JOIN brands br ON pref.brand_primary = br.id WHERE pref.userid = {$val['userid']} 
							ORDER BY pref.id DESC LIMIT 1";
				$resBrand =  $this->fetch($sqlBrand);
				if ($resBrand){
					$list[$key]['brands_name'] = $resBrand['brands_name'];
					$list[$key]['brands_code'] = $resBrand['code'];
					$list[$key]['brands_flavor'] = $resBrand['flavor'];
					$list[$key]['brands_tar'] = $resBrand['tar'];
				}else{
					$list[$key]['brands_name'] = "";
					$list[$key]['brands_code'] = "";
					$list[$key]['brands_flavor'] = "";
					$list[$key]['brands_tar'] = "";
				}
				
				
			}
			
		}			
		
		return $list;		
		
	}
	
	function getredeemreportwithletter(){
		
		$filename = "redeem_activity_".date('Ymd_gia').".xls";
		header("Content-Type:   application/vnd.ms-excel; charset=utf-8");
		header("Content-Disposition: attachment; filename=$filename");  //File name extension was wrong
		header("Expires: 0");
		header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
		header("Cache-Control: private",false);
		// echo "Some Text"; //no ending ; here
		$resReport = $this->reportQueryWithLetter();
		$this->log->sendActivity("user printing dyo report");
		// pr($resReport);
		echo "<table border='1'>";
		echo "<tr>";
			echo "<td>No</td>";
			echo "<td>Individual ID (Archlight)</td>";
			echo "<td>First Name</td>";
			echo "<td>Last Name</td>";
			echo "<td>Email</td>";
			echo "<td>Date of birth</td>";
			echo "<td>Number of Letters Letters Earned</td>";
			echo "<td>Numbers of Letters Used</td>";
			echo "<td>Brand Code</td>";
			echo "<td>Brand Name</td>";
			echo "<td>Brand Flavour</td>";
			echo "<td>Tar Level</td>";
			echo "<td>City</td>";
			echo "<td>Province</td>";
			
		echo "</tr>";
		foreach ($resReport as $key => $val){			
			echo "<tr>";
				echo "<td>$val[no]</td>";
				echo "<td>$val[registerid]</td>";
				echo "<td>$val[name]</td>";
				echo "<td>$val[last_name]</td>";
				echo "<td>$val[email]</td>";
				echo "<td>$val[birthday]</td>";
				echo "<td>$val[total_letter]</td>";
				echo "<td>$val[total_letter_redeem]</td>";
				echo "<td>$val[brands_code]</td>";
				echo "<td>$val[brands_name]</td>";
				echo "<td>$val[brands_flavor]</td>";
				echo "<td>$val[brands_tar]</td>";
				echo "<td>$val[city]</td>";
				echo "<td>$val[province]</td>";
				
			echo "</tr>";
		}
		echo "</table>";
		exit;
	}
	
	
	function reportQueryWithLetter() {
		
		$filter = "";
		$search = $this->_g("search") == NULL ? '' : $this->_g("search");
		$startdate = $this->_g("startdate") == NULL ? '' : $this->_g("startdate");
		$enddate = $this->_g("enddate") == NULL ? '' : $this->_g("enddate");
		$filter .= $startdate=='' ? "" : "AND DATE(mm.redeemdate) >= '{$startdate}' ";
		$filter .= $enddate=='' ? "" : "AND DATE(mm.redeemdate) <= '{$enddate}' ";		
		$filter .= $search=='' ? "" : "AND (sm.name LIKE '%{$search}%' OR sm.last_name LIKE '%{$search}%' OR sm.email LIKE '%{$search}%')";
		$this->View->assign('search',$search);
		$this->View->assign('startdate',$startdate);
		$this->View->assign('enddate',$enddate);	
	 	 
		/* list article */
		 // DATE_FORMAT(a.datetime,'%Y-%m-%d')

		$emailDev = 'impstrg@yahoo.com';
		
		$sql = "SELECT DISTINCT(mm.userid), sm.registerid, sm.birthday, sm.name, mm.email, 
				sm.last_name, mhm.name merchName, mhm.image,
				sm.city, mm.merchandiseid
				FROM my_merchandise mm
				LEFT JOIN social_member sm ON mm.userid = sm.id
				LEFT JOIN marlborohunt_merchandise mhm ON mm.merchandiseid = mhm.id
				WHERE mm.n_status <> 3 {$filter}
				AND mm.email NOT IN ('{$emailDev}')
				GROUP BY mm.userid ORDER BY mm.redeemdate DESC ";
				
		// $sql = "SELECT mm.*, sm.registerid, sm.name, sm.last_name, mhm.name merchName, mhm.image
				// FROM my_merchandise mm
				// LEFT JOIN social_member sm ON mm.userid = sm.id
				// LEFT JOIN marlborohunt_merchandise mhm ON mm.merchandiseid = mhm.id
				// WHERE mm.n_status <> 3 {$filter}
				// ORDER BY mm.redeemdate DESC ";		
		$list = $this->fetch($sql,1);
		// pr($sql);
		
		if($list){
			
			
			
			$n=$start+1;
			foreach($list as $key => $val){
			
				$sql = "SELECT COUNT(codeid) total FROM tbl_code_inventory WHERE userid = {$val['userid']}";
				// pr($sql);
				$res =  $this->fetch($sql);
				// pr($res);
				if ($res){
					$list[$key]['total_letter'] = $res['total'];
				}else{
					$list[$key]['total_letter'] = 0;
				}
				
				$sqlredeem = "SELECT COUNT(codeid) totalredeem FROM tbl_code_inventory WHERE userid = {$val['userid']} 
						AND n_status = 4";
				// pr($sqlredeem);
				$resredeem =  $this->fetch($sqlredeem);
				// pr($resredeem);
				if ($resredeem){
					$list[$key]['total_letter_redeem'] = $resredeem['totalredeem'];
				}else{
					$list[$key]['total_letter_redeem'] = 0;
				}
				
				
				$sqlProv = "SELECT provinceName, city FROM marlborohunt_city_reference 
						WHERE id = {$val['city']} ";
				$resProv = $this->fetch($sqlProv);
				if ($resProv){
					$list[$key]['city'] = $resProv['city'];
					$list[$key]['province'] = $resProv['provinceName'];
				}else{
					$list[$key]['city'] = "";
					$list[$key]['province'] = "";
				}
				
				$sqlBrand = "SELECT br.brands_name, br.code, br.flavor, br.tar FROM social_brand_preferences pref 
							LEFT JOIN brands br ON pref.brand_primary = br.id WHERE pref.userid = {$val['userid']} 
							ORDER BY pref.id DESC LIMIT 1";
				$resBrand =  $this->fetch($sqlBrand);
				if ($resBrand){
					$list[$key]['brands_name'] = $resBrand['brands_name'];
					$list[$key]['brands_code'] = $resBrand['code'];
					$list[$key]['brands_flavor'] = $resBrand['flavor'];
					$list[$key]['brands_tar'] = $resBrand['tar'];
				}else{
					$list[$key]['brands_name'] = "";
					$list[$key]['brands_code'] = "";
					$list[$key]['brands_flavor'] = "";
					$list[$key]['brands_tar'] = "";
				}
				
				

				
					$list[$key]['no'] = $n++;
					
			}
			
		}			
		// pr($list);
		return $list;		
		
	}
	
}