<?php

namespace Sioen\HtmlToJson;

final class ImageConverter implements Converter
{
    use HtmlToMarkdown;

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
