<?php

namespace Sioen\HtmlToJson;

final class IframeConverter implements Converter
{
    use HtmlToMarkdown;

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

    public function matches(\DomElement $node)
    {
        return $node->nodeName === 'iframe';
    }
}
