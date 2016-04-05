<?php

namespace Sioen\JsonToHtml;

final class ImageConverter implements Converter
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
