<?php
/**
 * @author: ZhaQiu <34485431@qq.com>
 * @time: 2018/3/29
 */

namespace Viliy\SMS\Support;

use Viliy\SMS\Contracts\MessageInterface;

/**
 * Class Message
 * @package Viliy\SMS\Format
 */
class Message implements MessageInterface
{

    /**
     * @var array
     */
    protected $gateways = [];

    /**
     * @var string
     */
    protected $type;

    /**
     * @var string
     */
    protected $content;

    /**
     * @var string
     */
    protected $identifier;

    /**
     * @var array
     */
    protected $data = [];

    /**
     * Message constructor.
     * @param array $attributes
     */
    public function __construct(array $attributes = [])
    {
        foreach ($attributes as $property => $value) {
            if (property_exists($this, $property)) {
                $this->$property = $value;
            }
        }
    }

    /**
     * Return the message type.
     *
     * @return string
     */
    public function getMessageType()
    {
        is_null($this->type) && $this->type = self::TEXT_MESSAGE;

        return $this->type;
    }

    /**
     * @return mixed
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * @return mixed
     */
    public function getIdentifier()
    {
        return $this->identifier;
    }

    /**
     * @return array|mixed
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * @return mixed
     */
    public function getGateways()
    {
        return $this->gateways;
    }
}