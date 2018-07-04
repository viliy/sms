<?php

namespace Viliy\SMS\Gateways;

use FastD\Http\Request;
use Viliy\SMS\Contracts\MessageInterface;
use Viliy\SMS\Exceptions\GatewayErrorException;
use Viliy\SMS\Support\Config;
use Viliy\SMS\Support\Render;

/**
 * Class SendCouldGatewayway
 * @package Viliy\SMS\Gateways
 */
class NexmoGateway extends Gateway
{

    use Render;

    const API_URL = 'https://rest.nexmo.com/sms/json?';

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
        return 'nexmo';
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
            'api_key'    => $config->get('api_key'),
            'api_secret' => $config->get('api_secret'),
            'to'         => $phone,
            'from'       => $config->get('from'),
            'text'       => '【' . $this->config->get('signature') . '】' .
                $this->render($message->getContent(), $message->getData()),
            'type'       => 'unicode'
        ];

        $result = $this->request($params);

        $this->checkStatus($result);

        return $result;
    }

    /**
     * @param array $params
     * @return array
     * @throws GatewayErrorException
     */
    public function request($params = [])
    {
        $response = (new Request(
            self::REQUEST_METHOD,
            self::API_URL . http_build_query($params),
            ['Content-Type: application/json;']
        ))->send();

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

        $signed = [];

        foreach ($params as $key => $value) {
            $signed[$key] = str_replace(array("&", "="), "_", $value);
        }

        return md5('&' . urldecode(http_build_query($signed) . $this->config->get('api_secret')));
    }

    /**
     * @param null $result
     * @throws GatewayErrorException
     */
    public function checkStatus($result = null)
    {
        if (is_null($result) || !isset($result['messages'])) {
            throw new GatewayErrorException(__CLASS__ . ' Error.', 500, []);
        }

        if (0 !== $result['messages'][0]['status']) {
            throw new GatewayErrorException( json_encode($result), 500, []);
        }
    }
}
