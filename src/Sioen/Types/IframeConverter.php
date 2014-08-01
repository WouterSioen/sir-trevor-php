<?php

namespace Sioen\Types;

use \Michelf\Markdown;

class IframeConverter extends BaseConverter implements ConverterInterface
{
    public function toJson(\DOMElement $node)
    {
        $html = $node->ownerDocument->saveXML($node);

        // youtube or vimeo
        if (preg_match('~//www.youtube.com/embed/([^/\?]+).*\"~si', $html, $matches)) {
            return array(
                'type' => 'video',
                'data' => array(
                    'source' => 'youtube',
                    'remote_id' => $matches[1]
                )
            );
        } elseif (preg_match('~//player.vimeo.com/video/([^/\?]+).*\?~si', $html, $matches)) {
            return array(
                'type' => 'video',
                'data' => array(
                    'source' => 'vimeo',
                    'remote_id' => $matches[1]
                )
            );
        }
    }

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
}
