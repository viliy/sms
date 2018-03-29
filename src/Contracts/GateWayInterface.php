<?php

namespace Viliy\SMS\Contracts;
use Viliy\SMS\Format\Config;

/**
 * Interface GateWayInterface
 * @package Viliy\SMS\Contracts
 */
interface GateWayInterface
{
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
     * @param array $params
     * @return array
     */
    public function request(array $params);
}
