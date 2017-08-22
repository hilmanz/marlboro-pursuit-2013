<?php

global $ENGINE_PATH;
include_once $ENGINE_PATH."Utility/Paginate.php";
		
class outbound extends Admin{
	var $category;
	var $type;
	function __construct(){	
		parent::__construct();	
		
		$this->type = "1,3,4,5,6";
		$this->contentType = "0";
		$this->folder =  'outbound';
		$this->dbclass = 'marlborohunt';
		$this->fromwho = 0; // 0 is admin/backend
		$this->total_per_page = 20;
		
	}
	
	function admin(){
		
		global $CONFIG;
	
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
		$startdate = false;
		$enddate = false;
		$start = intval($this->_g('st'));
		
		/* Hitung banyak record data */
		$sql ="
			SELECT count(*) total
			FROM archlight_outbound
			";
		$totalList = $this->fetch($sql);	
		
								 
		if($totalList){
		$total = intval($totalList['total']);
		}else $total = 0;
		 //$totalList = implode('',$total);
		  // pr($totalList);
		 // $total = " INSERT INTO archlight_outbound (id,filename,record_succes,record_failed,DATETIME,n_status)
					// VALUES (' ', ' ', '{$total}', ' ', ' ', ' ' ) ";
		
		// $totalSucces = implode(' ', ' ', '{$total}', ' ', ' ', ' ' );
		
		/* list article */
		$sql = "
			SELECT *
			FROM archlight_outbound
			WHERE n_status<>3
			{$filter}
			ORDER BY datetime DESC 
			LIMIT {$start},{$this->total_per_page}
		";
	
		$list = $this->fetch($sql,1);
		
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

	
	function generate(){	
		$id = $this->_g('id');
		/* sftp */
			GLOBAL $ENGINE_PATH, $CONFIG;
			
			require_once $ENGINE_PATH."Utility/phpseclib/Net/SFTP.php";
				
			try{
					$sftp = new Net_SFTP('transfer.pconnect.biz');
					if (!$sftp->login('jlorenzo1', 'Cheers2u')) {
						return array('result'=>false);
					}
				
					// Put to Jlorenzo SFTP
					$sftp->chdir("/Distribution/Hippodrome/ArcLight Outbound/A12/pick_up/");				
					$files = $sftp->nlist();
					$realfiles = false;
					foreach($files as $val){
						if(preg_match("/.xml/i",$val)) {
							$sql = "SELECT count(*) total FROM archlight_outbound WHERE filename='{$val}' LIMIT 1 ";
							$qData = $this->fetch($sql);
							if($qData['total']==0)	$realfiles[] = $val;
							
						}
					}
					if($realfiles){	
						$arrDataXML = false;
							
						foreach($realfiles as $val){
							$arrDataXML[$val] = $sftp->get($val);
						}
						// echo $sftp->pwd();
						// nlist: glob 
						if($arrDataXML){
						$dataXML = false;
							foreach($arrDataXML as $key=> $val){
								$dataXML[$key] = simplexml_load_string($val);
							}
						}
						if($dataXML){
							foreach($dataXML as $key => $xml){
								$reportdata[] = $this->insertUpdateTable($xml,$key);
							}
						}
						
						return $this->View->showMessage('UPLOADED DATA TO SERVER', "index.php?s={$this->folder}");
					}else{
						return $this->View->showMessage('DATA ALREADY EXISTS', "index.php?s={$this->folder}");
					}
			}catch (Exception $e){
					return $this->View->showMessage('FAILED UPLOAD DATA TO SERVER', "index.php?s={$this->folder}");
			}
			
			/* sftp */
			return $this->View->showMessage('FAILED UPLOAD DATA TO SERVER', "index.php?s={$this->folder}");
		
	}
	
	
	
	function generatelocal(){	
		
		/* sftp */
			GLOBAL $ENGINE_PATH, $CONFIG;
		
					
					$xml = simplexml_load_file("D:\PMA_PH_A12_PmiPhFileDm_PH13000410d01_20130512_062742.xml");
					
	
					$this->insertUpdateTable($xml,"PMA_PH_A12_PmiPhFileDm_PH13000410d01_20130512_062742.xml");
					return $this->View->showMessage('UPLOADED DATA TO SERVER', "index.php?s={$this->folder}");

					
			/* sftp */
			return $this->View->showMessage('UPLOADED DATA TO SERVER', "index.php?s={$this->folder}");
		
	}
	
	
	
	function insertUpdateTable($xml=null,$filename=null){
		if($xml==null) return false;
		$sqlData = false;
		$sqlInsertData = false;
		$record['success'] = 0;
		$record['failed'] = 0;
		$success = 0;
		$counter = 0;
		$failed = 0;
		if(preg_match("/PmiPhFileDm/i",$filename)) $type = 2;
		else  $type = 0;
		if($type==2) $n_status = 1;
		else $n_status = 0;
			foreach( $xml as $record ) 
				{
					//backup full record ada di full.php
					$registerid 				= $record->IndividualID;
					$name						= $record->FirstName; 
					$middlename					= $record->SecondName; 
					$nickname					= $record->Nickname;	
					$lastname					= $record->ThirdName;	
					$email				 		= $record->EmailAddress;
					$giid				 		= $record->GovernmentIDNumber;
					$register_date		 		= $record->AudienceDropDate;  
					$city			 			= $record->CurrentAddress->City; 
					$phone_number 		 		= $record->MobilePhoneNumber;
					$sex			 	 		= $record->Gender; 
					$birthday 			 		= $record->DateOfBirth;
					$StreetName 			 	= $record->CurrentAddress->StreetName;
								
					$userid						= $record->id;
					$MarketCode					= $record->MarketCode;
					$ConsumerId					= $record->IndividualID;
					$District					= $record->CurrentAddress->District;
					$Province					= $record->CurrentAddress->Province;
					$PostalCode					= $record->CurrentAddress->PostalCode;
					$CampaignCode				= $record->CampaignCode;
					$PhaseCode					= $record->PhaseCode;
					$MediaCategoryCode			= $record->MediaCategoryCode;
					$MobileServiceProvider 		= $record->MobileServiceProvider;
					$SignatureReasonCode		= $record->SignatureReasonCode;
					$AgeVerificationType		= $record->AgeVerificationType;
					$GovernmentIDType			= $record->GovernmentIDType;
					$GovernmentIDNumber			= $record->GovernmentIDNumber;
					$OptOffDirectMail			= $record->OptOffDirectMail;
					$OptOffMobilePhone			= $record->OptOffMobilePhone;
					$OptOffAllChannels			= $record->OptOffAllChannels;
					$CurrentBrand				= $record->CurrentBrand;	
					$CurrentBrandAffinity		= $record->CurrentBrandAffinity;
					$CurrentBrandFlavor			= $record->CurrentBrandFlavor;
					$CurrentBrandTarLevel		= $record->CurrentBrandTarLevel;
					$AlternatePurchaseIndicator = $record->AlternatePurchaseIndicator;
					$FirstAlternateBrand		= $record->FirstAlternateBrand;
					$SecondAlternateBrand		= $record->SecondAlternateBrand;
					$FirstBrandFlavor			= $record->FirstBrandFlavor;
					$FirstBrandTarLevel			= $record->FirstBrandTarLevel;
					
					$offlineuser				= $record->OfflineDataEntryRegistrationId;
					
					// if($offlineuser!=0) $type = 1;
					
					pr($xml);
					
					$salt = md5(date("ymdhis")."outboundartlight".$email);
					$password = $this->substringshash(sha1($salt.date('Y-m-d H:i:s')),10);
					
					if($registerid!=''||$registerid!=0) $n_status = 1;
					else $n_status = 0;
					//	Update and save to database
				
					//get city
					$citysql = "SELECT id FROM marlborohunt_city_reference WHERE city=\"{$city}\" LIMIT 1";
					pr($citysql);exit;
					$arrCity = $this->fetch($citysql);
					$city = 0;
					if($arrCity){
						$city = $arrCity['id'];
					}
					
					//get brand
					// $brandsql = "SELECT * FROM social_brand_preferences WHERE userid=\"{$id}\" LIMIT 1";
					// pr($brandsql);exit;
					// $brandpref = $this->fetch($brandsql);
					
					
					$sqlData = " INSERT INTO social_member 					(name,last_name,middle_name,nickname,register_date,city,phone_number,sex,birthday,registerid,email,giid_number,username,salt,password,usertype,n_status,StreetName,barangay,zipcode)
					VALUES
					(\"{$name}\",\"{$lastname}\",\"{$middlename}\",\"{$nickname}\",'{$register_date}','{$city}','{$phone_number}','{$sex}','{$birthday}','{$ConsumerId}','{$email}','{$giid}','{$email}','{$salt}','{$password}','{$type}','{$n_status}',\"{$StreetName}\",\"{$District}\",'{$PostalCode}')					
					ON DUPLICATE KEY UPDATE
					name=\"{$name}\",
					last_name=\"{$lastname}\",
					nickname=\"{$nickname}\",
					city='{$city}',
					phone_number='{$phone_number}',
					sex='{$sex}', 
					birthday='{$birthday}',
					registerid ='{$ConsumerId}',
					email='{$email}' , 
					giid_number='{$giid}',
					username = '{$email}',
					n_status='{$n_status}',
					StreetName = \"{$StreetName}\",
					barangay = \"{$District}\",
					zipcode = '{$PostalCode}',
					middle_name = \"{$middlename}\",
					usertype = '{$type}'
					";	
					
					
					$sqlInsertData[] = " 
					INSERT INTO social_member_preference (MarketCode,ConsumerId,District,Province,PostalCode,CampaignCode,PhaseCode,MediaCategoryCode,MobileServiceProvider,SignatureReasonCode,AgeVerificationType,GovernmentIDType,GovernmentIDNumber,OptOffDirectMail,OptOffMobilePhone,OptOffAllChannels,CurrentBrand,CurrentBrandAffinity,CurrentBrandFlavor,CurrentBrandTarLevel,AlternatePurchaseIndicator,FirstAlternateBrand,SecondAlternateBrand,FirstBrandFlavor,FirstBrandTarLevel) 
					VALUES ('{$MarketCode}','{$ConsumerId}','{$District}','{$Province}','{$PostalCode}','{$CampaignCode}','{$PhaseCode}','{$MediaCategoryCode}','{$MobileServiceProvider}','{$SignatureReasonCode}','{$AgeVerificationType}','{$GovernmentIDType}','{$GovernmentIDNumber}','{$OptOffDirectMail}','{$OptOffMobilePhone}','{$OptOffAllChannels}','{$CurrentBrand}','{$CurrentBrandAffinity}','{$CurrentBrandFlavor}','{$CurrentBrandTarLevel}','{$AlternatePurchaseIndicator}','{$FirstAlternateBrand}','{$SecondAlternateBrand}','{$FirstBrandFlavor}','{$FirstBrandTarLevel}') ";
					
					/*
					$sqlPutData[] = "
					INSERT INTO `social_brand_preferences` (`id`, `userid`, `brand_primary`, `brand_secondary`, `question_mark`, `other_answer`, `survey`, `consent`, `n_status`) VALUES
					('{$id}','{$userid}','{$brand_primary}','{$brand_secondary}','{$question_mark}','{$other_answer}', '{$survey}','{$consent}','{$n_status}')";
					*/
					// sleep(1);
					
							$qData = $this->query($sqlData);
							if( $this->getLastInsertId() ){
								$success++;
								$record['success']=$success;						
																							
								$userdata['email'] =$email;
								$userdata['firstname'] = $name;
								$userdata['lastname'] = $lastname;
								$userdata['username'] = $email;
								$userdata['password'] = $password;
								$userdata['trackingcode'] = "";
								
								$this->getEmailTemplate('welcomeweb',$userdata,'send');
					
							}else {
								$failed++;
								$record['failed']=$failed;
							}
							if($counter==100) {
								sleep(1);
								$counter = 0;							
							}else $counter++;
					
				}
			
				/*
				if($sqlData){
					$counter = 0;	
					foreach($sqlData as $val){	
						$qData = $this->query($val);
							if( $qData ){
								$success++;
								$record['success']=$success;						
																							
								$userdata['email'] =$email;
								$userdata['firstname'] = $name;
								$userdata['lastname'] = $lastname;
								$userdata['username'] = $email;
								$userdata['password'] = $password;
								$userdata['trackingcode'] = "";
								
								$this->getEmailTemplate('welcomeweb',$userdata,'send');
							}else {
								$failed++;
								$record['failed']=$failed;
							}
							if($counter==500) {
							sleep(1);
							$counter = 0;							
							}else $counter++;
					}
				}
				*/
				sleep(1);						
				$sql ="
				INSERT INTO archlight_outbound 
				(filename,record_succes,record_failed,datetime,n_status) 
				VALUES
				(\"{$filename}\",'{$record['success']}','{$record['failed']}','".date('Y-m-d H:i:s')."',1)
				";
				$this->query($sql);	
			
				// pr($sql);exit;
			
				if($sqlInsertData){
					$counter = 0;
					foreach($sqlInsertData as $val){				
						$this->query($val);
					
					if($counter==500) {
							sleep(1);
							$counter = 0;							
							}else $counter++;
				
					}
					
					
				}
				
				/*
				if($sqlPutData){
					$counter = 0;
					foreach($sqlPutData as $val){				
						$this->query($val);
					
					if($counter==500) {
							sleep(1);
							$counter = 0;							
							}else $counter++;
				
					}
					
					
				}
				*/
				return true;	
	}
	
	function getEmailTemplate($mailtemplate='welcomeweb',$userdata=false,$sendType='send'){
		global $CONFIG;
		/* user data is array field */
		if($userdata==false) return false;
		
		$host = "api2.silverpop.com";
		$adminuser = "inong@marlboro.ph";
		$adminpass = "Kana9i8u!";
		$servlet = "http://api2.silverpop.com/servlet/XMLAPI";

		$list_id = false;
		$mailid = false;
		$email = false;
		$firstname = false;
		$username = false;
		$password = false;
		$lastname = false;
		$trackingcode = false;
		$MAIL = false;
		/* sample 
			
			$arrData['email'] = "rizal@kana.co.id";
			$arrData['firstname'] = "rizal aja";
			$arrData['username'] = "rizal@kana.co.id";
			$arrData['password'] = "9234g2934h239h40203240239480298wrjoiwtowehtoerhtiuerhteukrtj";
			$arrData['lastname'] = "9234g2934h239h40203240239480298wrjoiwtowehtoerhtiuerhteukrtj";
			$arrData['trackingcode'] = "9234g2934h239h40203240239480298wrjoiwtowehtoerhtiuerhteukrtj";
		
		*/
		foreach($userdata as $key => $val){
			$arrData[$key] = $val;
			$$key = $val;
		}
	
		
		include "../../config/mail.inc.php";
		if($MAIL){
			$arrData['mailid'] =  $MAIL[$mailtemplate]['mailid'];
			$arrData['templatedataxml'] = $MAIL[$mailtemplate]['template'];
			
			if($sendType=='send') {
				$this->addRecipeForSilverPop($arrData,$adminuser,$adminpass, $host);
				sleep(1);
				$this->sendMailViaSilverPop($arrData,$adminuser,$adminpass, $host);
			}else $this->addRecipeForSilverPop($arrData,$adminuser,$adminpass, $host); 
		}
	}

	function addRecipeForSilverPop($arrData,$adminname,$adminpass,  $host, $servlet="XMLAPI", $port=80, $time_out=20){
	
	$servlet = $servlet;
	
	foreach($arrData as $key => $val){
		$$key = $val;
	}
	$sock = fsockopen ($host, $port, $errno, $errstr, $time_out); // open socket on port 80 w/ timeout of 20
	$data = "xml=<?xml version=\"1.0\" encoding=\"UTF-8\" ?><Envelope><Body>";
	$data .= "<Login>";
	$data .= "<USERNAME>".$adminname."</USERNAME>";
	$data .= "<PASSWORD>".$adminpass."</PASSWORD>";
	$data .= "</Login>";
	$data .= "<AddRecipient>";
	$data .= $templatedataxml;
	$data .= "</AddRecipient>";
	$data .= "</Body></Envelope>";
	if (!$sock)
	{
	print("Could not connect to host:". $errno . $errstr);
	return (false);
	}
	$size = strlen ($data);
	fputs ($sock, "POST /servlet/" . $servlet . " HTTP/1.1\n");
	fputs ($sock, "Host: " . $host . "\n");
	fputs ($sock, "Content-type: application/x-www-form-urlencoded\n");
	fputs ($sock, "Content-length: " . $size . "\n");
	fputs ($sock, "Connection: close\n\n");
	fputs ($sock, $data);
	$buffer = "";
	while (!feof ($sock)) {
	$buffer .= fgets ($sock);
	}
	// pr($data);
	fclose ($sock);
	return ($buffer);


	}


	function sendMailViaSilverPop($arrData,$adminname,$adminpass, $host, $servlet="XMLAPI", $port=80, $time_out=20){



	$servlet = $servlet;
	
	foreach($arrData as $key => $val){
		$$key = $val;
	}

	$sock = fsockopen ($host, $port, $errno, $errstr, $time_out); // open socket on port 80 w/ timeout of 20
	$data = "xml=<?xml version=\"1.0\" encoding=\"UTF-8\" ?><Envelope><Body>";
	$data .= "<Login>";
	$data .= "<USERNAME>".$adminname."</USERNAME>";
	$data .= "<PASSWORD>".$adminpass."</PASSWORD>";
	$data .= "</Login>";
	$data .= "<SendMailing>
	<MailingId>".$mailid."</MailingId>
	<RecipientEmail>".$email."</RecipientEmail>";	
	$data .= "</SendMailing></Body></Envelope>";
	if (!$sock)
	{
	print("Could not connect to host:". $errno . $errstr);
	return (false);
	}
	$size = strlen ($data);
	fputs ($sock, "POST /servlet/" . $servlet . " HTTP/1.1\n");
	fputs ($sock, "Host: " . $host . "\n");
	fputs ($sock, "Content-type: application/x-www-form-urlencoded\n");
	fputs ($sock, "Content-length: " . $size . "\n");
	fputs ($sock, "Connection: close\n\n");
	fputs ($sock, $data);
	$buffer = "";
	while (!feof ($sock)) {
	$buffer .= fgets ($sock);
	}
	// pr($data);
	fclose ($sock);
	return ($buffer);




	}
	
	function substringshash($hasher=null,$limit=10){
			if($hasher==null) return false;
			$strings = substr($hasher,0,$limit);
			
			return $strings;
	}
}
