<?php

namespace Sioen\HtmlToJson;

use Sioen\SirTrevorBlock;

interface Converter
{
    /**
     * @param \DomElement $node
     * @return SirTrevorBlock
     */
    public function toJson(\DOMElement $node);

    /**
     * @param \DomElement $node
     * @return boolean
     */
    public function matches(\DOMElement $node);
}
