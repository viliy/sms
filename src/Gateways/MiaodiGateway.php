<?php
/**
 * @author: ZhaQiu <34485431@qq.com>
 * @time: 2018/3/30
 */

namespace Viliy\SMS\Gateways;

use Viliy\SMS\Contracts\MessageInterface;
use Viliy\SMS\Exceptions\GatewayErrorException;
use Viliy\SMS\Exceptions\GatewayMethodNotSupportException;
use Viliy\SMS\Support\Config;
use Viliy\SMS\Support\Render;

class MiaodiGateway extends Gateway
{
    use Render;

    const API_URL = 'https://api.miaodiyun.com/20150822/industrySMS/sendSMS';

    const REQUEST_METHOD = 'POST';

    /**
     * @return string
     */
    public function getGatewayName()
    {
        return 'miaodi';
    }

    /**
     * @return string
     */
    public function getRequestMethod()
    {
        return self::REQUEST_METHOD;
    }

    /**
     * @return string
     */
    public function getApiUrl()
    {
        return self::API_URL;
    }

    /**
     * @param $phone
     * @param MessageInterface $message
     * @param Config|null $config
     * @return array
     * @throws GatewayErrorException
     */
    public function send($phone, MessageInterface $message, Config $config = null)
    {
        !is_null($config) && $this->setConfig($config);

        date_default_timezone_set("Asia/Shanghai");
        $timestamp = date("YmdHis");

        $params = [
            'accountSid'    => $this->config->get('account_sid'),
            'to'            => $phone,
            'timestamp'     => date('YmdHis'),
            'sig'           => $this->sign($timestamp),
            'smsContent'    => '【' . $this->config->get('signature') . '】' .
                $this->Render($message->getContent(), $message->getData()),
            "respDataType"  => "JSON"
        ];

        $result = $this->request($params);

        $this->checkStatus($result);

        return $result;
    }

    /**
     * @param $params
     * @return string
     */
    public function sign($params)
    {
        $sign = md5(
            $this->config->get('account_sid') .
            $this->config->get('auth_token') .
            (string)$params
        );

        return $sign;
    }

    /**
     * @param null $result
     * @throws GatewayErrorException
     */
    public function checkStatus($result = null)
    {

        if (!isset($result['respCode']) || '00000' !== $result['respCode']) {

            $msg = sprintf(
                'MiaoDi Error. code: %s, message: %s',
                $result['respCode'],
                $result['respDesc']
            );

            throw new GatewayErrorException($msg, $result['respCode'], $result);
        }
    }
}