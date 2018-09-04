<?php

namespace Viliy\SMS\Gateways;

use Viliy\SMS\Contracts\MessageInterface;
use Viliy\SMS\Exceptions\GatewayErrorException;
use Viliy\SMS\Support\Config;
use Viliy\SMS\Support\Render;

/**
 * Class WeilaiwuxianGateway
 * @package Viliy\SMS\Gateways
 */
class WeilaiwuxianGateway extends Gateway
{

    use Render;

    const API_URL = 'http://123.58.255.70:8860/sendSms';

    const REQUEST_METHOD = 'POST';

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
     * Get gateway name.
     *
     * @return string
     */
    public function getGatewayName()
    {
        return 'weilaiwuxian';
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

        $content = '【' . $this->config->get('signature') . '】' .
            $this->Render($message->getContent(), $message->getData());

        $params = [
            'destMobiles' => $phone,
            'cust_code' => $this->config->get('cust_code'),
            'content' => $content,
        ];

        $params['sign'] = $this->sign($content);

        $result = $this->request(json_encode($params));

        $this->checkStatus($result);

        return $result;
    }

    /**
     * @param $params
     * @return string
     */
    public function sign($params)
    {
        return md5($params . $this->config->get('password'));
    }

    /**
     * @param null $result
     * @throws GatewayErrorException
     */
    public function checkStatus($result = null)
    {
        if (is_null($result)) {
            throw new GatewayErrorException('未知错误', 500, []);
        }

        if (isset($result['result']) && !$result['result']) {
            throw new GatewayErrorException($result['message'], $result['statusCode'], $result);
        }

        if (isset($result)) {
            throw new GatewayErrorException(json_encode($result, 256), 500, $result);
        }
    }
}
