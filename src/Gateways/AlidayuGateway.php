<?php

/*
 * This file is part of the overtrue/easy-sms.
 *
 * (c) overtrue <i@overtrue.me>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Viliy\SMS\Gateways;

use FastD\Http\Request;
use Viliy\SMS\Contracts\MessageInterface;
use Viliy\SMS\Exceptions\GatewayErrorException;
use Viliy\SMS\Format\Config;
use Viliy\SMS\Gateways\Gateway;

/**
 * Class AlidayuGateway.
 *
 * @see http://open.taobao.com/doc2/apiDetail?apiId=25450#s2
 */
class AlidayuGateway extends Gateway
{

    const API_URL = 'http://gw.api.taobao.com/router/rest';

    const API_METHOD = 'alibaba.aliqin.fc.sms.num.send';

    const API_VERSION = '2.0';

    const API_FORMAT = 'json';

    /**
     * Get gateway name.
     *
     * @return string
     */
    public function getName()
    {
        return 'alidayu';
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

        var_dump($this->config->get('signature'));
        $params['sign'] = $this->generateSign($params);

        var_dump($params);

        $result = $this->request($params);

        if (!empty($result['error_response'])) {
            if (isset($result['error_response']['sub_msg'])) {
                $message = $result['error_response']['sub_msg'];
            } else {
                $message = $result['error_response']['msg'];
            }

            throw new GatewayErrorException($message, $result['error_response']['code'], $result);
        }

        return $result;
    }

    /**
     * Generate Sign.
     *
     * @param array $params
     *
     * @return string
     */
    protected function generateSign($params)
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
     * @param array $params
     * @return array
     * @throws GatewayErrorException
     */
    public function request(array $params)
    {
        $response = (new Request('POST', self::API_URL))->send($params);

        var_dump($response->getBody());
        if (!$response->isSuccessful()) {
            throw new GatewayErrorException('Alidayu Gateway Error.', 500);
        }

        return $response->toArray();
    }
}
