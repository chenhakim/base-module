<?php
namespace Module\AmoyApi;

use Illuminate\Support\ServiceProvider;

class AmoyApiProvider extends ServiceProvider
{

	/**
	 * Bootstrap the application events.
	 *
	 * @return void
	 */
	public function boot()
	{
		$this->publishes([
			__DIR__ . '/config/config.php' => config_path('module-amoy-api.php')
		], 'config');
	}

	/**
	 * Register the service provider.
	 *
	 * @return void
	 */
	public function register()
	{
		$this->mergeConfigFrom(__DIR__ . '/config/config.php', 'module-amoy-api');

		$this->app->singleton('amoy-api', function ($app) {
			$config = $app->config->get('module-amoy-api');
			return new AmoyApi($config);
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
			'amoy-api'
		];
	}
}
