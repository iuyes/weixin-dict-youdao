<?php
    //查询URL
    define('QUERY_URL', 'http://fanyi.youdao.com/openapi.do?keyfrom=SmallEnglish&key=682914935&type=data&doctype=xml&version=1.1&q=');
	
	//查询单词返回错误码
	define('QUERY_OK', "0");
	define('QUERY_STR_TOOLONG', "20");
	define('QUERY_CAN_NOT_TRANSLATE', "30");
	define('QUERY_ERROR_LANGUAGE', "40");
	define('QUERY_ERROR_KEY', "50");
	
	//定义消息类型
	define('EVENT', 'event');
	define('TEXT', 'text');
	define('SUBSCRIBE', 'subscribe');
	
	//define token
	define("TOKEN", "smallenglish");
	
	
	    
?>