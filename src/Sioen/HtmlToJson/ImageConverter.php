<?php

namespace Sioen\HtmlToJson;

class ImageConverter extends Converter
{
    public function toJson(\DOMElement $node)
    {
        return array(
            'type' => 'image',
            'data' => array(
                'file' => array(
                    'url' => $node->getAttribute('src')
                )
            )
        );
    }

    public function matches(\DomElement $node)
    {
        return $node->nodeName === 'img';
    }
}
