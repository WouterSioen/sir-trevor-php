<?php

namespace Sioen\JsonToHtml;

use \Michelf\Markdown;

class HeadingConverter implements Converter
{
    public function toHtml(array $data)
    {
        return Markdown::defaultTransform('## ' . $data['text']);
    }

    public function matches($type)
    {
        return $type === 'heading';
    }
}
