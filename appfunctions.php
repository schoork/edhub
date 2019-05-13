<?php

function parseMoney($money) {

}

function parseObsDT($datetime) {
    $time = new DateTime($datetime);
    return $time->format('g:i a');
}

function parseRefDT($datetime) {
    $datetimeArray = explode(" ", $datetime);
    $date = $datetimeArray[0];
    $time = $datetimeArray[1];

    $dateArray = explode("-", $date);
    $amerDate = ltrim($dateArray[0]) . '-' . ltrim($dateArray[1]) . '-' . $dateArray[2];

    $timeArray = explode(":", $time);
    $ampm = 'am';
    if ($timeArray[0] > 12) {
    $timeArray[0] = $timeArray[0] - 12;
    $ampm = 'pm';
    }
    else if ($timeArray[0] == 00) {
    $timeArray[0] = 12;
    }
    $amerTime = ltrim($timeArray[0]) . ":" . $timeArray[1] . $ampm;

    $newDatetime = $amerDate . ' at ' . $amerTime;
    return $newDatetime;
}

function parseDatetime($datetime) {
  $datetimeArray = explode(" ", $datetime);
  $date = $datetimeArray[0];
  $time = $datetimeArray[1];

  $dateArray = explode("-", $date);
  $amerDate = ltrim($dateArray[1]) . '/' . ltrim($dateArray[2]) . '/' . $dateArray[0];

  $timeArray = explode(":", $time);
  $ampm = 'am';
  if ($timeArray[0] > 12) {
    $timeArray[0] = $timeArray[0] - 12;
    $ampm = 'pm';
  }
  else if ($timeArray[0] == 00) {
    $timeArray[0] = 12;
  }
  $amerTime = ltrim($timeArray[0]) . ":" . $timeArray[1] . $ampm;

  $newDatetime = $amerDate . ' at ' . $amerTime;
  return $newDatetime;
}

function parseTime($time) {
  $timeArray = explode(":", $time);
  $ampm = 'am';
  if ($timeArray[0] > 12) {
    $timeArray[0] = $timeArray[0] - 12;
    $ampm = 'pm';
  }
  $amerTime = ltrim($timeArray[0]) . ":" . $timeArray[1] . $ampm;

  $newDatetime = $amerTime;
  return $newDatetime;
}

function makeDateAmerican($date) {
 if ($date != '') {
   $dateComponents = getdate(strtotime($date));
 }
 else {
   $dateComponents = getdate();
 }
 $month = $dateComponents['mon'];
 $year = $dateComponents['year'];
 $day_of_month = $dateComponents['mday'];
 $date_amer = "$month/$day_of_month/$year";
 return $date_amer;
}

function makeDateBritish($date) {
 if ($date != '') {
   $dateComponents = getdate(strtotime($date));
 }
 else {
   $dateComponents = getdate();
 }
 $month = $dateComponents['mon'];
 $year = $dateComponents['year'];
 $day_of_month = $dateComponents['mday'];
 $date_brit = "$year-$month-$day_of_month";
 return $date_brit;
}

function makeTime($date) {
 $dateComponents = getdate(strtotime($date));
 $hours = $dateComponents['hours'];
 $minutes = $dateComponents['minutes'];
 $time = "$hours : $minutes";
 return $time;
}

function calculateAverage($student_id) {
 $dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
 $query = "SELECT sum(points) AS sum, sum(total_points) AS total FROM gradebook_grades AS gg LEFT JOIN gradebook_assignments AS ga ON (gg.assignment_id = ga.id) WHERE student_id=$student_id AND grade_calc=1";
 $result = mysqli_query($dbc, $query);
 $row = mysqli_fetch_array($result);
 if ($row['total'] != 0) {
   $points = $row['sum'];
   $total = $row['total'];
   $average = round($points/$total*100,1);
 }
 else {
   $average = 'N/A';
 }
 return $average;
}

function drawBarGraph($width, $height, $data, $max_value, $filename, $target) {
 //Create the empty graph image
 $img = imagecreatetruecolor($width, $height);

 //Set a white background with black text and grey graphics
 $bg_color = imagecolorallocate($img, 255, 255, 255);   //white
 $text_color = imagecolorallocate($img, 255, 255, 255);   //white
 $bar_above_target_color = imagecolorallocate($img, 0, 128, 0);   //green
 $bar_below_target_color = imagecolorallocate($img, 255, 0, 0);   //red
 $bar_color = imagecolorallocate($img, 0, 0, 0);   //black
 $border_color = imagecolorallocate($img, 192, 192, 192);   //light gray

 //Fill the background
 imagefilledrectangle($img, 0, 0, $width, $height, $bg_color);

 //Draw the bars
 $bar_width = $width / ((count($data) * 2) + 1);
 for ($i = 0; $i < count($data); $i++) {
   if ($data[$i][1] >= $target) {
     $indiv_bar_color = $bar_above_target_color;
   }
   else {
     $indiv_bar_color = $bar_below_target_color;
   }
   imagefilledrectangle($img, ($i * $bar_width * 2) + $bar_width, $height, ($i * $bar_width * 2) + ($bar_width * 2), $height - (($height / $max_value) * $data[$i][1]), $indiv_bar_color);
   imagestringup($img, 5, ($i * $bar_width * 2) + ($bar_width), $height - 5, $data[$i][0], $text_color);
 }

 //Draw a rectangle around the whole thing
 imagerectangle($img, 0, 0, $width - 1, $height - 1, $border_color);

 //Draw the range up the left side of the graph
 for ($i = 1; $i < 6; $i++) {
   $value = round($i * $max_value / 5, 1);
   imagestring($img, 5, 0, $height - ($value * ($height / $max_value)), $value, $bar_color);
 }

 //Write the graph image to a file
 imagepng($img, $filename, 5);
 imagedestroy($img);
}

function getMaxValue($array) {
 $max_value = 0;
 foreach ($array as $value) {
   if ($max_value < $value) {
     $max_value = $value;
   }
 }
 return $max_value;
}

?>
