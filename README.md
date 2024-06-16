# base-module

础组件
======

## 安装

打开 `composer.json` 找到或创建 `repositories` 键，添加VCS资源库。

```
	// ...
	"repositories": [
		// ...
		{
			"type": "vcs",
			"url": "git@github.com:chenhakim/base-module.git"
		}
	],
	// ...
```

添加依赖包。

```
composer require module/base-module dev-master
```


## 安装mail，邮件发送信息

执行第一步安装之后
找到 `config/app.php` 配置文件中，key为 `providers` 的数组，在数组中添加服务提供者。

```php
    'providers' => [
        // ...
        \Module\Base\Mail\CustomMailServiceProvider::class,
    ]
```

找到key为 `aliases` 的数组，在数组中注册Facades。

```php
    'aliases' => [
        // ...
        'CustomMail' =>  \Module\Base\Mail\Facades\CustomMail::class,
    ]
```

运行 `php artisan vendor:publish` 命令，发布配置和视图文件到项目中。

### 调用

找到 Exceptions/Handler.php 文件中的report方法,添加如下信息，即可不足后异常
```php
    if ($this->shouldReport($exception)) {
        CustomMail::sendEmail($exception); // sends an email
    }
```

不足之处，目前仅指出异常邮件处理，后续加入其他类型邮件处理

## 接口调用

执行第一步安装之后
找到 `config/app.php` 配置文件中，key为 `providers` 的数组，在数组中添加服务提供者。

```php
    'providers' => [
        // ...
        \Module\AmoyApi\AmoyApiProvider::class,
    ]
```

找到key为 `aliases` 的数组，在数组中注册Facades。

```php
    'aliases' => [
        // ...
        'CustomMail' =>  \Module\AmoyApi\Facades\AmoyApi::class,
    ]
```

运行 `php artisan vendor:publish` 命令，发布配置文件到项目中。

### 调用

```php
    AmoyApi::userReportList($param1, $param2, $param3);}
```


## 安装elastic search

执行第一步安装之后
找到 `config/app.php` 配置文件中，key为 `providers` 的数组，在数组中添加服务提供者。

```php
    'providers' => [
        // ...
        \Module\ElasticSearch\ElasticSearchProvider::class,
    ]
```

找到key为 `aliases` 的数组，在数组中注册Facades。

```php
    'aliases' => [
        // ...
        'ElasticSearch' =>  \Module\ElasticSearch\Facades\ElasticSearchProvider::class,
    ]
```

运行 `php artisan vendor:publish` 命令，发布配置和视图文件到项目中。


###调用
```php
$arrData = [
            'route' => $strRouteName,
            'method' => $strMethod,
            'date' => $nDate,
            'action' => Route::currentRouteAction(),
            'version' => $request->input('key'),
            'datetime' => time(),
            '_index' => 'amoy-test',
        ];
        app('es')->addDocument($arrData);
        // 或者
        ElasticSearch::addDocument($arrData); //引用facades
```

### 队列执行
Supervisor 配置如下队列执行信息，队列名称在config中配置，默认elk-log
```php
php artisan queue:work --queue=elk-log
```


## 安装google admob ssv

执行第一步安装之后
找到 `config/app.php` 配置文件中，key为 `providers` 的数组，在数组中添加服务提供者。

```php
    'providers' => [
        // ...
        \Module\Base\GoogleAdmobSsv\GoogleAdmobSsvProvider::class,
    ]
```

找到key为 `aliases` 的数组，在数组中注册Facades。

```php
    'aliases' => [
        // ...
        'GoogleAdmobSsv' =>  \Module\Base\GoogleAdmobSsv\Facades\GoogleAdmobSsv::class,
    ]
```

运行 `php artisan vendor:publish` 命令，发布配置和视图文件到项目中。

### 调用

```php
     GoogleAdmobSsv::verifyString($strData);
```

## 安装支付宝SDK
要使用支付宝SDK服务提供者，你必须自己注册服务提供者到Laravel/Lumen服务提供者列表中。
基本上有两种方法可以做到这一点。

### Laravel
找到 `config/app.php` 配置文件中，key为 `providers` 的数组，在数组中添加服务提供者。

```php
    'providers' => [
        // ...
        '\Module\Alipay\AlipayServiceProvider::class',
    ]
```


运行 `php artisan vendor:publish` 命令，发布配置文件到你的项目中。

### Lumen
在`bootstrap/app.php`里注册服务。

```php
//Register Service Providers
$app->register(Module\Alipay\AlipayServiceProvider::class);
```

由于Lumen的`artisan`命令不支持`vendor:publish`,需要自己手动将`src/config`下的配置文件拷贝到项目的`config`目录下,
并将`config.php`改名成`module-alipay.php`,
`mobile.php`改名成`module-alipay-mobile.php`,
`web.php`改名成`module-alipay-web.php`.

### 说明
配置文件 `config/module-alipay.php` 为公共配置信息文件， `config/module-alipay-web.php` 为Web版支付宝SDK配置， `config/module-alipay-mobile.php` 为手机端支付宝SDK配置。

## 例子

### 支付申请

#### 网页

```php
	// 创建支付单。
	$alipay = app('alipay.web');
	$alipay->setOutTradeNo('order_id');
	$alipay->setTotalFee('order_price');
	$alipay->setSubject('goods_name');
	$alipay->setBody('goods_description');
	
	$alipay->setQrPayMode('4'); //该设置为可选，添加该参数设置，支持二维码支付。

	// 跳转到支付页面。
	return redirect()->to($alipay->getPayLink());
```

#### 手机端

```php
	// 创建支付单。
	$alipay = app('alipay.mobile');
	$alipay->setOutTradeNo('order_id');
	$alipay->setTotalFee('order_price');
	$alipay->setSubject('goods_name');
	$alipay->setBody('goods_description');

	// 返回签名后的支付参数给支付宝移动端的SDK。
	return $alipay->getPayPara();
```

### 结果通知

#### 网页

```php
	/**
	 * 异步通知
	 */
	public function webNotify()
	{
		// 验证请求。
		if (! app('alipay.web')->verify()) {
			Log::notice('Alipay-a notify post data verification fail.', [
				'data' => Request::instance()->getContent()
			]);
			return 'fail';
		}

		// 判断通知类型。
		switch (Input::get('trade_status')) {
			case 'TRADE_SUCCESS':
			case 'TRADE_FINISHED':
				// TODO: 支付成功，取得订单号进行其它相关操作。
				Log::debug('Alipay-a notify post data verification success.', [
					'out_trade_no' => Input::get('out_trade_no'),
					'trade_no' => Input::get('trade_no')
				]);
				break;
		}
	
		return 'success';
	}

	/**
	 * 同步通知
	 */
	public function webReturn()
	{
		// 验证请求。
		if (! app('alipay.web')->verify()) {
			Log::notice('Alipay-a return query data verification fail.', [
				'data' => Request::getQueryString()
			]);
			return view('alipay.fail');
		}

		// 判断通知类型。
		switch (Input::get('trade_status')) {
			case 'TRADE_SUCCESS':
			case 'TRADE_FINISHED':
				// TODO: 支付成功，取得订单号进行其它相关操作。
				Log::debug('Alipay-a notify get data verification success.', [
					'out_trade_no' => Input::get('out_trade_no'),
					'trade_no' => Input::get('trade_no')
				]);
				break;
		}

		return view('alipay.success');
	}
```

#### 手机端

```php
	/**
	 * 支付宝异步通知
	 */
	public function alipayNotify()
	{
		// 验证请求。
		if (! app('alipay.mobile')->verify()) {
			Log::notice('Alipay-a notify post data verification fail.', [
				'data' => Request::instance()->getContent()
			]);
			return 'fail';
		}

		// 判断通知类型。
		switch (Input::get('trade_status')) {
			case 'TRADE_SUCCESS':
			case 'TRADE_FINISHED':
				// TODO: 支付成功，取得订单号进行其它相关操作。
				Log::debug('Alipay-a notify get data verification success.', [
					'out_trade_no' => Input::get('out_trade_no'),
					'trade_no' => Input::get('trade_no')
				]);
				break;
		}

		return 'success';
	}
```
