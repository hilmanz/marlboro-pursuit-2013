<?php 

class inboundHelper {

	function __construct($apps){
		global $logger;
		$this->logger = $logger;
		$this->apps = $apps;
		
		$this->dbclass = "marlborohunt";	
		
		$this->week = 7;
		$week = intval($this->apps->_request('weeks'));
		if($week!=0) $this->week = $week;
		
		$this->startweekcampaign = "2013-05-20";
		$this->datetimes = date("Y-m-d H:i:s");
		// pr($this->apps->_request('week'));
	}

	
	
	function runservice(){	
	

		$sql = "
		SELECT * FROM social_member  sm		
		WHERE n_status = 2 AND usertype=0 AND verified = 1 ";
		$list = $this->apps->fetch($sql,1);
		if($list){
			$arrUserid = false;
			$arrCity = false;
			$sbranddata = false;
			$scitydata = false;
			$arrgiididtype = false;
			$sgiiddata = false;
			$questionmark = array("000","DB9","LV9","OS9","WT9","ZZ9");
			$brands = "
			SELECT * FROM brands ";
			$branddata = $this->apps->fetch($brands,1);
			
			if($branddata){
				foreach($branddata as $val ){			
					$branddictionary[$val['id']]=$val;
				}
			
			}
			
			foreach($list as $key => $val){
				$arrUserid[$val['id']] = $val['id'];
				$arrCity[$val['city']] = $val['city'];
				$arrgiididtype[$val['giid_type']] = $val['giid_type'];
			}
			
			if($arrUserid){
				$struserid = implode(',',$arrUserid);
				
				$sql = "SELECT * FROM social_brand_preferences WHERE userid IN ({$struserid}) ";
				$brandpref = $this->apps->fetch($sql,1);
				
				if($brandpref){
					foreach($brandpref as $key => $val){
							if(array_key_exists($val['brand_primary'],$branddictionary)) $brandpref[$key]['brand_primary_name'] = $branddictionary[$val['brand_primary']];
							if(array_key_exists($val['brand_secondary'],$branddictionary)) $brandpref[$key]['brand_secondary_name'] = $branddictionary[$val['brand_secondary']];
							if(array_key_exists($val['question_mark'],$questionmark)) $brandpref[$key]['question_mark_name'] = $questionmark[$val['question_mark']];
							$sbranddata[$val['userid']] = $brandpref[$key];
					}
					
					
				}
				
				
			}
			
			if($arrCity){
				$strcityid = implode(',',$arrCity);
				
				$sql = "SELECT * FROM {$this->dbclass}_city_reference WHERE id IN ({$strcityid}) ";
				$citydata = $this->apps->fetch($sql,1);
				
				if($citydata){
					foreach($citydata as $val){
							$scitydata[$val['id']]=$val;					
					}
				}
				
				
			}
			
			if($arrgiididtype){
				$strgiididtype = implode(',',$arrgiididtype);
				
				$sql = "SELECT * FROM giid_type WHERE id IN ({$strgiididtype}) ";
				$giiddata = $this->apps->fetch($sql,1);
				
				if($giiddata){
					foreach($giiddata as $val){
							$sgiiddata[$val['id']]=$val;					
					}
				}
				
				
			}
			
			
			foreach($list as $key => $val){
				/* city list */
				$list[$key]['citydetail'] = false;
				$list[$key]['branddetail'] = false;
				if($scitydata) 	if(array_key_exists($val['city'],$scitydata)) $list[$key]['citydetail'] =  $scitydata[$val['city']];
				if($sbranddata) if(array_key_exists($val['id'],$sbranddata)) $list[$key]['branddetail'] =  $sbranddata[$val['id']];
				if($sgiiddata) if(array_key_exists($val['giid_type'],$sgiiddata)) $list[$key]['giiddetail'] =  $sgiiddata[$val['giid_type']];
				
				
				/* conditioning */
				$list[$key]['phone_number'] =  str_replace("-","",$list[$key]['phone_number']);
				$list[$key]['sex'] = substr($val["sex"],0,1);
			
			}
			
		
		}
		
		
		$data = $this->addToXML($list);
		if($data['result']){
			$datetime = date("Y-m-d H:i:s");
			$sql ="
				INSERT INTO archlight_inbound (filename,record,datetime,n_status)
				VALUES (\"{$data['filename']}\",\"{$data['record']}\",\"{$datetime}\",1)
			";
			$this->apps->query($sql);
			if($this->apps->getLastInsertId()) return 'SUCCESS UPLOAD DATA TO SERVER';
		}
		
		return 'FAILED UPLOAD DATA TO SERVER';
	}
	
	function trim_value($val) 
		{ 
			$value = trim($val); 
		}
	
	function addToXML($list=false){

		if($list==false) return false;
		
		$CampaignNumber = "PH13000421O01";
		$CampaignPhase = "XXXA";
		$Audience = "XXX1";
		$MediaCategory = "ODM";
		$OfferCategory = "400";
		$OfferCode = "400403";
		$SignatureReasonCode = "W";
		$AgeVerificationType = "G";
		// pr($list);exit;
		
		$arrRecord = false;
		
		foreach($list as $val){
		$val['StreetName'] = preg_replace('/[^A-Za-z0-9]/', ' ', $val['StreetName']);
		$val['StreetName'] = ($val['StreetName']);
		$val['citydetail']['city'] = preg_replace('/[^A-Za-z0-9]/', ' ', $val['citydetail']['city']);
		$val['barangay'] = preg_replace('/[^A-Za-z0-9]/', ' ', $val['barangay']);
		$val['citydetail']['provinceName'] = preg_replace('/[^A-Za-z0-9]/', ' ', $val['citydetail']['provinceName']);
		$val['zipcode'] = preg_replace('/[^A-Za-z0-9]/', ' ', $val['zipcode']);

		
			$datetime = date("Y-m-d");
			if($val["sex"]=="M") $prefix = "MR";
			else $prefix = "MRS";
			
			$dataXML = "<Record>";
			$dataXML .= "<VendorCode>A12</VendorCode>";
			$dataXML .= "<DocumentReferenceNumber></DocumentReferenceNumber>";
			$dataXML .= "<DateOfProcessing>{$datetime}</DateOfProcessing>";
			$dataXML .= "<LocalMarketCode>PH</LocalMarketCode>";
			$dataXML .= "<Prefix>{$prefix}</Prefix>";
			$dataXML .= "<FirstName>{$val['name']}</FirstName>";
			$dataXML .= "<MiddleInitial>{$val['middle_name']}</MiddleInitial>";
			$dataXML .= "<ThirdName>{$val['last_name']}</ThirdName>";
			$dataXML .= "<NickName>{$val['nickname']}</NickName>";
			$dataXML .= "<Gender>{$val['sex']}</Gender>";
			$dataXML .= "<CurrentAddress>";
			// $dataXML .= "<HouseNumber></HouseNumber>";
			$dataXML .= "<StreetName>{$val['StreetName']}</StreetName>";
			$dataXML .= "<City>{$val['citydetail']['city']}</City>";
			// $dataXML .= "<Village></Village>";
			$dataXML .= "<District>{$val['barangay']}</District>";
			// $dataXML .= "<Block></Block>";
			$dataXML .= "<State>{$val['citydetail']['provinceName']}</State>";
			$dataXML .= "<PostalCode>{$val['zipcode']}</PostalCode>";
			$dataXML .= "<Country>PH</Country>";
			$dataXML .= "</CurrentAddress>";
			$dataXML .= "<OfficePhone></OfficePhone>";
			$dataXML .= "<OfficePhoneExtension></OfficePhoneExtension>";
			$dataXML .= "<HomePhone></HomePhone>";
			$dataXML .= "<MobilePhone>{$val['phone_number']}</MobilePhone>";
			$dataXML .= "<MobileServiceProvider></MobileServiceProvider>";
			$dataXML .= "<EmailAddress>{$val['email']}</EmailAddress>";
			$dataXML .= "<DateOfBirth>{$val['birthday']}</DateOfBirth>";
			$dataXML .= "<DateOfCapture>{$datetime}</DateOfCapture>";
			$dataXML .= "<SignatureReasonCode>W</SignatureReasonCode>";
			$dataXML .= "<AgeVerificationType>G</AgeVerificationType>";
			$dataXML .= "<GovernmentIDType>{$val['giiddetail']['giid_code']}</GovernmentIDType>";
			$dataXML .= "<GovernmentIDNumber>{$val['giid_number']}</GovernmentIDNumber>";
			$dataXML .= "<OptOffDirectMail></OptOffDirectMail>";
			$dataXML .= "<OptOffHomePhone></OptOffHomePhone>";
			$dataXML .= "<OptOffMobilePhone></OptOffMobilePhone>";
			$dataXML .= "<OptOffDigital></OptOffDigital>";
			$dataXML .= "<OptOffAllChannels></OptOffAllChannels>";
			$dataXML .= "<OptOffTelemarketing></OptOffTelemarketing>";
			$dataXML .= "<OptOffOfficePhone></OptOffOfficePhone>";
			$dataXML .= "<DMDeliverabilityCode>3M</DMDeliverabilityCode>";
			$dataXML .= "<HomePhoneDeliverabilityCode></HomePhoneDeliverabilityCode>";
			$dataXML .= "<MobilePhoneDeliverabilityCode>3P</MobilePhoneDeliverabilityCode>";
			$dataXML .= "<DigitalDeviceDeliverabilityCode>3D</DigitalDeviceDeliverabilityCode>";
			$dataXML .= "<OfficePhoneDeliverabilityCode></OfficePhoneDeliverabilityCode>";
			$dataXML .= "<HomePhoneDeliverabilityReason></HomePhoneDeliverabilityReason>";
			$dataXML .= "<MobilePhoneDeliverabilityReason>VDP</MobilePhoneDeliverabilityReason>";
			$dataXML .= "<OfficePhoneDeliverabilityReason></OfficePhoneDeliverabilityReason>";
			$dataXML .= "<DigitalDeviceDeliverabilityReason>VDD</DigitalDeviceDeliverabilityReason>";
			$dataXML .= "<DMDeliverabilityReason>VDM</DMDeliverabilityReason>";
			$dataXML .= "<CurrentBrand>{$val['branddetail']['brand_primary_name']['code']}</CurrentBrand>";
			$dataXML .= "<CurrentBrandAffinity>0</CurrentBrandAffinity>";
			$dataXML .= "<CurrentBrandFlavor>{$val['branddetail']['brand_primary_name']['flavor']}</CurrentBrandFlavor>";
			$dataXML .= "<CurrentBrandTarLevel>{$val['branddetail']['brand_primary_name']['tar']}</CurrentBrandTarLevel>";
			$dataXML .= "<BrandBuyKind></BrandBuyKind>";
			$dataXML .= "<BrandPurchaseTimespan></BrandPurchaseTimespan>";
			$dataXML .= "<AlternatePurchaseIndicator>{$val['branddetail']['question_mark_name']}</AlternatePurchaseIndicator>";
			$dataXML .= "<FirstAlternateBrand>{$val['branddetail']['brand_secondary_name']['code']}</FirstAlternateBrand>";
			$dataXML .= "<FirstAlternateBrandAffinity>0</FirstAlternateBrandAffinity>";
			$dataXML .= "<SecondAlternateBrand>{$val['branddetail']['brand_secondary_name']['code']}</SecondAlternateBrand>";
			$dataXML .= "<SecondAlternateBrandAffinity>0</SecondAlternateBrandAffinity>";
			$dataXML .= "<FirstBrandFlavor>{$val['branddetail']['brand_secondary_name']['flavor']}</FirstBrandFlavor>";
			$dataXML .= "<FirstBrandTarLevel>{$val['branddetail']['brand_secondary_name']['tar']}</FirstBrandTarLevel>";
			$dataXML .= "<CampaignNumber>{$CampaignNumber}</CampaignNumber>";
			$dataXML .= "<CampaignPhase>{$CampaignPhase}</CampaignPhase>";
			$dataXML .= "<Audience>{$Audience}</Audience>";
			$dataXML .= "<MediaCategory>{$MediaCategory}</MediaCategory>";
			$dataXML .= "<OfferCategory>{$OfferCategory}</OfferCategory>";
			$dataXML .= "<OfferCode>{$OfferCode}</OfferCode>";
			$dataXML .= "<EventConfirmation></EventConfirmation>";
			$dataXML .= "<CollectionCity></CollectionCity>";
			$dataXML .= "<PromoterSignature></PromoterSignature>";
			$dataXML .= "<PromoterSignatureDate></PromoterSignatureDate>";
			$dataXML .= "<MMSCapability></MMSCapability>";
			$dataXML .= "<ThreeGCapability></ThreeGCapability>";
			$dataXML .= "<CollectionPlace></CollectionPlace>";
			$dataXML .= "<PromoterCode></PromoterCode>";
			$dataXML .= "<WebId>{$val['id']}</WebId>";
			$dataXML .= "<PreferredCommunicationModeCode></PreferredCommunicationModeCode>";
			$dataXML .= "<OfflineDataEntryRegistrationId></OfflineDataEntryRegistrationId>";
			$dataXML .= "<TransactionalCode></TransactionalCode>";
			$dataXML .= "</Record>";
			
			$arrRecord[] = $dataXML;
			$dataXML = "";

			
		}
		
		
	
		if($arrRecord){
			$totalRecord = count($arrRecord);
			$xmlContent = implode('',$arrRecord);
			
			$xmlHeader = '<?xml version="1.0"?><InboundDMFilePH xmlns="http://pmiap-arclight.arcww2.com/Inbound/2009-01" xmlns:xs="http://www.w3.org/2001/XMLSchema">';		
			$xmlFooter = '</InboundDMFilePH>';
			
			$xml = "{$xmlHeader}{$xmlContent}{$xmlFooter}";
			// pr($xml);exit;
				$datetime = date("Ymd");
				$timeHours = date("Hm");
				$charsize = strlen($xml);
				/* sftp */
				GLOBAL $ENGINE_PATH, $CONFIG;
				
				require_once $ENGINE_PATH."Utility/phpseclib/Net/SFTP.php";
					
				try{
						$sftp = new Net_SFTP('transfer.pconnect.biz');
						if (!$sftp->login('jlorenzo1', 'Lockout123')) {
							return array('result'=>false);
						}
						$filename ="PMA_PH_A12_InboundDMFilePH_{$datetime}_{$timeHours}_{$totalRecord}_{$charsize}.xml";
						$sftp->chdir("/Distribution/Hippodrome/ArcLight Inbound - AP/drop_off/");					
						$sftp->put($filename, $xml);
						
						// echo $sftp->pwd();
						// nlist: glob 
						 // pr($sftp->nlist());
						// pr($sftp->getSFTPErrors());exit;
						return array('result'=>true,'record'=>$totalRecord,'filename'=>$filename);
				}catch (Exception $e){
							return array('result'=>false);	
				}
			
			/* sftp */
			
			
			
		}
		return array('result'=>false);
	}
	
	
}

?>

