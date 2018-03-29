<?php
/**
 * @author: ZhaQiu <34485431@qq.com>
 * @time: 2018/3/29
 */

namespace Viliy\SMS\Gateways;

use Viliy\SMS\Contracts\GateWayInterface;
use Viliy\SMS\Format\Config;

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
}
