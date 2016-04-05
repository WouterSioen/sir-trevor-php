<?php

namespace Sioen\HtmlToJson;

use Sioen\SirTrevorBlock;

final class ImageConverter implements Converter
{
    use HtmlToMarkdown;

    public function toJson(\DOMElement $node)
    {
        return new SirTrevorBlock(
            'image',
            array(
                'file' => array('url' => $node->getAttribute('src')),
            )
        );
    }

    public function matches(\DomElement $node)
    {
        return $node->nodeName === 'img';
    }
}
