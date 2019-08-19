<?php
namespace Module\Amoypi\Facades;

use Illuminate\Support\Facades\Facade;

class AmoyApi extends Facade
{

	/**
	 * Get the registered name of the component.
	 *
	 * @return string
	 */
	protected static function getFacadeAccessor()
	{
		return 'amoy-api';
	}
}