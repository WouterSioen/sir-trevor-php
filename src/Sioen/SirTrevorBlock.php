<?php

namespace Sioen;

/**
 * Class SirTrevorBlock
 */
final class SirTrevorBlock implements \JsonSerializable
{
    /** @var string */
    private $type;

    /** @var array */
    private $data = array();

    /**
     * @param string $type
     * @param array $data
     */
    public function __construct($type, array $data)
    {
        $this->type = $type;
        $this->data = $data;
    }

    /**
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @return array
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * @return array
     */
    public function jsonSerialize()
    {
        return array(
            'type' => $this->type,
            'data' => $this->data,
        );
    }
}
