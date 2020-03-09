<?php namespace Haoyou\Cashier;

/**
 * Created by PhpStorm.
 * User: beller
 */

class ServiceProvider extends \Illuminate\Support\ServiceProvider {

    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = false;

    /**
     * Bootstrap the application events.
     *
     * @return void
     */
    public function boot()
    {
    }


    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton('cashier.huafutong', function () {
            return new Huafutong\Huafutong();
        });
        $this->app->singleton('cashier.weifutong', function () {
            return new Weifutong\Weifutong();
        });
        $this->app->singleton('cashier.jsapipay', function () {
            return new Api\JsApiPay();
        });
        $this->app->singleton('cashier.wxpayunifiedorder', function () {
            return new Data\WxPayUnifiedOrder();
        });
        $this->app->singleton('cashier.wxpayapi', function () {
            return new Api\WxPayApi();
        });
        $this->app->singleton('cashier.config', function () {
            return new Config\WxPayConfig();
        });
        $this->app->singleton('cashier.wechatCallbackapi', function () {
            return new Api\wechatCallbackapi();
        });
        $this->app->singleton('cashier.wxpay', function () {
            return new Api\wxPay();
        });
    }
}