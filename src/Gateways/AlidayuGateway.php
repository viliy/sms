<?php

namespace Viliy\SMS\Gateways;

use Viliy\SMS\Contracts\MessageInterface;
use Viliy\SMS\Exceptions\GatewayErrorException;
use Viliy\SMS\Support\Config;

/**
 * Class AlidayuGateway
 * @package Viliy\SMS\Gateways
 * @see http://open.taobao.com/doc2/apiDetail?apiId=25450#s2
 */
class AlidayuGateway extends Gateway
{

    const API_URL = 'http://gw.api.taobao.com/router/rest';

    const API_METHOD = 'alibaba.aliqin.fc.sms.num.send';

    const API_VERSION = '2.0';

    const API_FORMAT = 'json';

    const REQUEST_METHOD = 'POST';

    /**
     * Get gateway name.
     *
     * @return string
     */
    public function getGatewayName()
    {
        return 'alidayu';
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
            'method'             => self::API_METHOD,
            'format'             => self::API_FORMAT,
            'v'                  => self::API_VERSION,
            'sign_method'        => 'md5',
            'timestamp'          => date('Y-m-d H:i:s'),
            'sms_type'           => 'normal',
            'sms_free_sign_name' => $this->config->get('signature'),
            'app_key'            => $this->config->get('app_key'),
            'sms_template_code'  => $message->getIdentifier(),
            'rec_num'            => strval($phone),
            'sms_param'          => json_encode($message->getData()),
        ];

        $params['sign'] = $this->sign($params);

        $result = $this->request($params);

        $this->checkStatus($result);

        return $result;
    }

    /**
     * @param $params
     * @return array
     */
    public function sign($params)
    {
        ksort($params);
        $stringToBeSigned = $this->config->get('app_secret');
        foreach ($params as $key => $value) {
            if (is_string($value) && '@' != substr($value, 0, 1)) {
                $stringToBeSigned .= "$key$value";
            }
        }

        $stringToBeSigned .= $this->config->get('app_secret');

        return strtoupper(md5($stringToBeSigned));
    }

    /**
     * @param $result
     * @throws GatewayErrorException
     */
    public function checkStatus($result = null)
    {
        if (is_null($result)) {
            throw new GatewayErrorException('未知错误', 500, []);
        }

        if (!empty($result['error_response'])) {
            if (isset($result['error_response']['sub_msg'])) {
                $message = $result['error_response']['sub_msg'];
            } else {
                $message = $result['error_response']['msg'];
            }

            throw new GatewayErrorException($message, $result['error_response']['code'], $result);
        }
    }
}
