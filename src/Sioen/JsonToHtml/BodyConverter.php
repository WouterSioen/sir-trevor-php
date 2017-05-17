<?php

namespace Sioen\JsonToHtml;

final class BodyConverter implements Converter
{
    public function toHtml(array $data)
    {
        $html = '<p><strong>Body</strong></p>'.$data['text'];
        return $html;
    }

    public function matches($type)
    {
        return $type === 'body';
    }
}
