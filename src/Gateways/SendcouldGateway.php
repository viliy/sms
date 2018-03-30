<?php

namespace Viliy\SMS\Gateways;

use Viliy\SMS\Contracts\MessageInterface;
use Viliy\SMS\Exceptions\GatewayErrorException;
use Viliy\SMS\Support\Config;

/**
 * Class SendCouldGatewayway
 * @package Viliy\SMS\Gateways
 */
class SendcouldGateway extends Gateway
{

    const API_URL = 'http://www.sendcloud.net/smsapi/send';


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
        return 'sendcould';
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
            'smsUser'    => $config->get('sms_user'),
            'templateId' => $message->getIdentifier(),
            'phone'      => $phone,
            'msgType'    => 2,
            'vars'       => json_encode($message->getData()),
        ];

        $params['signature'] = $this->sign($params);

        $result = $this->request($params);

        $this->checkStatus($result);

        return $result;
    }


    /**
     * @param array $vars
     *
     * @return string
     */
    protected function formatTemplateVars(array $vars)
    {
        $formatted = [];

        foreach ($vars as $key => $value) {
            $formatted[sprintf('%%%s%%', trim($key, '%'))] = $value;
        }

        return json_encode($formatted, JSON_FORCE_OBJECT);
    }

    /**
     * @param array $params
     * @return array
     */
    public function sign($params)
    {
        ksort($params);

        return md5(sprintf(
                '%s&%s&%s',
                $this->config->get('sms_key'),
                urldecode(http_build_query($params)),
                $this->config->get('sms_key')
            )
        );
    }

    /**
     * @param null $result
     * @throws GatewayErrorException
     */
    public function checkStatus($result = null)
    {
        if (!$result['result']) {
            throw new GatewayErrorException($result['message'], $result['statusCode'], $result);
        }
    }
}
