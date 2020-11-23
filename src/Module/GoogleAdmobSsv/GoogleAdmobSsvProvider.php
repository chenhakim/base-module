<?php
namespace Module\GoogleAdmobSsv;

use Illuminate\Support\ServiceProvider;

class GoogleAdmobSsvProvider extends ServiceProvider
{

	/**
	 * Bootstrap the application events.
	 *
	 * @return void
	 */
	public function boot()
	{
		$this->publishes([
			__DIR__ . '/config/config.php' => config_path('module-google-admob-ssv.php')
		], 'config');
	}

	/**
	 * Register the service provider.
	 *
	 * @return void
	 */
	public function register()
	{
		$this->mergeConfigFrom(__DIR__ . '/config/config.php', 'module-google-admob-ssv');

		$this->app->singleton('google-admob-ssv', function ($app) {
			$config = $app->config->get('module-google-admob-ssv');
			return new GoogleAdmobSsv($config);
		});
	}

	/**
	 * Get the services provided by the provider.
	 *
	 * @return array
	 */
	public function provides()
	{
		return [
			'google-admob-ssv'
		];
	}
}
