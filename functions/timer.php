<?php function apollo_return_date($nowTime, $targetTime){
		$apollo_options = get_option("apollo_display_options");
		$rollover = 0;
		$s = '';
		$sigNumHit = false;

		$nowYear = date("Y", $nowTime);
		$nowMonth = date("m", $nowTime);
		$nowDay = date("d", $nowTime);
		$nowHour = date("H", $nowTime);
		$nowMinute = date("i", $nowTime);
		$nowSecond = date("s", $nowTime);

		$targetYear = date("Y", $targetTime);
		$targetMonth = date("m", $targetTime);
		$targetDay = date("d", $targetTime);
		$targetHour = date("H", $targetTime);
		$targetMinute = date("i", $targetTime);
		$targetSecond = date("s", $targetTime);

		$resultantYear = $targetYear - $nowYear;
		$resultantMonth = $targetMonth - $nowMonth;
		$resultantDay = $targetDay - $nowDay;
		$resultantHour = $targetHour - $nowHour;
		$resultantMinute = $targetMinute - $nowMinute;
		$resultantSecond = $targetSecond - $nowSecond;

		if($resultantSecond < 0){
			$resultantMinute--;
			$resultantSecond = 60 + $resultantSecond;
		}

		if($resultantMinute < 0){
			$resultantHour--;
			$resultantMinute = 60 + $resultantMinute;
		}

		if($resultantHour < 0){

			$resultantDay--;
			$resultantHour = 24 + $resultantHour;
		}

		if($resultantDay < 0){
			$resultantMonth--;
			$resultantDay = $resultantDay + cal_days_in_month(CAL_GREGORIAN, $nowMonth, $nowYear); //Holy crap! When did they introduce this function and why haven't I heard about it??
		}

		if($resultantMonth < 0){
			$resultantYear--;
			$resultantMonth = $resultantMonth + 12;
		}

		//Year
		if(empty($apollo_options["hide_year"])){
			if($sigNumHit || $resultantYear){
				$timer["year"] = $resultantYear;
				$sigNumHit = true;
			}
		}
		else{
			$rollover = $resultantYear*31536000;
		}

		//Month
		if(empty($apollo_options["hide_month"])){
			if($sigNumHit || intval($resultantMonth + ($rollover/2628000)) ){
				$resultantMonth = intval($resultantMonth + ($rollover/2628000));
				$timer["months"] = $resultantMonth;
				$rollover = $rollover - intval($rollover/2628000)*2628000;
				$sigNumHit = true;
			}
		}

		$rollover = $resultantYear*31536000;
		//Day
		if(empty($apollo_options["hide_day"])){
			$resultantDay = $resultantDay + intval($rollover/86400);
			$timer["days"] = $resultantDay;
			$rollover = $rollover - intval($rollover/86400)*86400;
			$sigNumHit = true;
		}
		else{
			$rollover = $rollover + $resultantDay*86400;
		}

		//Hour
		if(empty($apollo_options["hide_hour"])){
			if($sigNumHit || ($resultantHour + intval($rollover/3600)) ){
				$resultantHour = $resultantHour + intval($rollover/3600);
				$timer["hours"] = $resultantHour;
				$rollover = $rollover - intval($rollover/3600)*3600;
				$sigNumHit = true;
			}
		}
		else{
			$rollover = $rollover + $resultantHour*3600;
		}

		//Minute
		if(empty($apollo_options["hide_minute"])){
			if($sigNumHit || ($resultantMinute + intval($rollover/60)) ){
				$resultantMinute = $resultantMinute + intval($rollover/60);
				$timer["minutes"] = $resultantMinute;
				$rollover = $rollover - intval($rollover/60)*60;
				$sigNumHit = true;
			}
		}
		else{
			$rollover = $rollover + $resultantMinute*60;
		}

		//Second
		if(empty($apollo_options["hide_second"])){
			$resultantSecond = $resultantSecond + $rollover;
			$timer["seconds"] = $resultantSecond;
		}
		
		return $timer;
	}