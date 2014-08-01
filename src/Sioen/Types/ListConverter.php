<?php

namespace Sioen\Types;

class ListConverter extends BaseConverter implements ConverterInterface
{
    public function toJson(\DOMElement $node)
    {
        $html = $node->ownerDocument->saveXML($node);
        $markdown = $this->htmlToMarkdown($html);

        // we need a space in the beginnen of each line
        $markdown = ' ' . str_replace("\n", "\n ", $markdown);

        return array(
            'type' => 'list',
            'data' => array(
                'text' => $markdown
            )
        );
    }
}
