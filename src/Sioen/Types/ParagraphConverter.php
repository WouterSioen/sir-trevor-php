<?php

namespace Sioen\Types;

class ParagraphConverter extends BaseConverter implements ConverterInterface
{
    public function toJson(\DOMElement $node)
    {
        $html = $node->ownerDocument->saveXML($node);
        $markdown = new \HTML_To_Markdown($html, $this->options);
        $markdown = ' ' . $markdown->output();

        return array(
            'type' => 'text',
            'data' => array(
                'text' => $markdown
            )
        );
    }
}
