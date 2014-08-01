<?php

namespace Sioen\Types;

use \Michelf\Markdown;

class BlockquoteConverter extends BaseConverter implements ConverterInterface
{
    public function toJson(\DOMElement $node)
    {
        // check if the quote contains a cite
        $cite = '';

        foreach ($node->childNodes as $child) {
            // if it contains a 'cite' node, we should add it in the cite property
            if ($child->nodeName == 'cite') {
                $html = $child->ownerDocument->saveXML($child);
                $html = preg_replace('/<(\/|)cite>/i', '', $html);
                $child->parentNode->removeChild($child);
                $cite = new \HTML_To_Markdown($html, $this->options);
                $cite = ' ' . $cite->output();
            }
        }

        // we use the remaining html to create the remaining text
        $html = $node->ownerDocument->saveXML($node);
        $html = preg_replace('/<(\/|)blockquote>/i', '', $html);
        $markdown = new \HTML_To_Markdown($html, $this->options);
        $markdown = ' ' . $markdown->output();

        return array(
            'type' => 'quote',
            'data' => array(
                'text' => $markdown,
                'cite' => $cite
            )
        );
    }

    public function toHtml(array $data)
    {
        $text = $data['text'];
        $html = '<blockquote>';
        $html .= Markdown::defaultTransform($text);

        // Add the cite if necessary
        if (isset($data['cite']) && !empty($data['cite'])) {
            // remove the indent thats added by Sir Trevor
            $cite = ltrim($data['cite'], '>');
            $html .= '<cite>' . Markdown::defaultTransform($cite) . '</cite>';
        }

        $html .= '</blockquote>';

        return $html;
    }
}
