<?php
/**
 * @author: ZhaQiu <34485431@qq.com>
 * @time: 2018/3/29
 */

namespace Viliy\SMS\Gateways;

use FastD\Http\Request;
use Viliy\SMS\Contracts\GateWayInterface;
use Viliy\SMS\Exceptions\GatewayErrorException;
use Viliy\SMS\Support\Config;

/**
 * Class Gateway
 * @package Viliy\SMS\Gateways
 */
abstract class Gateway implements GateWayInterface
{
    /**
     * @var Config
     */
    protected $config;

    /**
     * @param Config $config
     */
    public function setConfig(Config $config)
    {
        $this->config = $config;
    }

    /**
     * @param $params
     * @return array
     * @throws GatewayErrorException
     */
    public function request($params = [])
    {
        $response = (new Request($this->getRequestMethod(), $this->getApiUrl()))->send($params);

        if (!$response->isSuccessful()) {
            throw new GatewayErrorException(sprintf('%s Gateway Error.', $this->getGatewayName()), 500);
        }

        return $response->toArray();
    }
}
