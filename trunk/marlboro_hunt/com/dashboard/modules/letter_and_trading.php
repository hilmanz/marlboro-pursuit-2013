<?php
class letter_and_trading extends App{
	
	
	function beforeFilter(){
		
		$this->letterHelper = $this->useHelper("letterHelper");
		
		global $LOCALE,$CONFIG;
		$this->assign('basedomain', $CONFIG['DASHBOARD_DOMAIN']);
		$this->assign('assets_domain', $CONFIG['ASSETS_DOMAIN_DASHBOARD']);
		
		$this->assign('locale', $LOCALE[1]);
		$this->assign('startdate', $this->_g('startdate'));
		$this->assign('enddate', $this->_g('enddate'));
	
		
	}
	function main(){
		
		$numberRedeemLetter = $this->letterHelper->numberRedeemLetter();
		$letterPercentage = $this->letterHelper->letterPercentage();
		$letterPercentageRedeem = $this->letterHelper->letterPercentageRedeem();
		$numSingleLetter = $this->letterHelper->numSingleLetter();
		$totalTrading = $this->letterHelper->totalTrading();
		$mostTradeLetter = $this->letterHelper->mostTradeLetter();
		$succesTrade = $this->letterHelper->succesTrade();
		$numEachLetterset = $this->letterHelper->numEachLetterset();
		$letterBeingTrade = $this->letterHelper->letterBeingTrade();
		$letterperuser = $this->letterHelper->letterperuser();
		$numRedeemLetter = $this->letterHelper->numRedeemLetter();
		$numTotalTrade = $this->letterHelper->numTotalTrade();
		$numSuccessTrade = $this->letterHelper->numSuccessTrade();
		// pr($numTotalTrade);
		$this->assign("numberRedeemLetter",$numberRedeemLetter);
		$this->assign("letterPercentage",$letterPercentage);
		$this->assign("letterPercentageRedeem",$letterPercentageRedeem);
		$this->assign("numSingleLetter",$numSingleLetter);
		$this->assign("totalTrading",$totalTrading);
		$this->assign("mostTradeLetter",$mostTradeLetter);
		$this->assign("succesTrade",$succesTrade);
		$this->assign("numEachLetterset",$numEachLetterset);
		$this->assign("letterBeingTrade",$letterBeingTrade);
		$this->assign("letterperuser",$letterperuser);
		$this->assign("numRedeemLetter",$numRedeemLetter);
		$this->assign("numTotalTrade",$numTotalTrade);
		$this->assign("numSuccessTrade",$numSuccessTrade);
		
		if(strip_tags($this->_g('page'))=='home') $this->log('surf','home');
		return $this->View->toString(TEMPLATE_DOMAIN_DASHBOARD .'apps/letter_and_trading.html');
		
	}
	
	function downloadreport(){
		
			$filename = "letter_per_user_".date('Ymd_gia').".xls";
			header("Content-Type:   application/vnd.ms-excel; charset=utf-8");
			header("Content-Disposition: attachment; filename=$filename");  //File name extension was wrong
			header("Expires: 0");
			header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
			header("Cache-Control: private",false);
			// echo "Some Text"; //no ending ; here
			$letterperuser = $this->letterHelper->letterperuser(100,true);
			 
			// pr($resReport);
			echo "<table border='1'>";
			echo "<tr><th>NAME</th><th>LAST NAME</th><th>EMAIL</th>
			<th>D</th><th>O</th><th>N</th><th>T</th>
			<th>B</th><th>E</th>
			<th>A</th>
			<th>M</th><th>A</th><th>Y</th><th>B</th><th>E</th></tr>";
			foreach ($letterperuser as $key => $val){	
				echo "<tr>";
				echo "<td>{$val['name']}</td>
					 <td>{$val['last_name']}</td>
					 <td>{$val['email']}</td> 	
					 <td>{$val['D']}</td> 	
					 <td>{$val['O']}</td> 	
					 <td>{$val['N']}</td> 	
					 <td>{$val['T']}</td> 	
					 <td>{$val['B1']}</td> 
					 <td>{$val['E1']}</td>
					 <td>{$val['A1']}</td>
					 <td>{$val['M']}</td>
					 <td>{$val['A2']}</td>
					 <td>{$val['Y']}</td>
					 <td>{$val['B2']}</td>
					 <td>{$val['E2']}</td>";
				echo "</tr>";
			}
			echo "</table>";
			exit;
	}
	
}
?>