<?php
class pursuit extends App{
	function beforeFilter(){
	
		$this->contentHelper = $this->useHelper('contentHelper');
		$this->activityHelper = $this->useHelper('activityHelper');
		$this->userHelper = $this->useHelper('userHelper');
		$this->newsHelper = $this->useHelper('newsHelper');
		$this->messageHelper = $this->useHelper('messageHelper');
		$this->uploadHelper  = $this->useHelper('uploadHelper');
		$this->eventHelper = $this->useHelper('eventHelper');
		global $LOCALE,$CONFIG;
		$this->assign('basedomain', $CONFIG['BASE_DOMAIN']);
		$this->assign('assets_domain', $CONFIG['ASSETS_DOMAIN_WEB']);
		
		$this->assign('locale', $LOCALE[1]);
		
	}
	
	function main()
	{
		global $CONFIG;
		sendRedirect($CONFIG['BASE_DOMAIN']."home");
		exit;
		
		// pr($this->user);
		$this->log('surf','pursuit_main');
		$this->View->assign('the_pursuit',$this->setWidgets('the_pursuit'));
		return $this->View->toString(TEMPLATE_DOMAIN_WEB .'apps/pursuit-pages.html');
	}
	
	function join()
	{
		
		// $this->getphrasedefault();
		
		$this->log('surf','pursuit_join');
		$userInfo = $this->user;
		
		$getUserProfile = $this->contentHelper->getUserProfile();
		
		if ($this->_p('invitePursuitFriends')){
			
			
			$inviteFriend = $this->contentHelper->invitePursuitFriends();
			if($inviteFriend) print json_encode(array('status'=>true));
			else  print json_encode(array('status'=>false));
			exit;
			// if ($inviteFriend) 
		}
		
		
		$makeTrade = $this->contentHelper->makeTradeCode();
		$getMyLetter = $this->contentHelper->getMyLetter();
		$getMyLetterDetail = $this->contentHelper->getMyLetterDetail();
		
		if ($getMyLetterDetail){
			foreach ($getMyLetterDetail as $key => $value){
				if ($key == 11){
					$getMyLetterDetail[$key]['type'] = 1;
				}
				if ($key == 9){
					$getMyLetterDetail[$key]['type'] = 1;
				}
				if ($key == 12){
					$getMyLetterDetail[$key]['type'] = 1;
				}
			}
		}
		// pr($getMyLetterDetail);
		if ($getMyLetter){
			// $getMyLetter = null;
			$this->View->assign('letterisavailable',count($getMyLetter));
			$this->View->assign('myletter',$getMyLetter);
		}
		
		
		$getMerchandise = $this->contentHelper->getRedeemPhrase();
		// pr($getMerchandise);
		if ($getMerchandise){
			$redeem = $this->contentHelper->redeemPrize();
			
			if ($redeem){
				if ($redeem['dont']){
					$this->View->assign('redeemdont',true);
				}
				if ($redeem['dontbe']){
					$this->View->assign('redeemdontbe',true);
				}
				if ($redeem['dontbea']){
					$this->View->assign('redeemdontbea',true);
				}
				if ($redeem['dontbeamaybe']){
					$this->View->assign('redeemdontbeamaybe',true);
				}
			}
			
			// pr($getMerchandise);
			if(array_key_exists('DONT',$getMerchandise))$this->View->assign('merchanDont',$getMerchandise['DONT']);
			if(array_key_exists('DONTBE',$getMerchandise))$this->View->assign('merchanDontbe',$getMerchandise['DONTBE']);
			if(array_key_exists('DONTBEA',$getMerchandise))$this->View->assign('merchanDontbea',$getMerchandise['DONTBEA']);
			if(array_key_exists('DONTBEAMAYBE',$getMerchandise))$this->View->assign('merchanDontbeamaybe',$getMerchandise['DONTBEAMAYBE']);
		}
		
		$this->View->assign('myletterdetail',$getMyLetterDetail);
		// $this->View->assign('message_box',$this->setWidgets('message_box'));
		$this->View->assign('task_list',$this->setWidgets('task_list'));
		// $this->View->assign('trade_request',$this->setWidgets('trade_request'));
		// $this->View->assign('accomplished_tasks',$this->setWidgets('accomplished_tasks'));
		// $this->View->assign('pursuit_updates',$this->setWidgets('pursuit_updates'));
		// $this->View->assign('pursuit_players',$this->setWidgets('pursuit_players'));
		$this->View->assign('popup_trading_floor',$this->setWidgets('popup_trading_floor'));
		$this->View->assign('popup_post_trade',$this->setWidgets('popup_post_trade'));
		$this->View->assign('popup_post_trade_message',$this->setWidgets('popup_post_trade_message'));
		$this->View->assign('popup_trade_open',$this->setWidgets('popup_trade_open'));
		$this->View->assign('popup_trade_confirm',$this->setWidgets('popup_trade_confirm'));
		$this->View->assign('popup_trade_success',$this->setWidgets('popup_trade_success'));
		$this->View->assign('popup_task',$this->setWidgets('popup_task'));
		$this->View->assign('popup_offline',$this->setWidgets('popup_offline'));
		$this->View->assign('popup_invitefriend',$this->setWidgets('popup_invitefriend'));
		$this->View->assign('popup_get_letter',$this->setWidgets('popup_get_letter'));
		$this->View->assign('popup_message_black',$this->setWidgets('popup_message_black'));
		
		$this->View->assign('user', $getUserProfile);
		
		return $this->View->toString(TEMPLATE_DOMAIN_WEB .'widgets/pursuit-join-pages.html');
	}
	
	function learnMore(){
		
		$this->log('surf', 'learn more');
		$getMerchandise = $this->contentHelper->getRedeemPhrase();
		// pr($getMerchandise);
		if ($getMerchandise){
			$redeem = $this->contentHelper->redeemPrize();
			
			if ($redeem){
				if ($redeem['dont']){
					$this->View->assign('redeemdont',true);
				}
				if ($redeem['dontbe']){
					$this->View->assign('redeemdontbe',true);
				}
				if ($redeem['dontbea']){
					$this->View->assign('redeemdontbea',true);
				}
			}
			
			// pr($getMerchandise);
			$this->View->assign('merchanDont',$getMerchandise['DONT']);
			$this->View->assign('merchanDontbe',$getMerchandise['DONTBE']);
			$this->View->assign('merchanDontbea',$getMerchandise['DONTBEA']);
		}
		return $this->View->toString(TEMPLATE_DOMAIN_WEB .'widgets/the_pursuit_learnmore.html');
	}
	
	function earnpoints(){
		
		$this->log('surf', 'earnpoints');
		$getMerchandise = $this->contentHelper->getRedeemPhrase();
		// pr($getMerchandise);
		if ($getMerchandise){
			$redeem = $this->contentHelper->redeemPrize();
			
			if ($redeem){
				if ($redeem['dont']){
					$this->View->assign('redeemdont',true);
				}
				if ($redeem['dontbe']){
					$this->View->assign('redeemdontbe',true);
				}
				if ($redeem['dontbea']){
					$this->View->assign('redeemdontbea',true);
				}
			}
			
			// pr($getMerchandise);
			$this->View->assign('merchanDont',$getMerchandise['DONT']);
			$this->View->assign('merchanDontbe',$getMerchandise['DONTBE']);
			$this->View->assign('merchanDontbea',$getMerchandise['DONTBEA']);
		}
		return $this->View->toString(TEMPLATE_DOMAIN_WEB .'widgets/the_pursuit_earnpoints.html');
	}
	
	
	function detail()
	{
		$this->View->assign('popup_game',$this->setWidgets('popup_game'));
		return $this->View->toString(TEMPLATE_DOMAIN_WEB .'widgets/pursuit-detail-pages.html');
	}
	
	function prize()
	{
		
		$this->log('surf','pursuit_prize');
		$getMerchandise = $this->contentHelper->getRedeemPhrase();
		// pr($getMerchandise);
		if ($getMerchandise){
			$redeem = $this->contentHelper->redeemPrize();
			
			if ($redeem){
				if ($redeem['dont']){
					$this->View->assign('redeemdont',true);
				}
				if ($redeem['dontbe']){
					$this->View->assign('redeemdontbe',true);
				}
				if ($redeem['dontbea']){
					$this->View->assign('redeemdontbea',true);
				}
			}
			
			// pr($getMerchandise);
			$this->View->assign('merchanDont',$getMerchandise['DONT']);
			$this->View->assign('merchanDontbe',$getMerchandise['DONTBE']);
			$this->View->assign('merchanDontbea',$getMerchandise['DONTBEA']);
		}
		
		
		$this->View->assign('popup_redeem_1',$this->setWidgets('popup_redeem_1'));
		$this->View->assign('popup_redeem_2',$this->setWidgets('popup_redeem_2'));
		$this->View->assign('popup_redeem_3',$this->setWidgets('popup_redeem_3'));
		$this->View->assign('popup_redeem_claim',$this->setWidgets('popup_redeem_claim'));
		
		return $this->View->toString(TEMPLATE_DOMAIN_WEB .'apps/pursuit-prize.html');
	}
	
	function ajaxPrizeRedeem(){
				$getMerchandise = $this->contentHelper->getRedeemPhrase();
				$datamerch = false;
				foreach($getMerchandise as $merch){
					foreach($merch as $val){
						$datamerch[$val['id']] = $val['stock'];
					}
				}
				print json_encode($datamerch);exit;
	}
	
	function sendMailAfterRedeem($template=null)
	{
		
		if ($template == null) return false;
		
		$id = $this->user->id;
		$data = $this->userHelper->getUserProfileByID($id);
		$userdata = false;
		if ($data){
			$userdata['email'] = $data['email'];
			$userdata['firstname'] = $data['name'];
			$userdata['lastname'] = $data['last_name'];
			
			
			$this->userHelper->getEmailTemplate($template,$userdata,'send');
			return true;
		}else{
			return false;
		}
		
	}
	
	function ajaxRedeem()
	{
		global $LOCALE;
		
		// if ($this->_p('redeem')){
		
			
			if ($this->_p('redeemDont')){
				
				
				$redeem = $this->contentHelper->redeemPrizeConfirm();
				// pr($redeem);
				
				if ($redeem){
					$this->log('redeemcode', 'Success redeem phrase DONT');
					if (isset($_SESSION['letter'])) unset($_SESSION['letter']);
					$this->sendMailAfterRedeem('redeemdont');
					print json_encode(array('status'=>true, 'rec'=>$redeem));
				}else{
				
					if (isset($_SESSION['letter'])) { 
						unset($_SESSION['letter']);
						print json_encode(array('status'=>true, 'rec'=>$redeem));
					}
				}
			
			}
			
			if ($this->_p('redeemDontBe')){
				
				$redeem = $this->contentHelper->redeemPrizeConfirm();
				
				if ($redeem){
					$this->log('redeemcode', 'Success redeem phrase DONTBE');
					if (isset($_SESSION['letter'])) unset($_SESSION['letter']);
					$this->sendMailAfterRedeem('redeemdontbe');
					print json_encode(array('status'=>true, 'rec'=>$redeem));
				}else{
				
					if (isset($_SESSION['letter'])) { 
					unset($_SESSION['letter']);
					print json_encode(array('status'=>true, 'rec'=>$redeem));
					}
				}
			
			}
			
			if ($this->_p('redeemDontBeA')){
				
				$redeem = $this->contentHelper->redeemPrizeConfirm();
				if ($redeem){
					$this->log('redeemcode', 'Success redeem phrase DONTBEA');
					if (isset($_SESSION['letter'])) unset($_SESSION['letter']);
					$this->sendMailAfterRedeem('redeemdontbea');
					print json_encode(array('status'=>true, 'rec'=>$redeem));
				}else{
					if (isset($_SESSION['letter'])) { 
					unset($_SESSION['letter']);
					print json_encode(array('status'=>true, 'rec'=>$redeem));
					}
				}
			
			}
			
			if ($this->_p('redeemDontBeAMayBe')){
				
				$redeem = $this->contentHelper->redeemPrizeConfirm();
				if ($redeem){
					$this->log('redeemcode', 'Success redeem phrase DONTBEAMAYBE');
					if (isset($_SESSION['letter'])) unset($_SESSION['letter']);
					$this->sendMailAfterRedeem('redeemdontbeamaybe');
					print json_encode(array('status'=>true, 'rec'=>$redeem));
				}else{
					if (isset($_SESSION['letter'])) { 
					unset($_SESSION['letter']);
					print json_encode(array('status'=>true, 'rec'=>$redeem));
					}
				}
			
			}
			
			
		// }
		
		if ($this->_p('redeemConfirm')){
				
				
			$confirm = $this->contentHelper->redeemConfirmDialog();
			// pr($redeem);
			if ($confirm){
				print json_encode(array('status'=>true, 'rec'=>$confirm));
			}else{
				print json_encode(array('status'=>false));
			}
		
		}
			
		if ($this->_p('redeemprize')){
			
			$getMyLetterDetail = $this->contentHelper->getMyLetterAlreadySet();
			
			if ($getMyLetterDetail){
				// check user set letter
				// give param to showing popup per phrase
				// phrase indicator of popup id to show up
				foreach ($getMyLetterDetail as $key => $value){
					// check phrase
					if ($value['total'] >0){
						$letter[] = $key;
					}
					
				}
				
				$dont = array(1,2,3,4);
				$dontbe = array(1,2,3,4,5,6);
				$dontbea = array(1,2,3,4,5,6,7);
				$dontbeamaybe = array(1,2,3,4,5,6,7,8,9,10,11,12);
				
				$getphrase = "";
				
				$dontbeamaybeFlag = array_intersect($letter, $dontbeamaybe);
				$dontbeaFlag = array_intersect($letter, $dontbea);
				$dontbeFlag = array_intersect($letter, $dontbe);
				$dontFlag = array_intersect($letter, $dont);
				
				if (count($dontFlag)==4){
					$getphrase = 'Dont';
				}
				if (count($dontbeFlag)==6){
					$getphrase = 'DontBe';
				}
				if (count($dontbeaFlag)==7){
					$getphrase = 'DontBeA';
				}
				if (count($dontbeamaybeFlag)==12){
					$getphrase = 'DontBeAMaybe';
				}
				
				if ($getphrase){
					// $redeemed = $this->contentHelper->firstcheckredeemitem($getphrase);
					// if(!$redeemed){
						$phrase = 'redeemPrize'.$getphrase;
						print json_encode(array('status'=>true, 'rec'=>$getMyLetterDetail, 'phrase'=>$phrase ));
					// }else print json_encode(array('status'=>false , 'message'=> $LOCALE[1]['redeemcaps'] ));
				}else{
					print json_encode(array('status'=>false));
				}
				
			}else{
				// $redeemed = $this->contentHelper->firstcheckredeemitem($getphrase);
				// if($redeemed) print json_encode(array('status'=>false , 'message'=> $LOCALE[1]['redeemcaps'] ));
				// else
				print json_encode(array('status'=>false));
			}
			
			//$this->log('redeemcode', 'Open redeem prize');
		}
		
		// if ($this->_p('chooseredeem')){
			
			if ($this->_p('redeemViewDont')){
				$redeem = $this->contentHelper->getRedeemDont();
				// pr($redeem);
				if ($redeem){
					
					foreach ($redeem['DONTBE'] as $value){
						
						if (empty($value['has'])){
							$flagAllow = true;
							
						}
					}
					
					
					// pr(isset($flagAllow));
					if (isset($flagAllow)){
					
						// if (!isset($_SESSION['showpopupredeem'])) $_SESSION['showpopupredeem'] = 'DONT';
						print json_encode(array('status'=>true, 'dont'=>$redeem['DONT'], 'dontbe'=>$redeem['DONTBE'], 'validate'=>$redeem['validate'], 'has'=>$redeem['DONT']['has']));
					}else{
						print json_encode(array('status'=>false));
					}
					
				}else{
					print json_encode(array('status'=>false));
				}
			}
			
			if ($this->_p('redeemViewDontBe')){
				$redeem = $this->contentHelper->getRedeemDontBe();
				// pr($redeem);
				if ($redeem){
					foreach ($redeem['DONTBEA'] as $value){
						if ($value['has']==""){
							$flagAllow = true;
						}
					}
					if (isset($flagAllow)){
						print json_encode(array('status'=>true, 'dontbe'=>$redeem['DONTBE'], 'dontbea'=>$redeem['DONTBEA'], 'validate'=>$redeem['validate'], 'has'=>$redeem['DONTBE']['has']));
					}else{
						print json_encode(array('status'=>false));
					}
					
				}else{
					print json_encode(array('status'=>false));
				}
			}
			
			if ($this->_p('redeemViewDontBeA')){
				$redeem = $this->contentHelper->getRedeemDontBeA();
				if ($redeem){
					foreach ($redeem['DONTBEAMAYBE'] as $value){
						if ($value['has']==""){
							$flagAllow = true;
						}
					}
					if (isset($flagAllow)){
						print json_encode(array('status'=>true, 'dontbea'=>$redeem['DONTBEA'], 'dontbeamaybe'=>$redeem['DONTBEAMAYBE'], 'validate'=>$redeem['validate'], 'has'=>$redeem['DONTBEA']['has']));
					}else{
						print json_encode(array('status'=>false));
					}
					
				}else{
					print json_encode(array('status'=>false));
				}
			}
			
			if ($this->_p('redeemViewDontBeAMaybe')){
				$redeem = $this->contentHelper->getRedeemDontBeAMaybe();
				// pr($redeem);
				if ($redeem){
					foreach ($redeem['COMPLETE'] as $value){
						if ($value['has']==""){
							$flagAllow = true;
						}
					}
					if (isset($flagAllow)){
						// pr($redeem);
						print json_encode(array('status'=>true, 'dontbeamaybe'=>$redeem['DONTBEAMAYBE'], 'complete'=>$redeem['COMPLETE'], 'validate'=>$redeem['validate'], 'has'=>$redeem['DONTBEAMAYBE']['has']));
					}else{
						print json_encode(array('status'=>false));
					}
					
				}else{
					print json_encode(array('status'=>false));
				}
			}
		// }
		
		exit;
	}
	
	function grandPrize()
	{
		
		$this->View->assign('popup_redeem_4',$this->setWidgets('popup_redeem_4'));
		return $this->View->toString(TEMPLATE_DOMAIN_WEB .'apps/pursuit-grand-prize.html');
	}
	
	function ajax()
	{
		
		if ($this->_p('popuptradingfloor')){
			$listTradeCode = $this->contentHelper->listTradeCode();
			// pr($listTradeCode);
			$this->log('surf', 'Open Trade Floor');
			if ($listTradeCode){
				print json_encode(array('status'=>true, 'rec'=>$listTradeCode['rec'], 'total'=>$listTradeCode['total']));
			}else{
				print json_encode(array('status'=>false));
			}
		}
		
		if ($this->_p('popuptradeletter')){
			
			$getData = $this->contentHelper->getDataTrade();
			// pr($getData);
			if ($getData){
				print json_encode(array('status'=>true, 'rec'=>$getData));
			}else{
				print json_encode(array('status'=>false));
			}
		}
		
		
		if ($this->_p('p_task')){
			
			
			// if (isset($_POST['id'])){
				// $id = $this->_p('id');
				// unset($_SESSION['idTaskOnSubmit']);
				// if ($id){
					// $_SESSION['idTaskOnSubmit'] = $id;
				// }
				
				// $getSessi = $_SESSION['idTaskOnSubmit'];
				// if ($getSessi){
					// print json_encode(array('status'=>true));
				// }else{
					// print json_encode(array('status'=>false));
				// }
				
				// exit;
			// }
			$_captcha = intval($this->_p('captcha'));
			$_valid = (md5($_captcha) == $_SESSION['simplecaptcha']) ? true : false;
			$_SESSION['simplecaptcha'] = "bed" . rand(00000000,99999999) . "bed";

			if($_valid) {
			$submitCode = $this->contentHelper->submitTaskCode();
			// pr($submitCode);
			if ($submitCode){
						print json_encode(array('status'=>true, 'letter'=>$submitCode));
					}else{
						print json_encode(array('status'=>false));
					}
			}else print json_encode(array('status'=>false,'result'=>'wrong captcha'));
		}
		
		if ($this->_p('getTrade')){
			
			// pr($_POST);
			
			if (isset($_SESSION['getTrade'])){
				unset($_SESSION['getTrade']);
			}
			$_SESSION['getTrade'] = $this->_p('getTrade');
			$setSession = $_SESSION['getTrade'];
			
			$getData = $this->contentHelper->getDataTrade($setSession);
			// pr($getData);
			if ($getData){
				// $this->log('surf', 'submit trade');
				print json_encode(array('status'=>true));
			}else{
				print json_encode(array('status'=>false));
			}
			
			
		}
		
		if ($this->_p('confirmTrade')){
			
			
			$getTrade = $this->contentHelper->getTradeFromFloor();
			
			$user = $this->contentHelper->getUserProfile($this->user->id);
			
			
			
			// foreach ($user as $key =>$value){
				// $data[$key] = $value;
			// }
			
			if ($getTrade){
				$getTrade['source']['name'] = $user['name'];
				$getTrade['source']['image_profile'] = $user['image_profile'];
				$getTrade['source']['photo_moderation'] = $user['photo_moderation'];
				
				if ($getTrade){
				
					$this->log('trading', $this->user->name. 'success trading Letter from trade floor');
					print json_encode(array('status'=>true, 'getrade'=>$getTrade['target'], 'user'=>$getTrade['source']));
				}else{
					print json_encode(array('status'=>false));
				}
			}else{
				$this->log('trading', $this->user->name. ' failed trading Letter from trade floor');
				print json_encode(array('status'=>false));
			}
		}
		
		if ($this->_p('cancelTrade')){
			
			$cancel = $this->contentHelper->cancelTrade();
			if ($cancel){
				$this->log('trading', $this->user->name.' Cancel Trade');
				print json_encode(array('status'=>true));
			}else{
				print json_encode(array('status'=>false));
			}
			
		}
		
		if ($this->_p('postTrade')){
			
			// pr($_POST);
			$lookTradeToPost = $this->contentHelper->lookTradeToPost();
			if ($lookTradeToPost){
				
				$this->log('trading', $this->user->name.' Success Trade A Letter to trading floor');
				print json_encode(array('status'=>true));
			}else{
				$this->log('trading', $this->user->name.' Failed Trade A Letter to trading floor');
				print json_encode(array('status'=>false));
			}
		}
		
		if ($this->_p('sendTradeReq')){
			$sendTradeReq = $this->contentHelper->sendTradeReq();
			if ($sendTradeReq){
				print json_encode(array('status'=>true));
			}else{
				print json_encode(array('status'=>false));
			}
			
		}
		
		if ($this->_p('tradeMesg')){
			$sendTradeMsg = $this->contentHelper->sendTradeMesg();
			if ($sendTradeMsg){
				$this->log('surf', $this->user->name.' Send message');
				print json_encode(array('status'=>true));
			}else{
				print json_encode(array('status'=>false));
			}
		}
		
		if ($this->_p('getPlayer')){
			$getPursuitPlayer = $this->contentHelper->searchPursuitPlayer();
			if ($getPursuitPlayer){
				// pr($getPursuitPlayer);
				$this->log('surf', 'search pursuit player');
				print json_encode(array('status'=>true, 'rec'=>$getPursuitPlayer));
			}else{
				print json_encode(array('status'=>false));
			}
		}
		
		if ($this->_p('deletemessage')){
			$deletemessage = $this->messageHelper->deleteMessage();
			if ($deletemessage){
				print json_encode(array('status'=>true));
			}else{
				print json_encode(array('status'=>false));
			}
			
		}
		
		if ($this->_p('setinboxstatus')){
			
			$param = intval($this->_p('param'));
			
			if (!$param){
				print json_encode(array('status'=>false));
				exit;
			}
			
			$array = array(1,2,3,4);
			if (in_array($param, $array)){
				$param = $param;
			}else{
				print json_encode(array('status'=>false));
				exit;
			}
			
			// @$_SESSION['statusinbox'] = $param;
			if(!isset($_SESSION['statusinbox'])){
				print json_encode(array('status'=>false));
				exit;
			}
			
			$getSession = @$_SESSION['statusinbox'];
			if ($getSession){
				
				if ($getSession !== $param){
					// echo 'ada';
					unset($_SESSION['statusinbox']);
					@$_SESSION['statusinbox'] = $param;
				}
				
				// pr($_SESSION['statusinbox']);
				print json_encode(array('status'=>true, 'data'=>@$_SESSION['statusinbox']));
			}else{
				print json_encode(array('status'=>false));
			}
			
		}
		
		if ($this->_p('getinboxstatus')){
			// echo 'adaadasda';
			
			
			
			$compose = $this->_p('compose');
			if ($compose==1){
				$_SESSION['statusinbox'] = 5;
			}else{
				if (!isset($_SESSION['statusinbox'])){
					$_SESSION['statusinbox'] = 1;
				}else{
					if ($_SESSION['statusinbox']==5)$_SESSION['statusinbox'] = 1;
				}
			}
			
			$getSession = @$_SESSION['statusinbox'];
			
			if ($getSession){
				
				if ($getSession ==1) $class = 'inboxbutton';
				if ($getSession ==2) $class = 'notificationbutton';
				if ($getSession ==3) $class = 'sentbutton';
				if ($getSession ==4) $class = 'trashbutton';
				if ($getSession ==5) $class = 'composebutton';
				
				print json_encode(array('status'=>true, 'rec'=>$getSession, 'classstyle'=>$class));
			}else{
				print json_encode(array('status'=>false));
			}
		}
		
		exit;
	}
	
	function task()
	{
		
		if ($this->_p('entercodetask')){
			$taskList = $this->contentHelper->taskList();
			if ($taskList){
				// pr($getPursuitPlayer);
				print json_encode(array('status'=>true, 'rec'=>$taskList));
			}else{
				print json_encode(array('status'=>false));
			}
		}
		
		if ($this->_p('tasklist')){
			$taskList = $this->contentHelper->taskList();
			if ($taskList){
				// pr($taskList['rec']);
				print json_encode(array('status'=>true, 'rec'=>$taskList['rec'], 'total'=>$taskList['total']));
			}else{
				print json_encode(array('status'=>false));
			}
		}
		
		if ($this->_p('taskdetail')){
			$taskList = $this->contentHelper->getDetailArticle();
			if ($taskList){
				// pr($getPursuitPlayer);
				print json_encode(array('status'=>true, 'rec'=>$taskList));
			}else{
				print json_encode(array('status'=>false));
			}
		}
		
		if ($this->_p('taskmessage')){
		
			$start = intval($this->_p('start'));
			
			// $end = $this->_p('end');
			if (!isset($_SESSION['statusinbox'])) $_SESSION['statusinbox'] = 1;
			
			$session = @$_SESSION['statusinbox'];
			
			// pr($_SESSION['statusinbox']);exit;
			if ($session==1){ $myMesg = $this->contentHelper->getMymesgTrade($start,5);}
			if ($session==2){ $myMesg = $this->contentHelper->getMyNotification($start,5);}
			if ($session==3){ $myMesg = $this->contentHelper->getMyMessgSent($start,5);}
			if ($session==4){ $myMesg = $this->contentHelper->getMyMessgTrash($start,5);}
			// if ($session==5){ $myMesg = $this->contentHelper->getMyMessgTrash($start,5);}
			
			
			if ($myMesg){
				// pr($myMesg);
				 // $this->contentHelper->getMymesgTrade();
				print json_encode(array('status'=>true, 'rec'=>$myMesg['data'], 'total'=>$myMesg['total']));
			}else{
				print json_encode(array('status'=>false));
			}
		}
		
		if ($this->_p('accomplishedtask')){
			// $accomplished = $this->contentHelper->accomplishedTask();
			$accomplished = $this->contentHelper->getAccomplishedTask();
			
			// pr($accomplished);
			if ($accomplished){
				
				// $start = $this->_p('start');
				// $end = $this->_p('end');
				
				// for($i = $start; $i <= $end; $i++){
					// if (array_key_exists($i, $accomplished)){
						// $getData[] = $accomplished[$i];
					// }
					
				// }
				
				
				print json_encode(array('status'=>true, 'rec'=>$accomplished['rec'], 'total'=>$accomplished['total']));
			}else{
				print json_encode(array('status'=>false));
			}
		}
		
		
		if ($this->_p('pursuitupdate')){
			$getUpdate = $this->contentHelper->getPursuitUpdate();
			if ($getUpdate){
				// pr($getUpdate);
				print json_encode(array('status'=>true, 'rec'=>$getUpdate['rec'], 'total'=>$getUpdate['total']));
			}else{
				print json_encode(array('status'=>false));
			}
		}
		
		if ($this->_p('nextlistpursuitupdate')){
			$getUpdate = $this->contentHelper->getPursuitUpdate();
			if ($getUpdate){
				// pr($getUpdate);
				print json_encode(array('status'=>true, 'rec'=>$getUpdate['rec'], 'total'=>$getUpdate['total']));
			}else{
				print json_encode(array('status'=>false));
			}
		}
		
		if ($this->_p('prevlistpursuitupdate')){
			$getUpdate = $this->contentHelper->getPursuitUpdate();
			if ($getUpdate){
				// pr($getUpdate);
				print json_encode(array('status'=>true, 'rec'=>$getUpdate['rec'], 'total'=>$getUpdate['total']));
			}else{
				print json_encode(array('status'=>false));
			}
		}
		
		if ($this->_p('mytrade')){
			$myTradeReq = $this->contentHelper->getMyTradeReq();
			if ($myTradeReq){
				// pr($getPursuitPlayer);
				print json_encode(array('status'=>true, 'rec'=>$myTradeReq['rec'], 'total'=>$myTradeReq['total']));
			}else{
				print json_encode(array('status'=>false));
			}
		}
		
		if ($this->_p('pursuitlistplayer')){
			
			$getPlayer = $this->contentHelper->getPursuitPlayer();
			if($getPlayer){
				// pr($getPlayer);
				$index = 0;
				foreach ($getPlayer['rec'] as $value){
					$data[$index]['id'] = $value['id'];
					$data[$index]['name'] = $value['name'];
					if (($value['image_profile']) and ($value['photo_moderation'] == 1)){
						$data[$index]['image_profile'] = $value['image_profile'];
					}else{
						$data[$index]['image_profile'] = "";
					}
					
					// $data[$index]['photo_moderation'] = $value['photo_moderation'];
					$index++;
				}
				// pr($data);
				if ($data){
					// pr($getPursuitPlayer);
					print json_encode(array('status'=>true, 'rec'=>$data, 'total'=>$getPlayer['total']));
				}else{
					print json_encode(array('status'=>false));
				}
			}else{
				print json_encode(array('status'=>false));
			}
		}
		
		if ($this->_p('nextlistplayer')){
			
			$getPlayer = $this->contentHelper->getPursuitPlayer();
			if($getPlayer){
				// pr($getPlayer);
				$index = 0;
				foreach ($getPlayer['rec'] as $value){
					$data[$index]['id'] = $value['id'];
					$data[$index]['name'] = $value['name'];
					if (($value['image_profile']) and ($value['photo_moderation'] == 1)){
						$data[$index]['image_profile'] = $value['image_profile'];
					}else{
						$data[$index]['image_profile'] = "";
					}
					
					$index++;
				}
				
				if ($data){
					// pr($getPursuitPlayer);
					print json_encode(array('status'=>true, 'rec'=>$data, 'total'=>$getPlayer['total']));
				}else{
					print json_encode(array('status'=>false));
				}
			}else{
				print json_encode(array('status'=>false));
			}
		}
		
		if ($this->_p('prevlistplayer')){
			
			$getPlayer = $this->contentHelper->getPursuitPlayer();
			if($getPlayer){
				// pr($getPlayer);
				$index = 0;
				foreach ($getPlayer['rec'] as $value){
					$data[$index]['id'] = $value['id'];
					$data[$index]['name'] = $value['name'];
					if (($value['image_profile']) and ($value['photo_moderation'] == 1)){
						$data[$index]['image_profile'] = $value['image_profile'];
					}else{
						$data[$index]['image_profile'] = "";
					}
					
					$index++;
				}
				
				if ($data){
					// pr($getPursuitPlayer);
					print json_encode(array('status'=>true, 'rec'=>$data, 'total'=>$getPlayer['total']));
				}else{
					print json_encode(array('status'=>false));
				}
			}else{
				print json_encode(array('status'=>false));
			}
		}
		
		if ($this->_p('getGiftBirthday')){
			
			
			$getGift = $this->userHelper->getBirthdayGift();
			// pr($getGift);
			if ($getGift){
				print json_encode(array('status'=>true,'rec'=>$getGift));
			}else{
				print json_encode(array('status'=>false));
			}
			
		}
		
		if ($this->_p('birthdayGift')){
			
		
			$getClaim = $this->userHelper->getMyClaimBirthday();
			if ($getClaim){
				print json_encode(array('status'=>true));
			}else{
				print json_encode(array('status'=>false));
			}
			
		}
		
		exit;
	}
	
	function trade()
	{
		if ($this->_p('tradealetter')){
			$makeTradeCode = $this->contentHelper->makeTradeCode();
			
			$getMyLetterDetail = $this->contentHelper->getMyLetterDetail();
			// pr($getMyLetterDetail);
			$this->log('tradefloor', 'Trade A Letter');
			
			if ($makeTradeCode){
			
				foreach ($makeTradeCode['mycode'] as $key => $value){
					$mycode[] = $value;
					$mycodeID[] = $key;
					$makeTradeCode['totalletter'][$key] = $getMyLetterDetail[$key]['total'];
				}
				
				
				// pr($getMyLetterDetail);
				// pr($makeTradeCode['mycode']);
				
				// exit;
				foreach ($getMyLetterDetail as $key => $value){
					$mycode[] = $value;
					
					if (in_array($key, $mycodeID)){
						$makeTradeCode['totalletter'][$key] = $getMyLetterDetail[$key]['total'];
					}else{
						$makeTradeCode['totalletter'][$key] = 0;
					}
				}
				
				// pr($makeTradeCode);
				
				
				$typeDouble = array('9','11','12');
				foreach ($makeTradeCode['needcode'] as $key => $value){
					$needcode[] = $value;
					
				}
				
				$codeID = $makeTradeCode['codeID'];
				$mycodeID = $makeTradeCode['mycodeID'];
				
				// pr($makeTradeCode);
				print json_encode(array('status'=>true, 'rec'=>$makeTradeCode));
				
			}else{
				print json_encode(array('status'=>false));
			}
		}
		
		exit;
	}
	
	function getmessageinbox(){
		$messages = $this->messageHelper->getMessage(true);
		$inboxs = $this->messageHelper->getInbox(true);
		
		$dataCodeInt = 0;
		$dataInboxInt = 0;
		if($messages) $dataCodeInt = count($messages);
		if($inboxs) $dataInboxInt= count($inboxs);
		
		if($dataCodeInt||$dataInboxInt) 	print json_encode(array('status'=>true,'data'=>$dataCodeInt+$dataInboxInt,'inbox'=>$dataInboxInt,'notification'=>$dataCodeInt));
		else  print json_encode(array('status'=>false));
		exit;
	}
	
	function readmessage(){
		
		$receipt = intval($this->_p('from'));
		$useraccount = $this->userHelper->getUserAccount($receipt,false);
		// pr($useraccount);
		$messages = $this->messageHelper->seeMessageByid(true);
		
		if($messages) 	print json_encode(array('status'=>true,'rec'=>$useraccount));
		else  print json_encode(array('status'=>false));
		exit;
	}
	
	function setletter()
	{
		if ($this->_p('setletter')){
			$setmyletter = $this->contentHelper->setMyLetter();
			
			if ($setmyletter){
				// $getMyLetterDetail = $this->contentHelper->getMyLetterDetail();
				$getMyLetterDetail = $this->contentHelper->getMyLetterAlreadySet();
				
				//check user set letter
				//give param to showing popup per phrase
				// phrase indicator of popup id to show up
				foreach ($getMyLetterDetail as $key => $value){
					// check phrase
					if ($value['total'] >0){
						$letter[] = $key;
					}
					
				}
				
				$dont = array(1,2,3,4);
				$dontbe = array(1,2,3,4,5,6);
				$dontbea = array(1,2,3,4,5,6,7);
				$dontbeamaybe = array(1,2,3,4,5,6,7,8,9,10,11,12);
				
				$getphrase = "";
				
				$dontbeamaybeFlag = array_intersect($letter, $dontbeamaybe);
				$dontbeaFlag = array_intersect($letter, $dontbea);
				$dontbeFlag = array_intersect($letter, $dontbe);
				$dontFlag = array_intersect($letter, $dont);
				
				if (!isset($_SESSION['letter'])) @$_SESSION['letter']=array();
				
				$haspopupphrase = @$_SESSION['letter'];
				
				if (count($dontFlag)==4){
					$getphrase = 'Dont';
					if(is_array($haspopupphrase)){
						if(!in_array('dont',$haspopupphrase)){							
							array_push($_SESSION['letter'], 'dont');	
							$this->log('redeemcode', $this->user->name.' complete phrase DONT');
						}else $getphrase="";
					}
				}
				if (count($dontbeFlag)==6){
					$getphrase = 'DontBe';
					if(is_array($haspopupphrase)){
						if(!in_array('dontbe',$haspopupphrase)){							
							array_push($_SESSION['letter'], 'dontbe');
							$this->log('redeemcode', $this->user->name.' complete phrase DONTBE');
						}else $getphrase="";
					}
				}
				if (count($dontbeaFlag)==7){
					$getphrase = 'DontBeA';
					if(is_array($haspopupphrase)){
						if(!in_array('dontbea',$haspopupphrase)){							
							array_push($_SESSION['letter'],'dontbea');
							$this->log('redeemcode', $this->user->name.' complete phrase DONTBEA');
						}else $getphrase="";
					}
				}
				if (count($dontbeamaybeFlag)==12){
					$getphrase = 'DontBeAMaybe';
					if(is_array($haspopupphrase)){
						if(!in_array('dontbeamaybe',$haspopupphrase)){							
							array_push($_SESSION['letter'],'dontbeamaybe');
							$this->log('redeemcode', $this->user->name.' complete word DONTBEAMAYBE');
						}else $getphrase="";
					}
				}
				// pr($_SESSION['letter']);
				// pr($haspopupphrase);
				// pr($getphrase);
				if ($getphrase){
					$phrase = 'redeemPrize'.$getphrase;
				}else{
					$phrase = "";
				}
				$this->log('redeemcode', $this->user->name.' Set letter to redeem');
				
				print json_encode(array('status'=>true, 'rec'=>$getMyLetterDetail, 'phrase'=>$phrase ));
			}else{
				print json_encode(array('status'=>false));
			}
		}
		
		exit;
	}
	
	function jointhepursuit()
	{
		if ($this->_p('join')){
			
			$this->log('join pursuit', $this->user->name.' Join the pursuit');
			
			print json_encode(array('status'=>true));
		}
		
		if ($this->_p('hasjoin')){
			
			$isuserjoin = $this->contentHelper->isuserjoinpursuit();
			
			if ($isuserjoin){
				
				print json_encode(array('status'=>true));
			}else{
				print json_encode(array('status'=>false));
			}
		}
		
		exit;
	}
	
	function seeMessage(){
		$this->messageHelper->seeMessage();
		exit;
	}
	function uploadtaskphotoevent(){
	
		global $CONFIG;
		
			// $path = $CONFIG['LOCAL_PUBLIC_ASSET']."thisorthat/";
			$path = $CONFIG['LOCAL_PUBLIC_ASSET']."taskListManagement/";
			$data['result'] = false;
			$data['filename'] = false;
			if (isset($_FILES['image'])&&$_FILES['image']['name']!=NULL) {
				if (isset($_FILES['image'])&&$_FILES['image']['size'] <= 20000000) {
					$res = $this->uploadHelper->uploadThisImage($_FILES['image'],$path,220,true);
					if ($res['arrImage']!=NULL) {
						sleep(1);
						// $result = $this->eventHelper->postData($res['arrImage']); 
						
						// $result = $this->eventHelper->postDataTask($res['arrImage']); 
						$savetask = $this->eventHelper->saveTaskUploadPhoto($res['arrImage']); 
						
						// $savetask = $this->eventHelper->checkcurrentevent(21); 
						$this->contentHelper->uncompletetask($savetask['idcontent']);
						
						// if($result) {
							// $this->log('update profile photo');
							$data['result'] = true;
							$data['filename'] = $res['arrImage'];
						// } 
					}
				}
			}
		
		// sendRedirect("{$CONFIG['BASE_DOMAIN']}account");
		print json_encode($data);
		exit;
	}
	
	
	function saveuploadphoto(){
	
		global $CONFIG;
			$path = $CONFIG['LOCAL_PUBLIC_ASSET']."thisorthat/";
			$data['result'] = false;
			$data['filename'] = false;
			if (isset($_FILES['image'])&&$_FILES['image']['name']!=NULL) {
				if (isset($_FILES['image'])&&$_FILES['image']['size'] <= 20000000) {
					$res = $this->uploadHelper->uploadThisImage($_FILES['image'],$path,220,true);
					if ($res['arrImage']!=NULL) {
												
							$data['result'] = true;
							$data['filename'] = $res['arrImage'];
						
					}
				}
			}
		
		// sendRedirect("{$CONFIG['BASE_DOMAIN']}account");
		print json_encode($data);
		exit;
	}
	
	
	function sendreply(){
		
	}
	
	
	function getphrasedefault(){
				return false;
				$getMyLetterDetail = $this->contentHelper->getMyLetterAlreadySet();
				if(!$getMyLetterDetail) return false;
				foreach ($getMyLetterDetail as $key => $value){
					// check phrase
					if ($value['total'] >0){
						$letter[] = $key;
					}
					
				}
				
				$dont = array(1,2,3,4);
				$dontbe = array(1,2,3,4,5,6);
				$dontbea = array(1,2,3,4,5,6,7);
				$dontbeamaybe = array(1,2,3,4,5,6,7,8,9,10,11,12);
				
				$getphrase = "";
				
				$dontFlag = array_intersect($letter, $dont);
				$dontbeFlag = array_intersect($letter, $dontbe);
				$dontbeaFlag = array_intersect($letter, $dontbea);
				$dontbeamaybeFlag = array_intersect($letter, $dontbeamaybe);
				
				$sessionletter = @$_SESSION['letter'];
				
				if (count($dontFlag)==4){
					if($sessionletter!='dont') {
						$getphrase = 'Dont';
						$_SESSION['letter'] = 'dont';
					}else $getphrase = "";
				}
				if (count($dontbeFlag)==6){
					if($sessionletter!='dontbe'){
						$getphrase = 'DontBe';
						$_SESSION['letter'] = 'dontbe';
					}else $getphrase = "";
				}
				if (count($dontbeaFlag)==7){
					if($sessionletter!='dontbea')
					{	
						$getphrase = 'DontBeA';
						$_SESSION['letter'] = 'dontbea';
					 }else $getphrase = "";
				}
				if (count($dontbeamaybeFlag)==12){
					if($sessionletter!='dontbeamaybe') {
						$getphrase = 'DontBeAMaybe';
						$_SESSION['letter'] = 'dontbeamaybe';
					 }else $getphrase = "";
				}
				
				return false;
	}
	
	function composemessage()
	{
		if ($this->_p('send')){
			
			$nickname = strip_tags($this->_p('nick'));
			$receipt = strip_tags($this->_p('receipt'));
			$mess = strip_tags($this->_p('mess'));
			$subject = strip_tags($this->_p('subject'));
			// pr($receipt);
			$getUserid = $this->userHelper->getUserAccount($receipt,false);
			// pr($getUserid);
			if ($getUserid){
				$sendTradeMsg = $this->contentHelper->composeMessage($getUserid['id'], $subject, $mess);
				if ($sendTradeMsg){
				
					unset($_SESSION['statusinbox']);
				
					$this->log('surf', $this->user->name.' Send message');
					print json_encode(array('status'=>true));
				}else{
					print json_encode(array('status'=>false));
				}
			}
			
			
		}
		
		
		
		exit;
	}
	
	
	function replayMessage()
	{
		if ($this->_p('reply')){
			
			$receipt = intval($this->_p('id'));
			$mess = strip_tags($this->_p('messg'));
			$subject = strip_tags($this->_p('subject'));
			if($receipt==0) {
				print json_encode(array('status'=>false));
				exit;
			}
			$getUserid = $this->userHelper->getUserAccount($receipt,false);
			if ($getUserid){
				$sendTradeMsg = $this->contentHelper->composeMessage($getUserid['id'], $subject, $mess);
				if ($sendTradeMsg){
					$this->log('surf', $this->user->name.' Send message');
					print json_encode(array('status'=>true));
				}else{
					print json_encode(array('status'=>false));
				}
			}
			
			
		}
		
		exit;
	}
}
?>