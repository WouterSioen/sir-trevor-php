<?php

namespace Sioen\JsonToHtml;

final class TasteConverter implements Converter
{
    public function toHtml(array $data)
    {
        $html = '<p><strong>Taste</strong></p>'.$data['text'];
        return $html;
    }

    public function matches($type)
    {
        return $type === 'taste';
    }
}
