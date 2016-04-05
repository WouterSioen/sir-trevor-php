<?php

namespace Sioen\HtmlToJson;

interface Converter
{
    /**
     * @param \DomElement $node
     * @return array
     */
    public function toJson(\DOMElement $node);

    /**
     * @param \DomElement $node
     * @return boolean
     */
    public function matches(\DOMElement $node);
}
