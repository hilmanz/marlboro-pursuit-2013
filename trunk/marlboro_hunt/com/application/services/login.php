<?php
class login extends ServiceAPI{
		
	function beforeFilter(){
		$this->loginHelper = $this->useHelper('loginHelper');
		
	}
	
	function main(){
	
		global $CONFIG,$logger;
		
		$basedomain = $CONFIG['BASE_DOMAIN'];
		$this->assign('basedomain',$basedomain);
				
			if($this->_request('username')&&$this->_request('password')){
				// $token = $this->_request('token');
				// $mytoken = sha1(date('YmdHi').'mobileaccess');
				// if($token==$mytoken) $this->Request->setParamPost('login',1);
				$this->Request->setParamPost('login',1);
				$res = $this->loginHelper->loginSession();
				if($res){
					
					$user =  $this->getUserOnline();
					if($user->image_profile){
						if(!is_file("{$CONFIG['LOCAL_PUBLIC_ASSET']}user/photo/{$user->image_profile}"))  $user->image_full_path = $CONFIG['BASE_DOMAIN_PATH']."public_assets/user/photo/".$user->image_profile;
						else $user->image_full_path = $CONFIG['BASE_DOMAIN_PATH']."public_assets/user/photo/default.jpg";
					}else $user->image_full_path = $CONFIG['BASE_DOMAIN_PATH']."public_assets/user/photo/default.jpg";
					$data['status'] = 200;
					$data['user_id'] = $user->id;
					$data['session_id'] =  $user->id.$user->login_count;
					$data['name'] =  $user->name." ".$user->last_name;
					$data['avatar'] = $user->image_full_path ;
				
					return $data;
					
				}else {
					$data['status'] = 404;
					$data['mesage'] = 'wrong username or password';
					
					return $data;
				}
			}else{
				print  $this->error_400();exit;
			}
			
			
	}

	
}
?>