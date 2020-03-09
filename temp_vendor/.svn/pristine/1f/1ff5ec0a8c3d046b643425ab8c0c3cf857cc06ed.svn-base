<?php
namespace Haoyou\Cashier\Api;

use Haoyou\Cashier\Config\WxPayConfig;

/**

 * wechat php test

 */

//define your token

// define("TOKEN", "weixin");

// $wechatObj = new wechatCallbackapi();

// $wechatObj->valid();

class wechatCallbackapi
{

    private function getAccessToken()
    {
        $appid     = WxPayConfig::APPID;//'wxfebacb6c401a6773';
        $appsecret = WxPayConfig::APPSECRET;//'fadb5b7163f55cdfbdbb67fedd138176';//
        $url       = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=$appid&secret=$appsecret";

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $output = curl_exec($ch);
        curl_close($ch);
        $jsoninfo     = json_decode($output, true);
        $access_token = $jsoninfo["access_token"];
        return $access_token;
    }
    public function createMenu()
    {
        $jsonmenu = '{
		      "button":[
		      {
		            "name":"客服中心",
		           "sub_button":[
		            {
		                "type":"view",
		                "name":"游戏下载",
		                "url":"http://www.sk9.cc"
		            },
		            {
		               "type":"click",
		               "name":"人工客服",
		               "key":"kefu"
		            },
		            {
		                "type":"click",
		                "name":"官方声明",
		                "key":"shengming"
		            },
		            {
		                "type":"click",
		                "name":"常见问题",
		                "key":"wenti"
		            }]
		       },
		       {
		            "name":"领取红包",
		            "type":"click",
		            "key":"redback"
		       },
		       {
		            "name":"游戏充值",
		            "sub_button":[
		            {
		                "type":"view",
		                "name":"房卡购买",
		                "url":"http://trade.haoyou998.com/order/1"
		            },
		            {
		                "type":"view",
		                "name":"推广员",
		                "url":"http://skhy.haoyou998.com"
		            }]
		       },
		   ]
		 }';
        $url    = "https://api.weixin.qq.com/cgi-bin/menu/create?access_token=" . $this->getAccessToken();
        $result = $this->https_request($url, $jsonmenu);
        var_dump($result);
    }
    public function valid()
    {

        $echoStr = $_GET["echostr"];

        //valid signature , option

        if ($this->checkSignature()) {

            echo $echoStr;

            exit;

        }

    }

    public function responseNews($newsContent)
    {
    	$postStr = $GLOBALS["HTTP_RAW_POST_DATA"];

        $object = simplexml_load_string($postStr, 'SimpleXMLElement', LIBXML_NOCDATA);

    	$newsTplHead = "<xml>
                <ToUserName><![CDATA[%s]]></ToUserName>
                <FromUserName><![CDATA[%s]]></FromUserName>
                <CreateTime>%s</CreateTime>
                <MsgType><![CDATA[news]]></MsgType>
                <ArticleCount>1</ArticleCount>
                <Articles>";
		$newsTplBody = "<item>
		                <Title><![CDATA[%s]]></Title> 
		                <Description><![CDATA[%s]]></Description>
		                <PicUrl><![CDATA[%s]]></PicUrl>
		                <Url><![CDATA[%s]]></Url>
		                </item>";
		$newsTplFoot = "</Articles>
		                <FuncFlag>0</FuncFlag>
		                </xml>";
		$header = sprintf($newsTplHead, $object->FromUserName, $object->ToUserName, time());
		$title = $newsContent['title'];
		$desc = $newsContent['description'];
		$picUrl = $newsContent['picUrl'];
		$url = $newsContent['url'];
		$body = sprintf($newsTplBody, $title, $desc, $picUrl, $url);

		$FuncFlag = 0;
		$footer = sprintf($newsTplFoot, $FuncFlag);

		echo $header.$body.$footer;

    }
    public function responseMsg($contentStr)
    {

//get post data, May be due to the different environments

        $postStr = $GLOBALS["HTTP_RAW_POST_DATA"];

        //extract post data

        if (!empty($postStr)) {

            $postObj = simplexml_load_string($postStr, 'SimpleXMLElement', LIBXML_NOCDATA);

            $fromUsername = $postObj->FromUserName;

            $toUsername = $postObj->ToUserName;

            //$keyword = trim($postObj->Content);

            $time = time();

            $textTpl = "<xml>

<ToUserName><![CDATA[%s]]></ToUserName>

<FromUserName><![CDATA[%s]]></FromUserName>

<CreateTime>%s</CreateTime>

<MsgType><![CDATA[%s]]></MsgType>

<Content><![CDATA[%s]]></Content>

<FuncFlag>0</FuncFlag>

</xml>";

            $msgType = "text";

            //$contentStr = "Welcome to wechat world!";

            $resultStr = sprintf($textTpl, $fromUsername, $toUsername, $time, $msgType, $contentStr);

            echo $resultStr;

        } else {

            echo "";

            exit;

        }

    }
    private function checkSignature()
    {

        $signature = $_GET["signature"];

        $timestamp = $_GET["timestamp"];

        $nonce = $_GET["nonce"];

        $token = WxPayConfig::TOKEN;

        $tmpArr = array($token, $timestamp, $nonce);

        sort($tmpArr);

        $tmpStr = implode($tmpArr);

        $tmpStr = sha1($tmpStr);

        if ($tmpStr == $signature) {

            return true;

        } else {

            return false;

        }

    }
    public function https_request($url, $data = null)
    {
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
        if (!empty($data)) {
            curl_setopt($curl, CURLOPT_POST, 1);
            curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
        }
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        $output = curl_exec($curl);
        curl_close($curl);
        return $output;
    }

}
