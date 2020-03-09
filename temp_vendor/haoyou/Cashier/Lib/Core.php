<?php
/**
 * Created by PhpStorm.
 * User: lufee
 */

namespace Haoyou\Cashier\Lib;

class Core {

    /**
     * 获取随机字符串
     *
     * @author  beller
     * @param   int $length
     * @return  string
     */
    public function getNonceStr($length = 16)
    {
        $chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
        $str = '';
        for ($i = 0; $i < $length; $i++) {
            $str .= substr($chars, mt_rand(0, strlen($chars) - 1), 1);
        }
        return $str;
    }

    public function make_notify_url($function,$_sn){
        $domain = 'http://'.env('PAYSUBDOMAIN').'.'.env('BASE_DOMAIN');
        if($_sn)
        {
            return $domain.$function.implode("/",$_sn);
        }
        return $domain.$function;

    }

    /**
     * 获取通知地址
     *
     * @author    lufee
     * @return    string
     */
    public function get_notify_url($_sn)
    {
        $url = $this->make_notify_url('/cashier/paynotify/',$_sn);
        return $url;
    }

    /**
     * 获取返回地址
     *
     * @author    lufee
     * @return    string
     */
    public function get_return_url($app_name,$app_id,$user_id)
    {
        $url = $this->make_notify_url('/recharge/'.$app_name.'/'.$app_id."/".$user_id,"");
        return $url;
    }

    /**
     * 参数过滤
     * @param $para
     * @return array
     */
    public function _para_filter($para) {
        $para_filter = array();
        foreach ($para as $key => $val) {
            if ($key == "sign" || $key == "sign_type" || $val == "") {
                continue;
            } else {
                $para_filter[$key] = $para[$key];
            }
        }
        return $para_filter;
    }


    /**
     * 格式化时间戳
     * @author beller
     * @param $format
     * @param null $time
     * @return bool|string
     */
    public  function local_date($format)
    {
        return date($format);
    }

    /**
     * 验证签名
     * @param $prestr 需要签名的字符串
     * @param $sign 签名结果
     * @param $key 私钥
     * return 签名结果
     */
    public function md5Verify($prestr, $sign, $key) {
        $prestr = $prestr . $key;
        $mysgin = md5($prestr);
        if($mysgin == $sign) {
            return true;
        }
        else {
            return false;
        }
    }

} 