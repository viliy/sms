<?php

namespace Viliy\SMS\Contracts;

use Viliy\SMS\Support\Config;

/**
 * Interface GateWayInterface
 * @package Viliy\SMS\Contracts
 */
interface GateWayInterface
{

    /**
     * @return string
     */
    public function getGatewayName();

    /**
     * @return string
     */
    public function getRequestMethod();

    /**
     * @return string
     */
    public function getApiUrl();

    /**
     * @param $phone
     * @param MessageInterface $message
     * @param $config
     * @return array
     */
    public function send($phone, MessageInterface $message, Config $config = null);

    /**
     * @param Config $config
     */
    public function setConfig(Config $config);

    /**
     * @param $params
     * @return array
     */
    public function sign($params);

    /**
     * @param array $params
     * @return array
     */
    public function request(array $params);

    /**
     * @param $result
     */
    public function checkStatus($result = null);
}
