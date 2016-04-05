<?php

namespace Sioen\HtmlToJson;

class HeadingConverter extends Converter
{
    public function toJson(\DOMElement $node)
    {
        $html = $node->ownerDocument->saveXML($node);

        // remove the h2 tags from the text. We just need the inner text.
        $html = preg_replace('/<(\/|)h2>/i', '', $html);

        return array(
            'type' => 'heading',
            'data' => array(
                'text' => ' ' . $this->htmlToMarkdown($html)
            )
        );
    }

    public function matches(\DomElement $node)
    {
        return $node->nodeName === 'h2';
    }
}
