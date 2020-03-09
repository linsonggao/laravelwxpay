<?php
/**
 * Created by PhpStorm.
 * @author:lufee
 */

namespace Haoyou\Cashier\Utils;


use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;

class Http {

    protected $client;

    public function __construct()
    {
        $this->client = new Client();
    }

    public function __call($name, $arguments)
    {
        $response = $this->client->$name($arguments[0], $arguments[1])->getBody();
        return $response;

        /*$response = json_decode($this->client->$name($arguments[0], $arguments[1])->getBody()->getContents(), true);
        if (isset($response['errcode']) && $response['errcode'] != 0) {
            throw new \Exception(isset($response['errmsg']) ? "errcode:".$response['errcode']."->".$response['errmsg'] : 'Unknown');
        }
        return $response;*/
    }

    public function curl_call($url,$data)
    {
        //启动一个CURL会话
        $ch = curl_init();

        // 设置curl允许执行的最长秒数
        curl_setopt($ch, CURLOPT_TIMEOUT, 200);
        curl_setopt($ch,CURLOPT_SSL_VERIFYPEER,false);
        curl_setopt($ch,CURLOPT_SSL_VERIFYHOST,false);
        // 获取的信息以文件流的形式返回，而不是直接输出。
        curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);

        //发送一个常规的POST请求。
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_URL, $url);
        //要传送的所有数据
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);

        // 执行操作
        $res = curl_exec($ch);
        $responseCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

        if ($res == NULL) {
            $this->errInfo = "call http err :" . curl_errno($ch) . " - " . curl_error($ch) ;
            curl_close($ch);
            return false;
        } else if($responseCode  != "200") {
            $this->errInfo = "call http err httpcode=" . $responseCode  ;
            curl_close($ch);
            return false;
        }

        curl_close($ch);


        return $res;
    }

}