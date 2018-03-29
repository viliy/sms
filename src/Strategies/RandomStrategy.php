<?php
/**
 * @author: ZhaQiu <34485431@qq.com>
 * @time: 2018/3/28
 */

namespace Viliy\SMS\Strategies;

use Viliy\SMS\Contracts\StrategyInterface;

/**
 * Class RandomStrategy
 * @package Viliy\SMS\Strategies
 */
class RandomStrategy implements StrategyInterface
{

    /**
     * @param array $gateways
     * @return array
     */
    public function apply(array $gateways)
    {
        uasort($gateways, function () {
            return mt_rand() - mt_rand();
        });

        return array_keys($gateways);
    }
}