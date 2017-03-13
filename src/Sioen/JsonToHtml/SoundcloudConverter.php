<?php

namespace Sioen\JsonToHtml;

final class SoundcloudConverter implements Converter
{
    public function toHtml(array $data)
    {
        return $data['html'];
    }

    public function matches($type)
    {
        return $type === 'soundcloud';
    }
}
