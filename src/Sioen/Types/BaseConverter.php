<?php

namespace Sioen\Types;

use \Michelf\Markdown;

class BaseConverter implements ConverterInterface
{
    protected $options;

    public function __construct(array $options)
    {
        $this->options = $options;
    }

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

    public function toHtml(array $data)
    {
        return Markdown::defaultTransform($data['text']);
    }
}
