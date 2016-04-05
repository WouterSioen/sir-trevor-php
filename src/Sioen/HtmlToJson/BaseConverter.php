<?php

namespace Sioen\HtmlToJson;

use Sioen\SirTrevorBlock;

final class BaseConverter implements Converter
{
    use HtmlToMarkdown;

    public function toJson(\DOMElement $node)
    {
        $html = $node->ownerDocument->saveXML($node);

        return new SirTrevorBlock(
            'text',
            array('text' => ' ' . $this->htmlToMarkdown($html))
        );
    }

    public function matches(\DomElement $node)
    {
        return true;
    }
}
