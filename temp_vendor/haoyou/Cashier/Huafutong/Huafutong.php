<?php
/**
 * Created by PhpStorm.
 * @author:lufee
 */

namespace Haoyou\Cashier\Huafutong;

use Haoyou\Cashier\Lib\Core;
use Haoyou\Cashier\Lib\Huafutong_lib;
use Haoyou\Cashier\Utils\Http;


class Huafutong {

    const HUAFUTONG_PAY_URL      = 'http://gateway.71pay.cn/Pay/GateWay10.shtml?';

    protected $_config;
    protected $http;
    protected $core;
    protected $huafutong_lib;
    public function __construct()
    {
        $this->http = new Http();
        $this->core = new Core();
        $this->huafutong_lib = new Huafutong_lib();
    }

    public function setConfig($config){
        $this->_config = $config;
        return $this;
    }

    /***
     * 话付通通提交付款
     */
    public function build_form($order){
        $notify_url   = $this->core->get_notify_url(array($order['payment_sn']));

        $subject = '用户充值';

        $orderTime = $this->core->local_date('YmdHis');

        //构造要请求的参数数组
        $params = array(
            "app_id"        => $this->_config['app_id'],
            "pay_type"      => $this->_config['pay_type'],
            "order_id"      => $order['payment_sn'],
            "order_amt"     => $order['amount'] * 100,
            "notify_url"	=> $notify_url,
            "return_url"    => $this->core->get_return_url($order['app_name'],$order['app_id'],$order['user_id']),
            "goods_name"    => $subject,
            "time_stamp"    => $orderTime,
        );
        $params = $this->core->_para_filter($params);
        $paramStr = '';
        foreach ($params as $key=>$v)
        {
            if($key == "goods_name")
            {
                continue;
            }
            $paramStr .= $key."=".$v."&";
        }
        $paramStr = substr($paramStr,0,-1);
        //dd($params,$paramStr);

        //$paramStr = "app_id=2911&pay_type=2&order_id=1706276582047501&order_amt=0.1000&notify_url=http://rechargeh5.haoyou998.com/cashier/paynotify/1706276582047501&return_url=http://rechargeh5.haoyou998.com/recharge/success/w2017062720355219366&time_stamp=20170627203552";
        $sign = md5($paramStr."&key=".md5($this->_config['huafutong_key']));
        $pa = static::HUAFUTONG_PAY_URL.$paramStr."&goods_name=".urlencode("用户充值")."&sign=".$sign;
        return $pa;
    }



    /**
     * 通知验证
     * @author lufee
     * @return array|bool
     */
    public function verify_notify($params)
    {
        if ($params) {
            if ($this->_sign_veryfy($params, $params['sign'], FALSE)) {

                $trade_sn = $params['pay_seq'];
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
        if($para)
        {
            $paramStr .= "app_id=".$para['app_id']."&order_id=".$para['order_id']."&pay_seq=".$para['pay_seq']."&pay_amt=".$para['pay_amt']."&pay_result=".$para['pay_result'];
        }

        $para_str = md5($paramStr."&key=".md5($this->_config['huafutong_key']));

        if (strtolower($para_str) == strtolower($sign)) {
            return TRUE;
        } else {
            return FALSE;
        }
    }
}