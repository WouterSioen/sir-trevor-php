<?php

namespace Sioen\HtmlToJson;

final class ListConverter implements Converter
{
    use HtmlToMarkdown;

    public function toJson(\DOMElement $node)
    {
        $markdown = $this->htmlToMarkdown($node->ownerDocument->saveXML($node));

        // we need a space in the beginning of each line
        $markdown = ' ' . str_replace("\n", "\n ", $markdown);

        return array(
            'type' => 'list',
            'data' => array(
                'text' => $markdown,
            )
        );
    }

    public function matches(\DomElement $node)
    {
        return $node->nodeName === 'ul';
    }
}
