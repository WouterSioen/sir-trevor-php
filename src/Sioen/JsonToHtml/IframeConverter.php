<?php

namespace Sioen\JsonToHtml;

final class IframeConverter implements Converter
{
    public function toHtml(array $data)
    {
        // youtube video's
        $source = $data['source'];
        $remoteId = $data['remote_id'];

        if ($source == 'youtube') {
            $html = '<iframe src="//www.youtube.com/embed/' . $remoteId . '?rel=0" ';
            $html .= 'frameborder="0" allowfullscreen></iframe>' . "\n";

            return $html;
        }

        // vimeo videos
        if ($source == 'vimeo') {
            $html = '<iframe src="//player.vimeo.com/video/' . $remoteId;
            $html .= '?title=0&amp;byline=0" frameborder="0"></iframe>' . "\n";

            return $html;
        }

        // fallback
        return '';
    }

    public function matches($type)
    {
        return $type === 'video';
    }
}
