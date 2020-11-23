<?php
namespace Module\GoogleAdmobSsv\Facades;

use Illuminate\Support\Facades\Facade;

class GoogleAdmobSsv extends Facade
{

	/**
	 * Get the registered name of the component.
	 *
	 * @return string
	 */
	protected static function getFacadeAccessor()
	{
		return 'google-admob-ssv';
	}
}