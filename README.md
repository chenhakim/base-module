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


## 安装恶业google admob ssv

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
