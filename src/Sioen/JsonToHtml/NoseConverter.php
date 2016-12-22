<?php

namespace Sioen\JsonToHtml;

final class NoseConverter implements Converter
{
    public function toHtml(array $data)
    {
        $html = '<p><strong>Nose</strong></p>'.$data['text'];
        return $html;
    }

    public function matches($type)
    {
        return $type === 'nose';
    }
}
