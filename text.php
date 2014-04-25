<?php
	$html = file_get_contents("http://dict.cn/你好");
	
	echo $html->find(".main");
	
   
	 
?>