<?php 

class outboundHelper {

	function __construct($apps){
		global $logger;
		$this->logger = $logger;
		$this->apps = $apps;
		
		$this->dbclass = "marlborohunt";	
	
	}

	
	function generate(){	
		
		/* sftp */
			GLOBAL $ENGINE_PATH, $CONFIG;
			
			require_once $ENGINE_PATH."Utility/phpseclib/Net/SFTP.php";
			
			try{
					
					$sftp = new Net_SFTP('transfer.pconnect.biz');
					if (!$sftp->login('jlorenzo1', 'Lockout123')) {
						$this->logger->log('stfp failedto login');
						return array('result'=>false);
					}
				
					// Put to Jlorenzo SFTP
					$sftp->chdir("/Distribution/Hippodrome/ArcLight Outbound/A10/pick_up/");				
					$files = $sftp->nlist();
					$realfiles = false;
					$this->logger->log("get search new files : ".json_encode($files));
					// $i = 0;
					foreach($files as $val){				
							
							if(preg_match("/.xml/i",$val)) {
								if(!preg_match("/_190104.xml/i",$val)){
									$sql = "SELECT count(*) total FROM archlight_outbound WHERE filename='{$val}' LIMIT 1 ";
									$qData = $this->apps->fetch($sql);
									if($qData['total']==0)	{
										 $realfiles[] = $val;
									}
								}
							}						
					}
					// pr($realfiles);exit;
					if($realfiles){	
						$this->logger->log("get new files : ".json_encode($realfiles));
						$arrDataXML = false;
								
						
						foreach($realfiles as $val){
									$sql ="
							INSERT INTO archlight_outbound 
							(filename,record_succes,record_failed,datetime,n_status) 
							VALUES
							(\"{$val}\",'0','0','".date('Y-m-d H:i:s')."',0)
							";
							$this->apps->query($sql);							
											
							$arrDataXML[$val] = $sftp->get($val);
						
							// sleep(1);
						}							
						// echo $sftp->pwd();
						// nlist: glob 
						if($arrDataXML){
						$dataXML = false;
							foreach($arrDataXML as $key=> $val){
								$dataXML[$key] = simplexml_load_string($val);
								
								// sleep(1);
							}
						}
						// pr($dataXML);exit;
						// $this->logger->log("check xml file : ".json_encode($dataXML));
						if($dataXML){
							// pr($dataXML);exit;
							foreach($dataXML as $key => $xml){
								
								$reportdata[] = $this->insertUpdateTable($xml,$key);
								
							}
						}
						
						return 'UPLOADED DATA TO SERVER';
					}else{
						return 'DATA ALREADY EXISTS';
					}
			}catch (Exception $e){
					return 'FAILED UPLOAD DATA TO SERVER';
			}
			
			/* sftp */
			return 'FAILED UPLOAD DATA TO SERVER';
		
	}
	
	function readInsertXMLData()
	{
		
		
		/* local */
			GLOBAL $ENGINE_PATH, $CONFIG;
			
			try{
					
					
					// Put to local directory
					$dir = "/home/webuser/www/marlboro.ph-phase02/tools/cron/outbound/";				
					// $dir = "D:/xml/";				
					
					$files =glob($dir.'*.xml',GLOB_BRACE);
					
					// pr($files);exit;
					$realfiles = false;
					$this->logger->log("get search new files : ".json_encode($files));
					// $i = 0;
					foreach($files as $val){				
							
							if(preg_match("/.xml/i",$val)) {
							
							$expl = explode('/',$val);
							
							// if(!preg_match("/_190104.xml/i",$val)){
									$sql = "SELECT count(*) total FROM archlight_outbound WHERE filename='{$expl['8']}' LIMIT 1 ";
									$qData = $this->apps->fetch($sql);
									if($qData['total']==0)	{
										 $realfiles[] = $expl['8'];
									}
								// }
							}						
					}
					// pr($realfiles);exit;
					if($realfiles){	
						$this->logger->log("get new files : ".json_encode($realfiles));
						$arrDataXML = false;
								
						
						foreach($realfiles as $val){
						
						$expl = explode('/',$val);
						
									$sql ="
							INSERT INTO archlight_outbound 
							(filename,record_succes,record_failed,datetime,n_status) 
							VALUES
							(\"{$val}\",'0','0','".date('Y-m-d H:i:s')."',0)
							";
							$this->apps->query($sql);							
											
							$arrDataXML[$val] = $val;
							// $arrDataXML[$val] = $sftp->get($val);
						
							// sleep(1);
						}							
						// echo $sftp->pwd();
						// nlist: glob 
						
						// pr($arrDataXML);
						if($arrDataXML){
						$dataXML = false;
							foreach($arrDataXML as $key=> $val){
								$dataXML[$key] = simplexml_load_file($dir.$val);
								
								// sleep(1);
							}
						}
						// pr($dataXML);exit;
						// $this->logger->log("check xml file : ".json_encode($dataXML));
						if($dataXML){
							// pr($dataXML);exit;
							foreach($dataXML as $key => $xml){
								
								$reportdata[] = $this->insertUpdateTable($xml,$key);
								
							}
						}
						
						// pr($reportdata);exit;
						return 'UPLOADED DATA TO SERVER';
					}else{
						return 'DATA ALREADY EXISTS';
					}
			}catch (Exception $e){
					return 'FAILED UPLOAD DATA TO SERVER';
			}
			
			/* sftp */
			return 'FAILED UPLOAD DATA TO SERVER';
		
	
	}
	
	function generatelocal(){	
		
		/* sftp */
			GLOBAL $ENGINE_PATH, $CONFIG;
		
					
					$xml = simplexml_load_file("D:\PMA_PH_A12_PmiPhFileDm_PH13000410d01_20130512_062742.xml");
				
	
					$this->insertUpdateTable($xml,"PMA_PH_A12_PmiPhFileDm_PH13000410d01_20130512_062742.xml");
					return 'UPLOADED DATA TO SERVER';
			
			
			/* sftp */
			return 'GAGAL UPLOAD DATA TO SERVER';
		
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
		if(preg_match("/PmiPhFileEmail/i",$filename)) $type = 1;
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
					$register_date		 		= date("Y-m-d H:i:s");  
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
					
					$offlineuser				= intval($record->OfflineDataEntryRegistrationId);
					
					if($offlineuser!=0) $type = 1;
					
					$salt = md5(date("ymdhis")."outboundartlight".$email);
					$password = $this->substringshash(sha1($salt.date('Y-m-d H:i:s')),10);
					
					if($registerid!=''||$registerid!=0) $n_status = 1;
					else $n_status = 0;
				//	Update and save to database
				
					//get city
					$citysql = "SELECT id FROM marlborohunt_city_reference WHERE city=\"{$city}\" LIMIT 1";
					// pr($citysql);exit;
					$arrCity = $this->apps->fetch($citysql);
					$city = 0;
					if($arrCity){
						$city = $arrCity['id'];
					}
					
					//get brand
					// $brandsql = "SELECT * FROM social_brand_preferences WHERE userid=\"{$id}\" LIMIT 1";
					// pr($brandsql);exit;
					// $brandpref = $this->apps->fetch($brandsql);
					
					// store name and email to var
					
					
					
					
					$sqlData = " INSERT INTO social_member					(name,last_name,middle_name,nickname,register_date,city,phone_number,sex,birthday,registerid,email,giid_number,username,salt,password,usertype,n_status,StreetName,barangay,zipcode)
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
					// pr($sqlData);exit;
					
					$sqlInsertData[] = " 
					INSERT INTO social_member_preference (MarketCode,ConsumerId,District,Province,PostalCode,CampaignCode,PhaseCode,MediaCategoryCode,MobileServiceProvider,SignatureReasonCode,AgeVerificationType,GovernmentIDType,GovernmentIDNumber,OptOffDirectMail,OptOffMobilePhone,OptOffAllChannels,CurrentBrand,CurrentBrandAffinity,CurrentBrandFlavor,CurrentBrandTarLevel,AlternatePurchaseIndicator,FirstAlternateBrand,SecondAlternateBrand,FirstBrandFlavor,FirstBrandTarLevel) 
					VALUES ('{$MarketCode}','{$ConsumerId}','{$District}','{$Province}','{$PostalCode}','{$CampaignCode}','{$PhaseCode}','{$MediaCategoryCode}','{$MobileServiceProvider}','{$SignatureReasonCode}','{$AgeVerificationType}','{$GovernmentIDType}','{$GovernmentIDNumber}','{$OptOffDirectMail}','{$OptOffMobilePhone}','{$OptOffAllChannels}','{$CurrentBrand}','{$CurrentBrandAffinity}','{$CurrentBrandFlavor}','{$CurrentBrandTarLevel}','{$AlternatePurchaseIndicator}','{$FirstAlternateBrand}','{$SecondAlternateBrand}','{$FirstBrandFlavor}','{$FirstBrandTarLevel}') ";
					
					
					
					/*
					$sqlPutData[] = "
					INSERT INTO `social_brand_preferences` (`id`, `userid`, `brand_primary`, `brand_secondary`, `question_mark`, `other_answer`, `survey`, `consent`, `n_status`) VALUES
					('{$id}','{$userid}','{$brand_primary}','{$brand_secondary}','{$question_mark}','{$other_answer}', '{$survey}','{$consent}','{$n_status}')";
					*/
					// sleep(1);
					
							$qData = $this->apps->query($sqlData);
							if( $this->apps->getLastInsertId() ){
								$success++;
								$record['success']=$success;						
																							
								$userdata['email'] =$email;
								$userdata['firstname'] = $name;
								$userdata['lastname'] = $lastname;
								$userdata['username'] = $email;
								$userdata['password'] = $password;
								$userdata['trackingcode'] = "";
								
								$this->logger->log("inserted register id : ".$registerid);
								
								$this->getEmailTemplate('welcomeweb',$userdata,'send');
								
								if ($n_status==1){
								
									$botHelper = $this->apps->useHelper("botHelper");
									$uid = $this->apps->getLastInsertId();
									$botHelper->setPointByUser("SUCCES REGISTER", "registerpoint", 3,null,$uid);
									$this->logger->log("inserted register id : ".$registerid." id : ".$uid." to inventory 3 points");
								}
								
								
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
						$qData = $this->apps->query($val);
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
				ON DUPLICATE KEY UPDATE record_succes='{$record['success']}',record_failed='{$record['failed']}'
				";
				$this->apps->query($sql);	
			
				// pr($sql);exit;
			
				if($sqlInsertData){
					$counter = 0;
					foreach($sqlInsertData as $val){				
						$this->apps->query($val);
					
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
						$this->apps->query($val);
					
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
	
		
		include "../config/mail.inc.php";
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
	$this->logger->log("check sending recepient : ".$buffer);
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
	
	$this->logger->log("check sending mail : ".$buffer);
	// pr($data);
	fclose ($sock);
	return ($buffer);




	}
	
	function substringshash($hasher=null,$limit=10){
			if($hasher==null) return false;
			$strings = substr($hasher,0,$limit);
			
			return $strings;
	}
	
	function getUserAfterRegister()
	{
		$sql = "SELECT id FROM social_member WHERE n_status = 1";
		$res = $this->apps->fetch($sql,1);
		if ($res) return $res;
		
		return false;
	}
	
}

?>

