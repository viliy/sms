<?php
/**
 * @author: ZhaQiu <34485431@qq.com>
 * @time: 2018/3/28
 */

namespace Viliy\SMS\Contracts;

/**
 * Interface StrategyInterface
 * @package Viliy\SMS\Contracts
 */
interface StrategyInterface
{
    /**
     * @param array $gateways
     * @return array
     */
    public function apply(array $gateways);
}
