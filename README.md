# sms

## 平台支持

- [阿里大于](https://www.alidayu.com/)
- [SendCloud](http://www.sendcloud.net/)
- [253云通讯（创蓝）](https://www.253.com/)
- [秒嘀科技](http://www.miaodiyun.com/)
- [未来无线](http://www.10690757.com)
- [掌骏传媒](http://www.zjunmedia.cn)

## 环境需求

- PHP >= 7.0

## 安装

```shell
$ composer require "viliy/sms"
```

## 使用

```php
use Viliy\SMS\Sender;

require __DIR__ . '/vendor/autoload.php';

/**
 * 平台网关配置
 */

$gateways = [
    'alidayu' => [
        'app_key' => '*****',
        'app_secret' => '*******',
        'signature' => '签名',
        'weight' => 10,  // 渠道比重  排序算法时使用 可不传 in:1,100
    ],
    'sendcloud' => [
        'sms_user' => '*****',
        'sms_key' => '**********',
        'signature' => '签名',
        'weight' => 20,
    ],
    'weilaiwuxian' => [
        'cust_code' => '******',
        'password' => '*************',
        'signature' => '签名',
        'weight' => 10,

    ],
    'junmei' => [
        'account' => '******',
        'user_id' => '*************',
        'password' => '*************',
        'signature' => '签名',
        'weight' => 10,

    ],
    'miaodi' => [
        'account_sid' => '*******',
        'auth_token' => '**************',
        'signature' => '签名',
        'weight' => 10,

    ],
    'chuanglan' => [
        'account' => '******',
        'password' => '*************',
        'signature' => '签名',
        'weight' => 10,

    ]
];

/**
 * 短信配置
 * 为了兼容多个发送平台 message需要包含以下信息
 */
$message = [
    'identifier' => '短信模板',   // 平台短信模板  
    'content' => '尊敬的用户,您的短信验证码为：{code}',  // 短信模板
    'data' => [  // 参数 对应content 变量
        'code' => 12344,
    ],
    'type' => 'text'
];

/**
 * 使用
 */
try {
    $sender = new Sender('weight', $gateways); // 初始化发送实例， 提供三个排序算法: order 顺序发送，random 随机发送， weight 权重发送， 默认order
    
    $result = $sender->send('15018093840', $message);

} catch (\Viliy\SMS\Exceptions\InvalidArgumentException $e) {

    var_dump($e->getMessage());
} catch (\Viliy\SMS\Exceptions\NoGatewayAvailableException $e) {
    var_dump($e->getMessage());
}

// or 
try {
    $sender = new Sender('weight');
    
    $result = $sender->send('15018093840', $message, $gateways);  // 可以发送时指定网关

} catch (\Viliy\SMS\Exceptions\InvalidArgumentException $e) {

    var_dump($e->getMessage());
} catch (\Viliy\SMS\Exceptions\NoGatewayAvailableException $e) {
    var_dump($e->getMessage());
}

var_dump($result);

## 返回值

由于使用多网关发送，所以返回值为一个数组，结构如下：
```php
[
    'chuanglan' => [
        'status' => 'success',
        'result' => [...] // 平台返回值
    ],
    'aliyun' => [
        'status' => 'failure',
        'result' => [...] // 捕获的错误信息
    ],
    //...
]
```

## todo

错误信息的格式化和处理

回调查看短信是否发送成功（判断第三方是否发送成功）


## License

MIT