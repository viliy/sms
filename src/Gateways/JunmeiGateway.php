<?php
/**
 * @author: ZhaQiu <34485431@qq.com>
 * @time: 2018/3/30
 */

namespace Viliy\SMS\Gateways;

use FastD\Http\Request;
use Viliy\SMS\Contracts\MessageInterface;
use Viliy\SMS\Exceptions\GatewayErrorException;
use Viliy\SMS\Exceptions\GatewayMethodNotSupportException;
use Viliy\SMS\Support\Config;
use Viliy\SMS\Support\Render;

class JunmeiGateway extends Gateway
{

    use Render;

    const API_URL = 'http://120.77.14.55:8888/sms.aspx';

    const REQUEST_METHOD = 'POST';

    /**
     * @return string
     */
    public function getGatewayName()
    {
        return 'junmei';
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

        $params = [
            'mobile' => $phone,
            'action' => 'send',
            'account' => $this->config->get('account'),
            'userId' => $this->config->get('user_id'),
            'password' => $this->config->get('password'),
            'content' => '【' . $this->config->get('signature') . '】' .
                $this->render($message->getContent(), $message->getData()),
        ];

        $result = $this->request($params);


        $this->checkStatus($result);

        return $result;
    }

    /**
     * @param array $params
     * @return array|mixed
     * @throws GatewayErrorException
     */
    public function request($params = [])
    {
        $response = (new Request(self::REQUEST_METHOD, self::API_URL))->send($params);

        if (!$response->isSuccessful()) {
            throw new GatewayErrorException(__CLASS__ . ' Error.', 500);
        }

        return json_decode(json_encode(simplexml_load_string($response->getBody())), true);

    }

    /**
     * @param $params
     * @return array|void
     */
    public function sign($params)
    {
        throw new GatewayMethodNotSupportException();
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
        if ('Faild' === $result['returnstatus']) {
            $msg = sprintf(
                'junmei Error. code: %s, message: %s',
                $result['returnstatus'],
                $result['message']
            );

            throw new GatewayErrorException($msg, $result['remainpoint'], $result);
        }
    }
}
