<?php

use Viliy\SMS\Sender;

/**
 * @author: ZhaQiu <34485431@qq.com>
 * @time: 2018/4/16
 */
class SenderTest extends \PHPUnit\Framework\TestCase
{

    public function testGateway()
    {
        $gateways = [
            'alidayuTest' => [
                'app_key' => '*****',
                'app_secret' => '*****',
                'signature' => '****',
                'weight' => 10,
            ],
        ];

        try {
            new Sender($gateways);
        } catch (\Viliy\SMS\Exceptions\InvalidArgumentException $e) {
            $this->assertEquals('Gateway AlidayuTest not exists.', $e->getMessage());
        }
    }
}
