<?php
/*
$options=explode(',',$options);
$ok='0';
foreach($options as $option){
if ($option=='1'){
$ok='1';	
}	
}
return $ok;
*/
$condition[] = strpos(' '.$options,'1',0) ? '1':'0';
return;
?>