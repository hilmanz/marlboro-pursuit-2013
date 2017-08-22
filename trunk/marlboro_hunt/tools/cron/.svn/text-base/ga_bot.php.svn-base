<?php

include_once "db.php";
include_once"gapi/gapi.class.php";

class ga_bot extends db{

	var $gaData;
	var $ga_email;
	var $ga_password;
	var $ga_profile;

	function __construct(){
		$this->DB = "`marlboro.ph2`";
		// $this->DB = "marlboro_pursuit_2013";
		$this->ga_email = 'kana.digital@gmail.com';
		$this->ga_password = 'tualatin9i8u';
		$this->ga_profile = '52542575';
		$this->init_ga();
		sleep(1);
		$this->init_ga_OS();

	}



	function init_ga(){
		$ga = new gapi($this->ga_email,$this->ga_password);
		$ga->requestReportData($this->ga_profile,array('date','deviceCategory'),array('timeOnSite','pageviews','timeOnPage','visits','bounces','exits','visitors'), array('date'),null,
				date('2013-04-12'), // Start Date
				date("Y-m-d"), // End Date
				1,  // Start Index
				500 // Max results
		);

		foreach ($ga->getResults() as $result){
				$this->gaRaw[date('Y-m-d',strtotime($result->getDate()))][$result->getDeviceCategory()] = array(
				'pageViews'=>$result->getPageviews(),
				'visits'=>$result->getVisits(),
				'timeOnSite'=>$result->getTimeOnSite(),				
				'timeOnPage'=>$result->getTimeOnPage(),			
				'exits'=>$result->getExits(),			
				'bounces'=>$result->getBounces(),
				'uniqueVisitors'=>$result->getVisitors(),
				 );
				$this->gaDataDevice[date('Y-m-d',strtotime($result->getDate()))][$result->getDeviceCategory()] = $result->getVisits();
		}
		foreach ($this->gaRaw as $key => $value) {
			$pageViews=0;$visits=0;$timeOnSite=0;$timeOnPage=0;$exits=0;$bounces=0;$uniqueVisitors=0;
			if($value['desktop']){
				$pageViews += $value['desktop']['pageViews'];
				$visits += $value['desktop']['visits'];
				$timeOnSite += $value['desktop']['timeOnSite'];
				$timeOnPage += $value['desktop']['timeOnPage'];
				$exits += $value['desktop']['exits'];
				$bounces += $value['desktop']['bounces'];
				$uniqueVisitors += $value['desktop']['uniqueVisitors'];
			}
			if($value['mobile']){
				$pageViews += $value['mobile']['pageViews'];
				$visits += $value['mobile']['visits'];
				$timeOnSite += $value['mobile']['timeOnSite'];
				$timeOnPage += $value['mobile']['timeOnPage'];
				$exits += $value['mobile']['exits'];
				$bounces += $value['mobile']['bounces'];
				$uniqueVisitors += $value['mobile']['uniqueVisitors'];
			}
			if($value['tablet']){
				$pageViews += $value['tablet']['pageViews'];
				$visits += $value['tablet']['visits'];
				$timeOnSite += $value['tablet']['timeOnSite'];
				$timeOnPage += $value['tablet']['timeOnPage'];
				$exits += $value['tablet']['exits'];
				$bounces += $value['tablet']['bounces'];
				$uniqueVisitors += $value['tablet']['uniqueVisitors'];
			}


			$this->gaData[$key]=array(
				'page_views'=>$pageViews,
				'visits'=>$visits,
				'visitDuration'=>$timeOnSite,				
				'time_onPage'=>@round($timeOnPage/($pageViews - $exits)),
				'bounce_rate'=>@round(($bounces/$visits) *100),
				'unique_visitor'=>($uniqueVisitors),
				'time_onSite'=>@round($timeOnSite/$visits)
			);
		}
		
		//print_r('<pre>');
		//print_r($ga->getResults());
		//print_r($this->gaData);
		//exit;

	}

function init_ga_OS(){
		$ga = new gapi($this->ga_email,$this->ga_password);
		$ga->requestReportData($this->ga_profile,array('date','operatingSystem'),array('timeOnSite','pageviews','timeOnPage','visits','bounces','exits','visitors'), array('date'),null,
				date('2013-04-12'), // Start Date
				date("Y-m-d"), // End Date
				1,  // Start Index
				500 // Max results
		);

		foreach ($ga->getResults() as $result){
				$this->gaRaw[date('Y-m-d',strtotime($result->getDate()))][$result->getOperatingSystem()] = array(
				'pageViews'=>$result->getPageviews(),
				'visits'=>$result->getVisits(),
				'timeOnSite'=>$result->getTimeOnSite(),				
				'timeOnPage'=>$result->getTimeOnPage(),			
				'exits'=>$result->getExits(),			
				'bounces'=>$result->getBounces(),
				'uniqueVisitors'=>$result->getVisitors(),
				 );
				$this->gaDataDeviceAll[date('Y-m-d',strtotime($result->getDate()))][$result->getOperatingSystem()] = $result->getVisits();
		}
		 
		// print_r('<pre>');
		// print_r($this->gaDataDeviceAll);
	 
		  
		 // exit;

	}


	function insertDataGa(){
		$datas = $this->gaData;
		$devicedatas = $this->gaDataDevice;
		if($datas){
		$this->setSocketDB(0);
			foreach($datas as $key => $val){
			
			// tbl_ga_average_page_view_daily
				$gaDataQuery = "
				INSERT INTO {$this->DB}.ga_daily_data (page_views, visits, visitDuration, time_onPage, bounce_rate, unique_visitor, time_onSite,  date_d) VALUES ({$val['page_views']},{$val['visits']},{$val['visitDuration']},{$val['time_onPage']},{$val['bounce_rate']},{$val['unique_visitor']},{$val['time_onSite']},'{$key}') ON DUPLICATE KEY UPDATE page_views={$val['page_views']},visits={$val['visits']},visitDuration={$val['visitDuration']},time_onPage={$val['time_onPage']},bounce_rate={$val['bounce_rate']},unique_visitor={$val['unique_visitor']},time_onSite={$val['time_onSite']};
				";
				$gaData = $this->query($gaDataQuery);
				// tbl_ga_time_on_site_daily num, date_d
			
				if($gaData) $data['gaData'][] ="success";
				else $data['gaData'][] ="failed".$gaDataQuery;
				
				$sql[] = $gaDataQuery;
			}
		}else $data[]='there is not left data of GA';
		
		if($devicedatas){
			$this->setSocketDB(0);
			foreach($devicedatas as $datetimes => $devicedata){
				foreach($devicedata as $key => $val){
				// tbl_ga_average_page_view_daily
					$gaDataQuery = "
					INSERT INTO {$this->DB}.ga_daily_device_data ( visits , device, date_d) VALUES ( {$val},'{$key}','{$datetimes}') ON DUPLICATE KEY UPDATE  visits={$val},device='{$key}' ;
					";
					$gaData = $this->query($gaDataQuery);
					// tbl_ga_time_on_site_daily num, date_d
				
					if($gaData) $data['gaData'][] ="success";
					else $data['gaData'][] ="failed".$gaDataQuery;
					
					$sql[] = $gaDataQuery;
				}
			}
		}else $data[]='there is not left data DEVICE of GA';
		print_r('<pre>');print_r($data);
		// print_r (array( 0 => implode("; ",$sql)));
		// print "
		// ";
	}
	function insertDataGaAllDevice(){
	 
		$devicedatas = $this->gaDataDeviceAll;
		 
		if($devicedatas){
			$this->setSocketDB(0);
			foreach($devicedatas as $datetimes => $devicedata){
				foreach($devicedata as $key => $val){
				// tbl_ga_average_page_view_daily
					$gaDataQuery = "
					INSERT INTO {$this->DB}.ga_daily_device_all_data ( visits , device, date_d) VALUES ( {$val},'{$key}','{$datetimes}') ON DUPLICATE KEY UPDATE  visits={$val},device='{$key}' ;
					";
					$gaData = $this->query($gaDataQuery);
					// tbl_ga_time_on_site_daily num, date_d
				
					if($gaData) $data['gaData'][] ="success";
					else $data['gaData'][] ="failed".$gaDataQuery;
					
					$sql[] = $gaDataQuery;
				}
			}
		}else $data[]='there is not left data DEVICE of GA';
		print_r('<pre>');print_r($data);
		// print_r (array( 0 => implode("; ",$sql)));
		// print "
		// ";
	}
}

$class = new ga_bot;
$class->insertDataGa();
$class->insertDataGaAllDevice();
die();



?>

