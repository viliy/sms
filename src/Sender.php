<?php

namespace Viliy\SMS;

use Viliy\SMS\Contracts\GateWayInterface;
use Viliy\SMS\Contracts\MessageInterface;
use Viliy\SMS\Contracts\StrategyInterface;
use Viliy\SMS\Exceptions\GatewayErrorException;
use Viliy\SMS\Exceptions\InvalidArgumentException;
use Viliy\SMS\Exceptions\NoGatewayAvailableException;
use Viliy\SMS\Support\Config;
use Viliy\SMS\Support\Message;

/**
 * Class Sender
 * @package Viliy\SMS
 */
class Sender
{

    const STATUS_SUCCESS = 'success';

    const STATUS_FAILURE = 'failure';

    protected $config;

    /**
     * @var StrategyInterface
     */
    protected $strategy;

    /**
     * @var array
     */
    protected $gateways = [];

    /**
     * Sender constructor.
     * @param array $gateways
     * @param string|null $strategy
     * @throws InvalidArgumentException
     */
    public function __construct(string $strategy = null, $gateways = [])
    {
        !is_null($strategy) && $this->makeStrategy($strategy);
        if (!empty($gateways)) {
            $this->createGateways($gateways);
        }
    }

    /**
     * @param $phone
     * @param array $message
     * @param array $gateways
     * @return mixed
     * @throws InvalidArgumentException
     * @throws NoGatewayAvailableException
     */
    public function send($phone, array $message, array $gateways = [])
    {
        $message = $this->makeMessage($message);

        if (!empty($gateways)) {
            $this->createGateways($gateways);
        }

        $strategyGateways = $this->formatStrategy($this->config);

        $results = [];
        foreach ($strategyGateways as $gateway) {
            try {
                $results[$gateway] = [
                    'status' => self::STATUS_SUCCESS,
                    'result' => $this->getGateway($gateway)
                        ->send($phone, $message, new Config($this->config[$gateway])),
                ];
                $isSuccessful = true;

                break;
            } catch (GatewayErrorException $exception) {
                $results[$gateway] = [
                    'status' => self::STATUS_FAILURE,
                    'result' => (array)$exception->raw ?? (array)$exception->getMessage(),
                ];

                continue;
            }
        }

        if (!isset($isSuccessful)) {
            throw new NoGatewayAvailableException($results);
        }

        return $results;
    }

    /**
     * @param array $gateways
     * @return $this
     * @throws InvalidArgumentException
     */
    public function createGateways(array $gateways)
    {
        $this->config = $gateways;

        foreach ($gateways as $gateway => $config) {
            if (!isset($this->gateways[$gateway]) || !($this->gateways[$gateway] instanceof GateWayInterface)) {
                $this->makeGateway($gateway);
            }
        }

        return $this;
    }


    /**
     * @param $name
     * @return GateWayInterface
     * @throws InvalidArgumentException
     */
    protected function getGateway($name): GateWayInterface
    {
        $name = ucfirst($name);
        if (!isset($this->gateways[$name]) && !class_exists($this->gateways[$name])) {
            throw new InvalidArgumentException(sprintf('Gateway "%s" not exists.', $name));
        }

        return $this->gateways[$name];
    }

    /**
     * @param $name
     * @throws InvalidArgumentException
     */
    protected function makeGateway($name)
    {
        $name = ucfirst(str_replace(['-', '_', ''], '', $name));

        $classGateway = __NAMESPACE__ . "\\Gateways\\{$name}Gateway";

        if (!class_exists($classGateway)) {
            throw new InvalidArgumentException(sprintf('Gateway %s not exists.', $name));
        }

        $this->gateways[$name] = new $classGateway();
    }

    /**
     * @param $name
     * @throws InvalidArgumentException
     */
    protected function makeStrategy($name)
    {
        $name = ucfirst(str_replace(['-', '_', ''], '', $name));

        $classStrategy = __NAMESPACE__ . "\\Strategies\\{$name}Strategy";

        if (!class_exists($classStrategy)) {
            throw new InvalidArgumentException("Unsupported strategy \"{$classStrategy}\"");
        }

        $this->strategy = new $classStrategy();
    }

    /**
     * @param array $message
     * @return Message
     */
    protected function makeMessage(array $message)
    {
        if (!($message instanceof MessageInterface)) {
            $message = new Message($message);
        }

        return $message;
    }

    /**
     * @param null $strategy
     * @return StrategyInterface
     * @throws InvalidArgumentException
     */
    protected function strategy($strategy = null): StrategyInterface
    {
        if (!($this->strategy instanceof StrategyInterface)) {
            is_null($strategy) && $strategy = 'order';
            $this->makeStrategy($strategy);
        }

        return $this->strategy;
    }

    /**
     * @param $gateways
     * @return array
     * @throws InvalidArgumentException
     */
    protected function formatStrategy($gateways)
    {
        $weight = [];

        foreach ($gateways as $key => $value) {
            $weight[$key] = $value['weight'] ?? 10;
        }

        return $this->strategy()->apply($weight) ?? [];
    }
}
