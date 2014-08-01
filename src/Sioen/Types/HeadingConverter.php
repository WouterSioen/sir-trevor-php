<?php

namespace Sioen\Types;

use \Michelf\Markdown;

class HeadingConverter extends BaseConverter implements ConverterInterface
{
    public function toJson(\DOMElement $node)
    {
        $html = $node->ownerDocument->saveXML($node);

        // remove the h2 tags from the text. We just need the inner text.
        $html = preg_replace('/<(\/|)h2>/i', '', $html);
        $markdown = new \HTML_To_Markdown($html, $this->options);
        $markdown = ' ' . $markdown->output();

        return array(
            'type' => 'heading',
            'data' => array(
                'text' => $markdown
            )
        );
    }

    public function toHtml(array $data)
    {
        return Markdown::defaultTransform('## ' . $data['text']);
    }
}
