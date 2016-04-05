<?php

namespace Sioen\JsonToHtml;

interface Converter
{
    /**
     * @param array $data
     * @return string
     */
    public function toHtml(array $data);

    /**
     * @param string $type
     * @return bool
     */
    public function matches($type);
}
