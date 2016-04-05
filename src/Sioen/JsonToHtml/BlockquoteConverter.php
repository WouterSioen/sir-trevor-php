<?php

namespace Sioen\JsonToHtml;

use \Michelf\Markdown;

final class BlockquoteConverter implements Converter
{
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

    public function matches($type)
    {
        return $type === 'quote';
    }
}
