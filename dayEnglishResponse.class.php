<?php
require_once 'translation.class.php';

class DayEnglishResponse {
	private $translation;
	
	private $fromUsername;
	private $toUsername;
	private $msgtype;
	private $keyword;
	private $event;
	private $result_string;
	
	public function __construct () {
		$this->getPostData();		
		$this->result_string = $this->getResultString();
	}
	
	public function valid () {
        $echoStr = $_GET["echostr"];

        //valid signature , option
        if($this->checkSignature()){
        	echo $echoStr;
        	exit;
        }
    }

    public function responseMsg () {
		
        $time = time();
        $resultTpl ="<xml>
					<ToUserName><![CDATA[%s]]></ToUserName>
					<FromUserName><![CDATA[%s]]></FromUserName>
					<CreateTime>%s</CreateTime>
					<MsgType><![CDATA[%s]]></MsgType>
					<Content><![CDATA[%s]]></Content>
					<FuncFlag>0</FuncFlag>
					</xml>";             
		
		$resultXml = sprintf($resultTpl, $this->toUsername, $this->fromUsername, $time, TEXT, is_string($this->msgtype));
        
        echo $resultXml;
    }
	
	private function getResultString () {
		$result_string = '';
		if ($this->equal($this->msgtype, EVENT)) {		
			$result_string = "欢迎关注爆笑英语。希望您在爆笑的时候，也能提高您的英语能力!（现只提供查词功能!输入您要查询的单词，就可以获得对应的中文翻译。如string;）";
		} else if ($this->equal($this->msgtype, TEXT)){
		
			if (!empty( $this->keyword ) && preg_match("/^[a-zA-Z]*$/", $this->keyword)) {
				
				$this->translation = new Translation( $this->keyword );
				$result_string = $this->translation->get_string_result();
			} else {
			
				$result_string = "请正确输入您要翻译的单词或句子!";
			}
		} else {
	
			$result_string = "现只支持文本类型消息!";
		}
		return $result_string;
	}

	private function getPostData () {
		$postStr = $GLOBALS["HTTP_RAW_POST_DATA"];
		
		if (!empty($postStr)) {
			$postObj = simplexml_load_string($postStr, 'SimpleXMLElement', LIBXML_NOCDATA);
			$this->toUsername = $postObj->FromUserName;
			$this->fromUsername = $postObj->ToUserName;
			$this->keyword = trim($postObj->Content);
			$this->msgtype = $postObj->MsgType;
			$this->event = $postObj->Event;
		} else {
			echo "";
			exit;
		}

	}
		
	private function checkSignature () {
        $signature = $_GET["signature"];
        $timestamp = $_GET["timestamp"];
        $nonce = $_GET["nonce"];	
        		
		$token = TOKEN;
		$tmpArr = array($token, $timestamp, $nonce);
		sort($tmpArr);
		$tmpStr = implode( $tmpArr );
		$tmpStr = sha1( $tmpStr );
		
		if( $tmpStr == $signature ){
			return true;
		}else{
			return false;
		}
	}
	
	private function equal ( $param1, $param2 ) {
		if ( $param1 === $param2 ) {
			return TRUE;
		}
		return FALSE;
	}
}

?>