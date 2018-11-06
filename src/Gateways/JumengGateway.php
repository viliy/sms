<?php
/**
 * @author: ZhaQiu <34485431@qq.com>
 * @time: 2018/11/5
 */

namespace Viliy\SMS\Gateways;

use Viliy\SMS\Contracts\MessageInterface;
use Viliy\SMS\Exceptions\GatewayErrorException;
use Viliy\SMS\Support\Config;
use Viliy\SMS\Support\Render;

/**
 * 聚梦
 *
 * Class JumengGateway
 * @package Viliy\SMS\Gateways
 */
class JumengGateway extends Gateway
{

    use Render;

    const API_URL = 'http://58.252.3.163:8357/smsgwhttp/sms/submit';

    const REQUEST_METHOD = 'GET';

    const NAME = 'jumeng';

    /**
     * @return string
     */
    public function getGatewayName()
    {
        return self::NAME;
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
     * @param $config
     * @throws GatewayErrorException
     * @return array
     */
    public function send($phone, MessageInterface $message, Config $config = null)
    {
        !is_null($config) && $this->setConfig($config);

        $params = [
            'spid' => $config->get('spid'),
            'password' => $config->get('password'),
            'content' => '【' . $this->config->get('signature') . '】' .
                $this->Render($message->getContent(), $message->getData()),
            'mobiles' => $phone,
            'ac' => $config->get('ac')
        ];

        $response = $this->request($params);

        $result = json_decode(json_encode(simplexml_load_string($response->getBody())), true);

        $this->checkStatus($result);

        return $result;
    }

    /**
     * @param $params
     * @return string
     */
    public function sign($params)
    {
        // TODO: Implement sign() method.
    }

    /**
     * @param null $result
     * @throws GatewayErrorException
     */
    public function checkStatus($result = null)
    {
        if (!isset($result['result'])) {
            throw new GatewayErrorException('the jumeng gateway returns no data', 500, $result ?? []);
        }
        if (0 != $result['result']) {
            throw new GatewayErrorException($result['desc'], 500, $result);
        }
    }
}