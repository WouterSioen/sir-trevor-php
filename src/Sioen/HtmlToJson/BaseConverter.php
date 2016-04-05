<?php

namespace Sioen\HtmlToJson;

class BaseConverter extends Converter
{
    public function toJson(\DOMElement $node)
    {
        $html = $node->ownerDocument->saveXML($node);

        return array(
            'type' => 'text',
            'data' => array(
                'text' => ' ' . $this->htmlToMarkdown($html)
            )
        );
    }

    public function matches(\DomElement $node)
    {
        return true;
    }
}
