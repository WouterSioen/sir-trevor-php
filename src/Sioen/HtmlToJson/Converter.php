<?php

namespace Sioen\HtmlToJson;

abstract class Converter
{
    /**
     * @param \DomElement $node
     * @return array
     */
    abstract public function toJson(\DOMElement $node);

    /**
     * @param \DomElement $node
     * @return boolean
     */
    abstract public function matches(\DOMElement $node);

    /**
     * @param string $html
     * @return string
     */
    protected function htmlToMarkdown($html)
    {
        $markdown = new \HTML_To_Markdown(
            $html,
            array(
                'header_style' => 'atx',
                'bold_style' => '__',
                'italic_style' => '_',
            )
        );

        return $markdown->output();
    }
}
