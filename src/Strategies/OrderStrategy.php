<?php
/**
 * @author: ZhaQiu <34485431@qq.com>
 * @time: 2018/3/28
 */

namespace Viliy\SMS\Strategies;

use Viliy\SMS\Contracts\StrategyInterface;

/**
 * Class OrderStrategy
 * @package Viliy\SMS\Strategies
 */
class OrderStrategy implements StrategyInterface
{
    /**
     * @param array $gateways
     * @return array
     */
    public function apply(array $gateways)
    {
        return array_keys($gateways);
    }
}