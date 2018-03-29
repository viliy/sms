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
class WeightStrategy implements StrategyInterface
{

    /**
     * @param array $gateways
     * @return array
     */
    public function apply(array $gateways)
    {
        uasort($gateways, function ($a, $b) {
            if ($a == $b) return 0;
            return ($a > $b) ? -1 : 1;
        });

        return self::weight($gateways);
    }

    /**
     * @param array $gateways
     * @return array
     */
    protected static function weight(array $gateways)
    {
        $sum = array_sum($gateways);
        $arr = [];

        foreach ($gateways as $key => $value) {
            for ($i = 0; $i >= $sum; $i++) {
                $arr[] = $key;
            }
        }

        shuffle($arr);
        $arr = array_unique($arr);

        return array_values($arr);
    }
}
