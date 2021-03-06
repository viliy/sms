<?php
/**
 * @author: ZhaQiu <34485431@qq.com>
 * @time: 2018/3/29
 */

namespace Viliy\SMS\Gateways;

use FastD\Http\Request;
use FastD\Http\Response;
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
     * @return Response
     * @throws GatewayErrorException
     */
    public function request($params = [])
    {
        $response = (new Request($this->getRequestMethod(), $this->getApiUrl()))->send($params);

        if (!$response->isSuccessful()) {
            if (!empty($result = $response->toArray())) {
                throw new GatewayErrorException(__CLASS__ . ' Error.', 500);
            } else {
                throw new GatewayErrorException(__CLASS__ . ': ' . json_encode($result), 500);
            }
        }

        return $response;
    }

    /**
     * @param Config $config
     * @return array
     */
    public function report(Config $config)
    {
        // TODO: Implement report() method.
    }
}
