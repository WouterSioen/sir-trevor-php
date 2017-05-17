<?php

namespace Sioen\JsonToHtml;

final class FinishConverter implements Converter
{
    public function toHtml(array $data)
    {
        $html = '<p><strong>Finish</strong></p>'.$data['text'];
        return $html;
    }

    public function matches($type)
    {
        return $type === 'finish';
    }
}
