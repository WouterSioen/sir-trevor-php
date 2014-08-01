<?php

namespace Sioen\Types;

use \Michelf\Markdown;

class BaseConverter implements ConverterInterface
{
    /**
     * The options we use for html to markdown
     *
     * @var array
     */
    protected $options = array(
        'header_style' => 'atx',
        'bold_style' => '__',
        'italic_style' => '_',
    );

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
