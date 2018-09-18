<?php
/**
 * @author: ZhaQiu <34485431@qq.com>
 * @time: 2018/4/25
 */

namespace Viliy\SMS\Gateways;

use FastD\Http\Request;
use Viliy\SMS\Contracts\MessageInterface;
use Viliy\SMS\Exceptions\GatewayErrorException;
use Viliy\SMS\Support\Config;
use Viliy\SMS\Support\Render;

class ChuanglanGateway extends Gateway
{
    use Render;

    const API_URL = 'http://smssh1.253.com/msg/send/json';

    const REQUEST_METHOD = 'POST';

    /**
     * @return string
     */
    public function getGatewayName()
    {
        return 'ChuangLan';
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
     * @throws \Viliy\SMS\Exceptions\GatewayErrorException
     */
    public function send($phone, MessageInterface $message, Config $config = null)
    {
        !is_null($config) && $this->setConfig($config);

        $params = [
            'account' => $config->get('account'),
            'password' => $config->get('password'),
            'msg' => '【' . $this->config->get('signature') . '】' .
                $this->Render($message->getContent(), $message->getData()),
            'phone' => $phone,
            'report' => 'true'
        ];

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
        return urlencode($params);
    }

    /**
     * @param array $params
     * @return array
     * @throws GatewayErrorException
     */
    public function request($params = [])
    {
        $response = (new Request(self::REQUEST_METHOD, self::API_URL))->send(
            $params,
            ['Content-Type: application/json; charset=utf-8']
        );

        if (!$response->isSuccessful()) {
            if (!empty($result = $response->toArray())) {
                throw new GatewayErrorException(__CLASS__ . ' Error.', 500);
            } else {
                throw new GatewayErrorException(__CLASS__ . ': ' . json_encode($result), 500);
            }
        }

        return $response->toArray();
    }

    /**
     * @param null $result
     * @throws GatewayErrorException
     */
    public function checkStatus($result = null)
    {
        if (is_null($result)) {
            throw new GatewayErrorException('unknown error', 500, []);
        }

        if (!isset($result['code']) || $result['code'] != '0') {
            throw new GatewayErrorException($result['errorMsg'], $result['code'], $result);
        }
    }
}
