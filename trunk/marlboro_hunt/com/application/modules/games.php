<?php
class games extends App{
	function beforeFilter(){
	
		$this->contentHelper = $this->useHelper('contentHelper');
		$this->activityHelper = $this->useHelper('activityHelper');
		$this->gamesHelper = $this->useHelper('gamesHelper');
		$this->userHelper = $this->useHelper('userHelper');
		global $LOCALE,$CONFIG;
		$this->assign('basedomain', $CONFIG['BASE_DOMAIN']);
		$this->assign('assets_domain', $CONFIG['ASSETS_DOMAIN_WEB']);
		
		$this->assign('locale', $LOCALE[1]);
		
	}
	
	function main()
	{
	
		// $this->View->assign('games_content',$this->setWidgets('games_content'));
		return $this->View->toString(TEMPLATE_DOMAIN_WEB .'apps/games_pages.html');
	}
	
	function wallbreaker(){
		
		$this->log('surf', 'games wallbreaker');
		
		$maylead = $this->gamesHelper->leaderboard_wallbreaker();
		$this->assign("maylead",$maylead);
	
		$this->View->assign('popup_leaderboard_wallbraker',$this->setWidgets('popup_leaderboard_wallbraker'));
		return $this->View->toString(TEMPLATE_DOMAIN_WEB .'widgets/wallbreaker.html');
	}
	
	function maybeninja(){
		
		$this->log('surf', 'games maybeninja');
		
		$maylead = $this->gamesHelper->leaderboard_maybeninja();
		$this->assign("maylead",$maylead);
		
		$this->View->assign('popup_leaderboard_maybeninja',$this->setWidgets('popup_leaderboard_maybeninja'));
		return $this->View->toString(TEMPLATE_DOMAIN_WEB .'widgets/crossout.html');
	}
	
	function crossout(){
		
		$this->log('surf', 'games crossout');
		$maylead = $this->gamesHelper->leaderboard_maybeninja();
		$this->assign("maylead",$maylead);
		
		$this->View->assign('popup_leaderboard_maybeninja',$this->setWidgets('popup_leaderboard_maybeninja'));
		return $this->View->toString(TEMPLATE_DOMAIN_WEB .'widgets/crossout.html');
	}
	
	function wordhunt(){
		
		$this->log('surf', 'games wordhunt');
		$maylead = $this->gamesHelper->leaderboardgames(4);
		$this->assign("maylead",$maylead);
		
		$this->View->assign('popup_leaderboard_wordhunt',$this->setWidgets('popup_leaderboard_wordhunt'));
		return $this->View->toString(TEMPLATE_DOMAIN_WEB .'widgets/wordhunt.html');
	}
	
	 
	
	function doubtcrasher(){
		
		$this->log('surf', 'games doubtcrasher');
		$maylead = $this->gamesHelper->leaderboardgames(5);
		$this->assign("maylead",$maylead);
		
		$this->View->assign('popup_leaderboard_doubtcrush',$this->setWidgets('popup_leaderboard_doubtcrush'));
		return $this->View->toString(TEMPLATE_DOMAIN_WEB .'widgets/doubtcrush.html');
	}
	
	function moveforward(){
		
		$this->log('surf', 'games moveforward');
		$maylead = $this->gamesHelper->leaderboardgames(6);
		$this->assign("maylead",$maylead);
		
		$this->View->assign('popup_leaderboard_moveforward',$this->setWidgets('popup_leaderboard_moveforward'));
		return $this->View->toString(TEMPLATE_DOMAIN_WEB .'widgets/moveforward.html');
	}
	
	function wordtap(){
		
		$this->log('surf', 'games wordtap');
		$maylead = $this->gamesHelper->leaderboardgames(7);
		$this->assign("maylead",$maylead);
		
		$this->View->assign('popup_leaderboard_wordtap',$this->setWidgets('popup_leaderboard_wordtap'));
		return $this->View->toString(TEMPLATE_DOMAIN_WEB .'widgets/wordtap.html');
	}
	
	function wordmaster(){
		
		$this->log('surf', 'games wordmaster');
		$maylead = $this->gamesHelper->leaderboardgames(7);
		$this->assign("maylead",$maylead);
		
		$this->View->assign('popup_leaderboard_wordtap',$this->setWidgets('popup_leaderboard_wordtap'));
		return $this->View->toString(TEMPLATE_DOMAIN_WEB .'widgets/wordtap.html');
	}
	
	function play(){
		
		$gamesplay = strip_tags($this->_request('gamesplay'));
		
		if($gamesplay=='wallbreaker'){
			$wallead = $this->gamesHelper->leaderboard_wallbreaker();
			$this->assign("wallead",$wallead);
	
			$this->View->assign('popup_leaderboard_wallbraker',$this->setWidgets('popup_leaderboard_wallbraker'));
			$this->View->assign('popup_leaderboard',$this->setWidgets('popup_leaderboard'));
			return $this->View->toString(TEMPLATE_DOMAIN_WEB .'widgets/game-play-wallbreaker.html');
		}
		
		if($gamesplay=='maybeninja'){
			$maylead = $this->gamesHelper->leaderboard_maybeninja();
			$this->assign("maylead",$maylead);
		
			$this->View->assign('popup_leaderboard_maybeninja',$this->setWidgets('popup_leaderboard_maybeninja'));
			$this->View->assign('popup_leaderboard',$this->setWidgets('popup_leaderboard'));
			return $this->View->toString(TEMPLATE_DOMAIN_WEB .'widgets/game-play-crossout.html');
		}
		if($gamesplay=='crossout'){
			$maylead = $this->gamesHelper->leaderboard_maybeninja();
			$this->assign("maylead",$maylead);
		
			$this->View->assign('popup_leaderboard_maybeninja',$this->setWidgets('popup_leaderboard_maybeninja'));
			$this->View->assign('popup_leaderboard',$this->setWidgets('popup_leaderboard'));
			return $this->View->toString(TEMPLATE_DOMAIN_WEB .'widgets/game-play-crossout.html');
		}
		
		if($gamesplay=='wordhunt'){
			$maylead = $this->gamesHelper->leaderboardgames(4);
			$this->assign("maylead",$maylead);
		
			$this->View->assign('popup_leaderboard_wordhunt',$this->setWidgets('popup_leaderboard_wordhunt'));
			$this->View->assign('popup_leaderboard',$this->setWidgets('popup_leaderboard'));
			return $this->View->toString(TEMPLATE_DOMAIN_WEB .'widgets/game-play-wordhunt.html');
		}
		
		if($gamesplay=='doubtcrasher'){
			$maylead = $this->gamesHelper->leaderboardgames(5);
			$this->assign("maylead",$maylead);
		
			$this->View->assign('popup_leaderboard_doubtcrush',$this->setWidgets('popup_leaderboard_doubtcrush'));
			$this->View->assign('popup_leaderboard',$this->setWidgets('popup_leaderboard'));
			return $this->View->toString(TEMPLATE_DOMAIN_WEB .'widgets/game-play-doubtcrush.html');
		}
		
		if($gamesplay=='moveforward'){
			$maylead = $this->gamesHelper->leaderboardgames(6);
			$this->assign("maylead",$maylead);
		
			$this->View->assign('popup_leaderboard_moveforward',$this->setWidgets('popup_leaderboard_moveforward'));
			$this->View->assign('popup_leaderboard',$this->setWidgets('popup_leaderboard'));
			return $this->View->toString(TEMPLATE_DOMAIN_WEB .'widgets/game-play-moveforward.html');
		}
		
		if($gamesplay=='wordtap'){
			$maylead = $this->gamesHelper->leaderboardgames(7);
			$this->assign("maylead",$maylead);
		
			$this->View->assign('popup_leaderboard_wordtap',$this->setWidgets('popup_leaderboard_wordtap'));
			$this->View->assign('popup_leaderboard',$this->setWidgets('popup_leaderboard'));
			return $this->View->toString(TEMPLATE_DOMAIN_WEB .'widgets/game-play-wordtap.html');
		}
		
		if($gamesplay=='wordmaster'){
			$maylead = $this->gamesHelper->leaderboardgames(7);
			$this->assign("maylead",$maylead);
		
			$this->View->assign('popup_leaderboard_wordtap',$this->setWidgets('popup_leaderboard_wordtap'));
			$this->View->assign('popup_leaderboard',$this->setWidgets('popup_leaderboard'));
			return $this->View->toString(TEMPLATE_DOMAIN_WEB .'widgets/game-play-wordtap.html');
		}
		
	}
	
	function hiddencode()
	{
		
		if ($this->_p('hiddenCode')){
			if ($this->_p('param')){
			
				$validateCode = $this->gamesHelper->ValidateHiddenCode();
				if ($validateCode){
					print json_encode(array('status'=>true, 'rec'=>$validateCode));
				}else{
					print json_encode(array('status'=>false));
				}
			}
			
		}
		
		exit;
	}
		
	
}
?>