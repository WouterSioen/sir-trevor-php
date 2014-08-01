<?php

namespace Sioen\Types;

use \Michelf\Markdown;

class HeadingConverter extends BaseConverter implements ConverterInterface
{
    public function toJson(\DOMElement $node)
    {
        // remove the h2 tags from the text. We just need the inner text.
        $html = $node->ownerDocument->saveXML($node);
        $html = preg_replace('/<(\/|)h2>/i', '', $html);

        return array(
            'type' => 'heading',
            'data' => array(
                'text' => ' ' . $this->htmlToMarkdown($html)
            )
        );
    }

    public function toHtml(array $data)
    {
        return Markdown::defaultTransform('## ' . $data['text']);
    }
}
