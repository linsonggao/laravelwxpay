<?php
/**
 * Created by PhpStorm.
 * @author:lufee
 */

namespace Haoyou\Cashier\Weifutong;

use Haoyou\Cashier\Lib\Core;
use Haoyou\Cashier\Lib\Weifutong_lib;
use Haoyou\Cashier\Utils\Http;
use Illuminate\Support\Facades\Request;


class Weifutong {

    const WEIFUTONG_PAY_URL      = 'https://pay.swiftpass.cn/pay/gateway?';

    protected $_config;
    protected $http;
    protected $core;
    protected $weifutong_lib;
    public function __construct()
    {
        $this->http = new Http();
        $this->core = new Core();
        $this->weifutong_lib = new Weifutong_lib();
    }

    public function setConfig($config){
        $this->_config = $config;
        return $this;
    }

    /***
     * 威富通通提交付款
     */
    public function build_form($order){
        $notify_url   = $this->core->get_notify_url(array($order['payment_sn']));

        $subject = '用户充值';

        //构造要请求的参数数组
        $params = array(
            "service"        => 'pay.weixin.wappay',
            "version"      => '2.0',
            "charset"      => 'UTF-8',
            "sign_type"     => 'MD5',
            "mch_id"     => $this->_config['mch_id'],
            "out_trade_no" => $order['payment_sn'],
            "body"    => $subject,
            "total_fee" =>intval($order['amount'] * 100),
            "mch_create_ip" => Request::getClientIp(),
            "notify_url"	=> $notify_url,
            "callback_url"	=> $this->core->get_return_url($order['app_name'],$order['app_id'],$order['user_id']),
            "device_info" => "iOS_WAP",
            "mch_app_name" => "H5支付",
            "mch_app_id" => "http://www.haoyou998.com/",
            "nonce_str" => mt_rand(time(),time()+rand())
        );
        $params = $this->core->_para_filter($params);
        ksort($params);
        $paramStr = '';
        foreach ($params as $key=>$v)
        {
            $paramStr .= $key."=".$v."&";
        }

        $paramStr .= "key=" . $this->_config['key'];
        $sign = strtoupper(md5($paramStr));
        $params['sign'] = $sign;

        $data = $this->weifutong_lib->toXml($params);

        $response = $this->http->curl_call(static::WEIFUTONG_PAY_URL,$data);
        $response = $this->weifutong_lib->libxml_nocdata($response);

        if ($response['status'] == 0 && $response['result_code'] == 0) {
            if(isset($response['pay_info']))
            {
                return $response['pay_info'];
            }else{
                return '<script>alert("系统异常，请稍后再试")</script>';
            }
        }else{
            return '<script>alert("系统异常，请稍后再试")</script>';
        }

        //https://statecheck.swiftpass.cn/pay/wappay?token_id=28f2e6687e030924acbc7b309aac905c8&service=pay.weixin.wappayv3

    }



    /**
     * 通知验证
     * @author lufee
     * @return array|bool
     */
    public function verify_notify($params)
    {
        $post_data = file_get_contents("php://input");
        $post_data = (array)simplexml_load_string($post_data, 'SimpleXMLElement', LIBXML_NOCDATA);
        if ($post_data) {
            if ($this->_sign_veryfy($post_data, $post_data['sign'], FALSE)) {

                $trade_sn = $post_data['transaction_id'];
                return array(
                    'trade_sn' => $trade_sn
                );
            }
        }
        return FALSE;
    }

    /**
     * 验证签名
     * @param $para_temp
     * @param $sign
     * @param $is_sort
     * @return bool
     */
    public function _sign_veryfy($params, $sign, $is_sort)
    {
        $para = array();
        foreach ($params as $key => $val) {
            if ($key == "result_desc" || $key == "extends" || $key == "sign") {
                continue;
            } else {
                $para[$key] = $params[$key];
            }
        }
        $paramStr = '';
        foreach ($para as $key=>$v)
        {
            $paramStr .= $key."=".$v."&";
        }

        $paramStr .= "key=" . $this->_config['key'];
        $para_str = strtoupper(md5($paramStr));

        if ($para_str == strtoupper($sign)) {
            return TRUE;
        } else {
            return FALSE;
        }
    }
}