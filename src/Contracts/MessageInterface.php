<?php

namespace Viliy\SMS\Contracts;

/**
 * Interface MessageInterface
 * @package Viliy\SMS\Contracts
 */
interface MessageInterface
{
    const TEXT_MESSAGE = 'text';

    const VOICE_MESSAGE = 'voice';

    /**
     * Return the message type.
     *
     * @return string
     */
    public function getMessageType();

    /**
     * @return mixed
     */
    public function getContent();

    /**
     * @return mixed
     */
    public function getIdentifier();

    /**
     * @return mixed
     */
    public function getData();

    /**
     * @return mixed
     */
    public function getGateways();
}
