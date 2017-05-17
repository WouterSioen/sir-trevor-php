<?php

namespace Sioen\JsonToHtml;

final class ColourConverter implements Converter
{
    public function toHtml(array $data)
    {
        $html = '<p><strong>Colour</strong></p>'.$data['text'];
        return $html;
    }

    public function matches($type)
    {
        return $type === 'colour';
    }
}
