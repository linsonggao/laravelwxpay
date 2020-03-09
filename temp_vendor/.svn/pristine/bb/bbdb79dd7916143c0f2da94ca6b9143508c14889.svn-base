<?php
/**
 * Created by PhpStorm.
 * User: lufee
 */

namespace Haoyou\Cashier\Lib;

class Huafutong_lib {

    /**
     * 异步通知时，对参数做固定排序
     * @param $para 排序前的参数组
     * @return 排序后的参数组
     */
    public function _sort_notify_para($para) {
        $para_sort['service'] = $para['service'];
        $para_sort['v'] = $para['v'];
        $para_sort['sec_id'] = $para['sec_id'];
        $para_sort['notify_data'] = $para['notify_data'];
        return $para_sort;
    }

    /**
     * @author lufee
     * @param $paras
     * @param $pay_sign
     * @param $return_url
     * @return string
     */
    public function _get_html($paras, $pay_sign, $return_url) {
        $html = '<script type="text/javascript">';
        $html .= 'function jsApiCall(){';
        $html .= 'WeixinJSBridge.invoke("getBrandWCPayRequest", {';
        $html .= '"appId" : "' . $paras['appId'] . '",';
        $html .= '"timeStamp" : "' . $paras['timeStamp'] . '",';
        $html .= '"nonceStr" : "' . $paras['nonceStr'] . '",';
        $html .= '"package" : "' . $paras['package'] . '",';
        $html .= '"signType" : "MD5",';
        $html .= '"paySign" : "' . $pay_sign . '"';
        $html .= '},function(res){';
        $html .= 'if(res.err_msg == "get_brand_wcpay_request:ok" ) {';
        $html .= 'window.location.href = "' . $return_url . '";';
        $html .= '} else if (res.err_msg == "access_control:not_allow") {';
        $html .= 'alert("微信支付未审核");';
        $html .= '} else {';
        $html .= '';
        $html .= '}';
        $html .= '})';
        $html .= '}';
        $html .= '</script>';
        return $html;
    }
}