<?php
/*
* description: returns german date
* usage: [+string:date_ger=`format`+]
* 	%a - abbreviated weekday name according to the current locale 
	%A - full weekday name according to the current locale 
	%b - abbreviated month name according to the current locale 
	%B - full month name according to the current locale 
	%c - preferred date and time representation for the current locale 
	%d - day of the month as a decimal number (range 01 to 31) 
	%D - same as %m/%d/%y 
	%e - day of the month as a decimal number, a single digit is preceded by a space (range ' 1' to '31') 
	%h - same as %b
	%H - hour as a decimal number using a 24-hour clock (range 00 to 23) 
	%I - hour as a decimal number using a 12-hour clock (range 01 to 12) 
	%m - month as a decimal number (range 01 to 12) 
	%M - minute as a decimal number 
	%n - newline character 
	%p - either `am' or `pm' according to the given time value, or the corresponding strings for the current locale 
	%r - time in a.m. and p.m. notation 
	%R - time in 24 hour notation 
	%S - second as a decimal number 
	%t - tab character 
	%T - current time, equal to %H:%M:%S 
	%x - preferred date representation for the current locale without the time 
	%X - preferred time representation for the current locale without the date 
	%y - year as a decimal number without a century (range 00 to 99) 
	%Y - year as a decimal number including the century 
	%Z - time zone or name or abbreviation 
	%% - a literal `%' character 
*/

$options=explode('||',$options);
//path where adodb-time.inc.php is installed.
$dateoptions=(isset($options[0]))?$options[0]:'';
$format = (strlen($options[0])>0) ? $options[0] : '%A, %e. %B %Y';
if ($format==''){
	return $output;
}
$adodbpath=(isset($options[1]))?$options[1]:"assets/snippets/Xett/";
$adodbFile=MODX_BASE_PATH.$adodbpath.'adodb-time.inc.php';
$useadodbtime='0';
if (file_exists($adodbFile)) {
	include_once($adodbFile);
	$useadodbtime='1';
}
$output=(is_numeric($output))?$output:time(); 
setlocale (LC_TIME, 'de_DE.UTF-8');
$date_ger = ($useadodbtime=='1')?xetadodb_strftime($format,0+$output):strftime($format,0+$output);

return $date_ger;
?>