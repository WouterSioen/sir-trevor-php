<?php

namespace Sioen\JsonToHtml;

class ImageConverter implements Converter
{
    public function toHtml(array $data)
    {
        return '<img src="' . $data['file']['url'] . '" />' . "\n";
    }

    public function matches($type)
    {
        return $type === 'image';
    }
}
