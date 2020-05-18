<?php
namespace Module\ElasticSearch;

use Illuminate\Support\ServiceProvider;
use Module\ElasticSearch\ElasticSearch\ElasticSearchClient;

class ElasticSearchClientProvider extends ServiceProvider
{

	/**
	 * Bootstrap the application events.
	 *
	 * @return void
	 */
	public function boot()
	{
		$this->publishes([
			__DIR__ . '/config/config.php' => config_path('module-elastic-search.php')
		], 'config');
	}

	/**
	 * Register the service provider.
	 *
	 * @return void
	 */
	public function register()
	{
		$this->mergeConfigFrom(__DIR__ . '/config/config.php', 'module-elastic-search');

        $this->app->singleton('es',function() {
            return new ElasticSearchClient();
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
			'es'
		];
	}
}
