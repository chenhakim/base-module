<?php

namespace Module\Alipay;

use Illuminate\Support\ServiceProvider;
use Illuminate\Foundation\Application as LaravelApplication;
use Laravel\Lumen\Application as LumenApplication;

class AlipayServiceProvider extends ServiceProvider {

    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    public function boot() {
        // this for config
        $this->setupConfig();
    }

    /**
     * Setup the config.
     *
     * @return void
     */
    public function setupConfig() {
        $source_config = realpath( __DIR__ . '/../../config/config.php' );
        $source_mobile = realpath( __DIR__ . '/../../config/mobile.php' );
        $source_web = realpath( __DIR__ . '/../../config/web.php' );

        if ( $this->app instanceof LaravelApplication && $this->app->runningInConsole() ) {
            $this->publishes([
                $source_config => config_path('module-alipay.php'),
                $source_mobile => config_path('module-alipay-mobile.php'),
                $source_web => config_path('module-alipay-web.php'),
            ]);
        } elseif ( $this->app instanceof LumenApplication ) {
            $this->app->configure('module-alipay');
            $this->app->configure('module-alipay-mobile');
            $this->app->configure('module-alipay-web');
        }

        $this->mergeConfigFrom($source_config, 'module-alipay');
        $this->mergeConfigFrom($source_mobile, 'module-alipay-mobile');
        $this->mergeConfigFrom($source_web, 'module-alipay-web');
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register() {

        /**
         * 注册移动端支付
         */
        $this->app->bind( 'alipay.mobile', function ($app) {
            $alipay = new Mobile\SdkPayMent();

            $alipay->setPartner( $app->config->get( 'module-alipay.partner_id' ) )
                ->setSellerId( $app->config->get( 'module-alipay.seller_id' ) )
                ->setSignType( $app->config->get( 'module-alipay-mobile.sign_type' ) )
                ->setPrivateKeyPath( $app->config->get( 'module-alipay-mobile.private_key_path' ) )
                ->setPublicKeyPath( $app->config->get( 'module-alipay-mobile.public_key_path' ) )
                ->setNotifyUrl( $app->config->get( 'module-alipay-mobile.notify_url' ) );

            return $alipay;
        } );

        /**
         * 注册web端支付
         */
        $this->app->bind('alipay.web', function ($app) {
            $alipay = new Web\SdkPayment();

            $alipay->setPartner( $app->config->get( 'module-alipay.partner_id' ) )
                ->setSellerId( $app->config->get( 'module-alipay.seller_id' ) )
                ->setKey( $app->config->get( 'module-alipay-web.key' ) )
                ->setSignType( $app->config->get( 'module-alipay-web.sign_type' ) )
                ->setNotifyUrl( $app->config->get( 'module-alipay-web.notify_url' ) )
                ->setReturnUrl( $app->config->get( 'module-alipay-web.return_url' ) )
                ->setExterInvokeIp( $app->request->getClientIp() );

            return $alipay;
        });

        /**
         * 注册wap端支付
         */
        $this->app->bind('alipay.wap', function ($app) {
            $alipay = new Wap\SdkPayment();

            $alipay->setPartner( $app->config->get( 'module-alipay.partner_id' ) )
                ->setSellerId( $app->config->get( 'module-alipay.seller_id' ) )
                ->setKey( $app->config->get( 'module-alipay-web.key' ) )
                ->setSignType( $app->config->get( 'module-alipay-web.sign_type' ) )
                ->setNotifyUrl( $app->config->get( 'module-alipay-web.notify_url' ) )
                ->setReturnUrl( $app->config->get( 'module-alipay-web.return_url' ) )
                ->setExterInvokeIp( $app->request->getClientIp() );

            return $alipay;
        });
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides() {
        return [
            'alipay.mobile',
            'alipay.web',
            'alipay.wap',
        ];
    }

}