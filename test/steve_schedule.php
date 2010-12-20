<!doctype html public "-//w3c//dtd html 4.0 transitional//en">
<html>
  <head>
    <title>Steve's Schedule</title>
    <link rel="stylesheet" type="text/css" href="main.css">
    <style type="text/css">
      table {
        border-style: outset;
      }
      th, td {
        border-style: inset;
      }
      .time {
        height: 50px;
      }
    </style>
  </head>
  <body>
    <h1>Steve's Schedule for Spring 2002</h1>
    <table border style="width: 100%;">
      <tr>
        <th></th>
        <th> Monday </th>
        <th> Tuesday </th>
        <th> Wednesday </th>
        <th> Thursday </th>
        <th> Friday </th>
        <th> Saturday </th>
        <th> Sunday </th>
      </tr>
      <tr></tr>
      <tr></tr>
<?php

// open file
//Load arrays

$textArray[0] = 'driving';			//text
$timeB[0] = 12;					//beginning time (24 hour time)
$timeS[0] = 12.5;					//ending time
$day1[0] = 0;					//first day of the week
$day2[0] = 2;					//second day, -1 if none
$day3[0] = 4;					//third day, -1 if none
$type[0] = '';					//type for class

$textArray[1] = 'multimedia';			//text
$timeB[1] = 12.5;					//beginning time
$timeS[1] = 13.5;					//ending time
$day1[1] = 0;					//first day of the week
$day2[1] = 2;					//second day, -1 if none
$day3[1] = 4;					//third day, -1 if none
$type[1] = 'lec';					//type for class

$textArray[2] = 'driving';			//text
$timeB[2] = 12.5;					//beginning time
$timeS[2] = 13;					//ending time
$day1[2] = 1;					//first day of the week
$day2[2] = 3;					//second day, -1 if none
$day3[2] = -1;					//third day, -1 if none
$type[2] = '';					//type for class

$textArray[3] = 'Theater';			//text
$timeB[3] = 13;					//beginning time
$timeS[3] = 14.5;					//ending time
$day1[3] = 1;					//first day of the week
$day2[3] = 3;					//second day, -1 if none
$day3[3] = -1;					//third day, -1 if none
$type[3] = 'lec';					//type for class


$hold = array(0,0,0,0,0,0,0); /* for long time blocks (need to figure out data validation here)
if time scedualed activities overlap second one just wont show up, easyest */

$begintime = 8; 					//start time of sceduale
$endtime = 23;					//endtime of sceduale

//time increments are set on 10 = 1 hour, or 1 = 6 minutes
//monday = 0; sunday = 6;

function writeCell($time, $day) {
	global $textArray;
	global $timeB;
	global $timeS;
	global $day1;
	global $day2;
	global $day3;
	global $type;
	global $hold;
	global $day;
	global $time;
	$cellSet = "false";
	
	for($i = 0; $i < count($textArray); $i++) {
		if ($time == $timeB[$i] * 10) {
			$duration = ($timeS[$i] - $timeB[$i]) * 2;
			
			if ($day1[$i] == $day || $day2[$i] == $day || $day3[$i] == $day) {
				echo (" <td ");
				echo ("class = \"$type[$i]\" ");
				echo ("rowspan = \"$duration\">");
				echo ("$textArray[$i]");
				//echo ("$duration");
				echo ("</td>\n");
				if ($duration > 1) {
					$hold[$day] = $duration - 1;
				}
				$i = count($textArray);
				$cellSet = "true";
			}
		}
	}
	if ($cellSet == "false") {					//handle empty time block
		echo ("<td rowspan=\"1\"></td>\n");
	}
}

for ($time = $begintime * 10; $time <= $endtime * 10; $time += 5) {
	echo (" <tr>\n");							//begin row
	if ($time % 10 == 0) {
		echo ("<th class=\"time\" rowspan=\"2\">");
		$hour = $time / 10;
		echo ("$hour");
		echo (":00</th>\n");
	}
	for ($day = 0; $day < 7; $day++) {
		if ($hold[$day] > 0) { 					//look for hold
			$hold[$day]--;
		} else { 							//look for time match
			writeCell($time, $day);
		}
	}
	echo ("</tr>");
} ?>
</table>
</body>
</html>