<?php

namespace Sioen\JsonToHtml;

final class YoutubeConverter implements Converter
{
    public function toHtml(array $data)
    {
        return sprintf(
            '<iframe src="//www.youtube.com/embed/%s?rel=0" frameborder="0" allowfullscreen></iframe>',
            $data['remote_id']
        );
    }

    public function matches($type)
    {
        return $type === 'youtube';
    }
}
