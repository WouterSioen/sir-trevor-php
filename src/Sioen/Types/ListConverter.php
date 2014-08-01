<?php

namespace Sioen\Types;

use \Michelf\Markdown;

class ParagraphConverter extends BaseConverter implements ConverterInterface
{
    public function toJson(\DOMElement $node)
    {
        $html = $node->ownerDocument->saveXML($node);
        $markdown = new \HTML_To_Markdown($html, $this->options);
        $markdown = $markdown->output();

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
