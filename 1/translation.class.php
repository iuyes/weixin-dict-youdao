<?php
require_once 'config.inc.php';
class Translation{
		
	private $query_url;
	private $result_xml;
	private $result_array;
	private $result_string;
		
	public function __construct ( $query_word ) {
		$this->query_url = QUERY_URL.$query_word;
		$this->result_xml = $this->get_xml_result();
		$this->result_array = $this->xml_to_array();
		$this->result_string = $this->set_string_result();
	}	
	
	public function get_string_result () {    
		return $this->result_string;
	}
	
	private function set_string_result () {
		$result_str = "";	
			    
		$error_code = $this->result_array[0]["value"];
		
		if ($this->equal($error_code, QUERY_OK)) {
			$query_word = $this->result_array[1][value];
			//基本翻译
			$translation = $this->result_array[2][0]["value"];
			//音标
			$phonetic = $this->result_array[3][0]["value"];
			//基本翻译
			$base_translation_0 = $this->result_array[3][1][0]["value"];
			$base_translation_1 = $this->result_array[3][1][1]["value"];
			$base_translation_2 = $this->result_array[3][1][2]["value"];
			//网络释义
			$web_translation_0 = $this->result_array[4][0][1][0]["value"];
			$web_translation_1 = $this->result_array[4][0][1][1]["value"];
			$web_translation_2 = $this->result_array[4][0][1][2]["value"];
 			//网络词组
 			$web_phrase_0 = $this->result_array[4][1][0]["value"];
			$web_phrase_0_translation_0 = $this->result_array[4][1][1][0]["value"];
			$web_phrase_0_translation_1 = $this->result_array[4][1][1][1]["value"];
			$web_phrase_0_translation_2 = $this->result_array[4][1][1][2]["value"];
			
			$web_phrase_1 = $this->result_array[4][2][0]["value"];
			$web_phrase_1_translation_0 = $this->result_array[4][2][1][0]["value"];
			$web_phrase_1_translation_1 = $this->result_array[4][2][1][1]["value"];
			$web_phrase_1_translation_2 = $this->result_array[4][2][1][2]["value"];
			
			$result_str = $query_word." : ".$translation."\r\n".
						  "音标："."  [".$phonetic."]\r\n".
						  "\r\n"."基本释义："."\r\n".
						  $base_translation_0."\r\n".
						  $base_translation_1."\r\n".
						  $base_translation_2."\r\n".
						  "\r\n"."网络释义:"."\r\n".
						  $web_translation_0." ; ".
						  $web_translation_1." ; ".
						  $web_translation_2." ; ".
						  "\r\n\r\n"."词组："."\r\n"
                		  .$web_phrase_0."：\r\n".
						  $web_phrase_0_translation_0."\r\n".
						  $web_phrase_0_translation_1."\r\n".
						  $web_phrase_0_translation_2."\r\n".
						  "\r\n".$web_phrase_1."：\r\n".
						  $web_phrase_0_translation_0."\r\n".
						  $web_phrase_1_translation_1."\r\n".
						  $web_phrase_2_translation_2."\r\n";
			 
		} else if ($this->equal($error_code, QUERY_STR_TOOLONG)) {
			$result_str = "对不起，您要翻译的文本太长了,无法进行翻译！";
		} else if ($this->equal($error_code, QUERY_CAN_NOT_TRANSLATE)) {
			$result_str = "无法进行有效翻译！";
		} else if ($this->equal($error_code, QUERY_ERROR_LANGUAGE)) {
			$result_str = "不支持的语言类型！";
		} else {
			$result_str = "请求超时，请稍后再试！";
		}
		return $result_str;
	}
	
	private function get_xml_result () {
		$xml_result = file_get_contents($this->query_url);
		return $xml_result;
	} 
	
	private function xml_to_array() { 
	    $opened = array(); 
	    $opened[1] = 0; 
	    $xml_parser = xml_parser_create(); 
	    xml_parse_into_struct($xml_parser, $this->result_xml, $xmlarray); 
	    $array = array_shift($xmlarray); 
	    unset($array["level"]); 
	    unset($array["type"]); 
	    $arrsize = sizeof($xmlarray); 
	    for($j=0;$j<$arrsize;$j++){ 
	        $val = $xmlarray[$j]; 
	        switch($val["type"]){ 
	            case "open": 
	                $opened[$val["level"]]=0; 
	            case "complete": 
	                $index = ""; 
	                for($i = 1; $i < ($val["level"]); $i++) 
	                    $index .= "[" . $opened[$i] . "]"; 
	                $path = explode('][', substr($index, 1, -1)); 
	                $value = &$array; 
	                foreach($path as $segment) 
	                    $value = &$value[$segment]; 
	                $value = $val; 
	                unset($value["level"]); 
	                unset($value["type"]); 
	                if($val["type"] == "complete") 
	                    $opened[$val["level"]-1]++; 
	            break; 
	            case "close": 
	                $opened[$val["level"]-1]++; 
	                unset($opened[$val["level"]]); 
	            break; 
	        } 
	    } 
	    return $array; 
	} 

	private function equal ( $param1, $param2 ) {
		if ( $param1 === $param2 ) {
			return TRUE;
		}
		return FALSE;
	}
}
?>