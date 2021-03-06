<?php  

error_reporting(E_ALL);
ini_set('display_errors', 1);

//Login function
function CBSCalendarApiInteract(){
	$encrypted_hash =  md5("emso13ab". "v.eRyzeKretW0r_t");

	$getCBSCalendar = "https://calendar.cbs.dk/events.php/". "emso13ab". "/".$encrypted_hash."."."json";

	$jsonObject = file_get_contents($getCBSCalendar);
	$jsonObjectArray = json_decode($jsonObject, true);

	return $jsonObjectArray;
}

$jsonString = "
    { \"events\": [
	    {\"activityid\":\"BINTO1035U_XB_E14\",\"eventid\":\"BINTO1035U_XB_E14_7522cb9e2c47efa1e3ff6ac24002be36_99537c3adf3e04bc329ed38f0e58ce2e\",\"type\":\"Lecture\",\"title\":\"BINTO1035U.XB_E14\",\"description\":\"Distribuerede Systemer (XB)\",\"start\":[\"2014\",11,\"24\",\"8\",\"00\"],\"end\":[\"2014\",11,\"24\",\"9\",\"40\"],\"location\":\"Ks71\"},
	    {\"activityid\":\"BINTO1035U_XB_A14\",\"eventid\":\"BINTO1035U_XB_E14_7522cb9e2c47efa1e3ff6ac24002be36_99537c3adf3e04bc329ed38f0e58ce2e\",\"type\":\"Lecture\",\"title\":\"BINTO1035U.XB_E14\",\"description\":\"Distribuerede Systemer (XB)\",\"start\":[\"2014\",11,\"24\",\"12\",\"00\"],\"end\":[\"2014\",11,\"24\",\"16\",\"40\"],\"location\":\"Ks71\"},
		{\"activityid\":\"BINTO1035U_XB_E14\",\"eventid\":\"BINTO1035U_XB_E14_5eea61ae6c2d8824d340e3b57c52dc11_a48b708b5cdbd426b599420f90697a1f\",\"type\":\"Exercise\",\"title\":\"BINTO1035U.XB_E14\",\"description\":\"Makro\u00f8konomi (XB)\",\"start\":[\"2014\",11,\"26 \",\"11\",\"00\"],\"end\":[\"2014\",11,\"26\",\"12\",\"40\"],\"location\":\"SP114\"},
		{\"activityid\":\"BINTO1035U_XB_E14\",\"eventid\":\"BINTO1035U_XB_E14_e523a307e38d3b09094746c6d679e683_087a51abc6bd219e13e0351601e0e1aa\",\"type\":\"Lecture\",\"title\":\"BINTO1035U.XB_E14\",\"description\":\"Makro\u00f8konomi (XB)\",\"start\":[\"2014\",11,\"22\",\"8\",\"00\"],\"end\":[\"2014\",11,\"22\",\"9\",\"40\"],\"location\":\"Ks71\"}
	]}
";

?>



<!DOCTYPE html>

<head>
<link type="text/css" rel="stylesheet" href="CBScalendar.css"/>
	<title>CBS Calendar</title>
</head>
<style>
	body{
		background-image:url(sandpaper.png);
	}
</style>
<body>
	<!-- Creates menu wrapper -->
	<div class="wrapper">
		<!-- Creates action buttons by a get method -->
		<form action="CBSCreateEvent.php" method="get">
			<button id="menu" type="submit" value="createEvent" name="Create Event">Create event</button>
		</form>
		<form action="CBSCreateNote.php" method="get">
			<button id="menu" type="submit" value="createNote" name="Create Note">Create note</button>
		</form>
		<form action="CBSDayview.php" method="get">	
			<button id="menu" type="submit" value="dayview" name="Dayview">Dayview</button>
		</form>
		<button id="menu">Weather</button>
		<button id="menu">Quote of the day</button>
		<form action="LoginCBS.php" method="get">
			<button id="menu" type="submit" value="logout" name="Logout">Logout</button>
		</form>

	</div>
	<!-- Creates the calendar body using the html table tags -->
	<table class="table">
		<tr class="dates">
			<?php 
			// dynamic week function
			// set current date
			if(isset($_GET['lastweek'])){
				if(isset($_GET['weekNumber'])){
					$weekNumber = $_GET['weekNumber'];
					$weekNumber--;
				}else{
					$weekNumber=0;
					$weekNumber--;
				}
				
				$weekString = $weekNumber . " week";

				$date = date("m/d/y", strtotime($weekString));

			}elseif(isset($_GET['nextweek'])){
				if(isset($_GET['weekNumber'])){
					$weekNumber = $_GET['weekNumber'];
					$weekNumber++;
				}else{
					$weekNumber = 1;
				}
				
				$weekString = $weekNumber . " week";

				$date = date("m/d/y", strtotime($weekString));
			}else{
				$date = date("m/d/y"); 
			}
			// parse about any English textual datetime description into a Unix timestamp
			$ts = strtotime($date);
			// calculate the number of days since Monday
			$dow = date('w', $ts);
			$offset = $dow - 1;
			if ($offset < 0) {
			    $offset = 6;
			}
			// calculate timestamp for the Monday
			$ts = $ts - $offset*86400;
			// print current week
			$i = 0;

			$weekday_month  = date("F", $ts + $i * 86400) . "\n";
			?>
			<td class="td4"><?= $weekday_month; ?></td>
			<?php 
			$json = json_decode($jsonString, true);


			for ($i = 0; $i < 7; $i++) {
			    $weekday_year   = date("Y", $ts + $i * 86400) . "\n";
			    $weekday_day    = date("l", $ts + $i * 86400) . "\n";
			    $weekday_day_number    = date("j", $ts + $i * 86400) . "\n";
			    $weekday_month_2  = date("m", $ts + $i * 86400) . "\n";
			    $weekday_date   = date("d", $ts + $i * 86400) . "\n";
			    $currentDateValidate = intval($weekday_year).intval($weekday_month_2).intval($weekday_day_number);	   
			?>



				<td class="td4">
					<?= $weekday_day_number . "."; ?>

					<?php 
					//
					$cbsCalendar = CBSCalendarApiInteract();

					foreach ($cbsCalendar['events'] as $eventInfo){
						$topMargin = 68;

						// comparing events year, month, day and time and prints it on the specific day and time
						$eventStartYear   	   = $eventInfo['start'][0];
						$eventStartMonth  	   = $eventInfo['start'][1];
						$eventStartDay         = $eventInfo['start'][2];
						$eventStartTimeHour    = $eventInfo['start'][3];
						$eventStartTimeMinutes = $eventInfo['start'][4];

						$eventEndTimeHour 	   = $eventInfo['end'][3];
						$eventEndTimeMinutes   = $eventInfo['end'][4];

						$eventStartTimeHourInMin = $eventStartTimeHour * 60;
						$eventStartTimeInMin = $eventStartTimeHourInMin + $eventStartTimeMinutes;


						$eventEndTimeHourInMin = $eventEndTimeHour * 60;
						$eventEndTimeInMin = $eventEndTimeHourInMin + $eventEndTimeMinutes;

						$eventDuration = $eventEndTimeInMin - $eventStartTimeInMin;

						$currentDateValidateEvent = intval($eventStartYear).intval($eventStartMonth).intval($eventStartDay);

						$eventHeightBordersPerHour = floor($eventDuration / 60) * 3;
						$eventHeight = $eventDuration + $eventHeightBordersPerHour;

						$eventType 		= $eventInfo['type'];
						$eventLocation  = $eventInfo['location'];
						$eventDes 		= $eventInfo['description'];
						$eventTime 		= $eventStartTimeHour . ":" . $eventStartTimeMinutes . " - " . $eventEndTimeHour . ":" . $eventEndTimeMinutes;
						$topMargin = ((($eventStartTimeHour - 8) * 60) + $eventStartTimeMinutes) + $topMargin + (floor(((($eventStartTimeHour - 8) * 60) + $eventStartTimeMinutes)/60) * 3);

						if($currentDateValidate == $currentDateValidateEvent){
						?> 
						<div class="event" style="top:<?= $topMargin; ?>px;height: <?= $eventHeight; ?>px;">
							<span class="type"><?= $eventType; ?></span>
							<span class="description"><?= $eventDes; ?></span>
							<span class="location"><?= $eventLocation; ?></span>
							<br/>
							<span class="time"><?= $eventTime; ?></span>
						</div>
						<?php
						}
					} 
					?>

					<?php
					foreach ($json['events'] as $eventInfo){
						$topMargin = 68;

						$eventStartYear   	   = $eventInfo['start'][0];
						$eventStartMonth  	   = $eventInfo['start'][1];
						$eventStartDay         = $eventInfo['start'][2];
						$eventStartTimeHour    = $eventInfo['start'][3];
						$eventStartTimeMinutes = $eventInfo['start'][4];

						$eventEndTimeHour 	   = $eventInfo['end'][3];
						$eventEndTimeMinutes   = $eventInfo['end'][4];

						$eventStartTimeHourInMin = $eventStartTimeHour * 60;
						$eventStartTimeInMin = $eventStartTimeHourInMin + $eventStartTimeMinutes;


						$eventEndTimeHourInMin = $eventEndTimeHour * 60;
						$eventEndTimeInMin = $eventEndTimeHourInMin + $eventEndTimeMinutes;

						$eventDuration = $eventEndTimeInMin - $eventStartTimeInMin;

						$currentDateValidateEvent = intval($eventStartYear).intval($eventStartMonth).intval($eventStartDay);

						$eventHeightBordersPerHour = floor($eventDuration / 60) * 3;
						$eventHeight = $eventDuration + $eventHeightBordersPerHour;

						$eventType 		= $eventInfo['type'];
						$eventLocation  = $eventInfo['location'];
						$eventDes 		= $eventInfo['description'];
						$eventTime 		= $eventStartTimeHour . ":" . $eventStartTimeMinutes . " - " . $eventEndTimeHour . ":" . $eventEndTimeMinutes;
						$topMargin = ((($eventStartTimeHour - 8) * 60) + $eventStartTimeMinutes) + $topMargin + (floor(((($eventStartTimeHour - 8) * 60) + $eventStartTimeMinutes)/60) * 3);

						if($currentDateValidate == $currentDateValidateEvent){
						?> 
						<div class="event" style="top:<?= $topMargin; ?>px;height: <?= $eventHeight; ?>px;">
							<span class="type"><?= $eventType; ?></span>
							<span class="description"><?= $eventDes; ?></span>
							<span class="location"><?= $eventLocation; ?></span>
							<br/>
							<span class="time"><?= $eventTime; ?></span>
						</div>
						<?php
						}
					} 
					?>
				</td>
			<?php } ?>
			
		</tr>

		<!-- back and forward buttons for every week -->
		<tr class="weekDays">
			<td class="td4">
				<div id="backWeek"><a href="CBScalendar.php<?php if(isset($weekNumber)){ echo "?weekNumber=" . $weekNumber . "&lastweek=1";}else{ echo "?lastweek=1"; } ?>"><</a></div>
				<div id="forwardWeek"><a href="CBScalendar.php<?php if(isset($weekNumber)){ echo "?weekNumber=" . $weekNumber . "&nextweek=1";}else{ echo "?nextweek=1"; } ?>">><a></div>
			</td>
			<?php 
			for ($i = 0; $i < 7; $i++) {
			    $weekday_day    = date("l", $ts + $i * 86400) . "\n";
			?>
			<td class="td4"><?= $weekday_day; ?></td>
			<?php } ?>
			
		</tr>

		<tr>
			<td class="td3">08:00</td>
			<td class="td1"></td>
			<td class="td1"></td>
			<td class="td1"></td>
			<td class="td1"></td>
			<td class="td1"></td>
			<td class="td2"></td>
			<td class="td2"></td>
		</tr>

		<tr>
			<td class="td3">09:00</td>
			<td class="td1"></td>
			<td class="td1"></td>
			<td class="td1"></td>
			<td class="td1"></td>
			<td class="td1"></td>
			<td class="td2"></td>
			<td class="td2"></td>
		</tr>

		<tr>
			<td class="td3">10:00</td>
			<td class="td1"></td>
			<td class="td1"></td>
			<td class="td1"></td>
			<td class="td1"></td>
			<td class="td1"></td>
			<td class="td2"></td>
			<td class="td2"></td>
		</tr>

		<tr>
			<td class="td3">11:00</td>
			<td class="td1"></td>
			<td class="td1"></td>
			<td class="td1"></td>
			<td class="td1"></td>
			<td class="td1"></td>
			<td class="td2"></td>
			<td class="td2"></td>
		</tr>

		<tr>
			<td class="td3">12:00</td>
			<td class="td1"></td>
			<td class="td1"></td>
			<td class="td1"></td>
			<td class="td1"></td>
			<td class="td1"></td>
			<td class="td2"></td>
			<td class="td2"></td>
		</tr>

		<tr>
			<td class="td3">13:00</td>
			<td class="td1"></td>
			<td class="td1"></td>
			<td class="td1"></td>
			<td class="td1"></td>
			<td class="td1"></td>
			<td class="td2"></td>
			<td class="td2"></td>
		</tr>

		<tr>
			<td class="td3">14:00</td>
			<td class="td1"></td>
			<td class="td1"></td>
			<td class="td1"></td>
			<td class="td1"></td>
			<td class="td1"></td>
			<td class="td2"></td>
			<td class="td2"></td>
		</tr>

		<tr>
			<td class="td3">15:00</td>
			<td class="td1"></td>
			<td class="td1"></td>
			<td class="td1"></td>
			<td class="td1"></td>
			<td class="td1"></td>
			<td class="td2"></td>
			<td class="td2"></td>
		</tr>

		<tr>
			<td class="td3">16:00</td>
			<td class="td1"></td>
			<td class="td1"></td>
			<td class="td1"></td>
			<td class="td1"></td>
			<td class="td1"></td>
			<td class="td2"></td>
			<td class="td2"></td>
		</tr>

		<tr>
			<td class="td3">17:00</td>
			<td class="td1"></td>
			<td class="td1"></td>
			<td class="td1"></td>
			<td class="td1"></td>
			<td class="td1"></td>
			<td class="td2"></td>
			<td class="td2"></td>
		</tr>

		<tr>
			<td class="td3">18:00</td>
			<td class="td1"></td>
			<td class="td1"></td>
			<td class="td1"></td>
			<td class="td1"></td>
			<td class="td1"></td>
			<td class="td2"></td>
			<td class="td2"></td>
		</tr>

		<tr>
			<td class="td3">19:00</td>
			<td class="td1"></td>
			<td class="td1"></td>
			<td class="td1"></td>
			<td class="td1"></td>
			<td class="td1"></td>
			<td class="td2"></td>
			<td class="td2"></td>
		</tr>

		<tr>
			<td class="td3">20:00</td>
			<td class="td1"></td>
			<td class="td1"></td>
			<td class="td1"></td>
			<td class="td1"></td>
			<td class="td1"></td>
			<td class="td2"></td>
			<td class="td2"></td>
		</tr>

		<tr>
			<td class="td3">21:00</td>
			<td class="td1"></td>
			<td class="td1"></td>
			<td class="td1"></td>
			<td class="td1"></td>
			<td class="td1"></td>
			<td class="td2"></td>
			<td class="td2"></td>
		</tr>

		<tr>
			<td class="td3">22:00</td>
			<td class="td1"></td>
			<td class="td1"></td>
			<td class="td1"></td>
			<td class="td1"></td>
			<td class="td1"></td>
			<td class="td2"></td>
			<td class="td2"></td>
		</tr>

	</table>


</body>

