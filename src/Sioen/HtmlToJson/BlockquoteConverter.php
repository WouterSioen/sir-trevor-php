<?php

namespace Sioen\HtmlToJson;

use Sioen\SirTrevorBlock;

final class BlockquoteConverter implements Converter
{
    use HtmlToMarkdown;

    public function toJson(\DOMElement $node)
    {
        $cite = $this->getCiteHtml($node);

        // we use the remaining html to create the remaining text
        $html = $node->ownerDocument->saveXML($node);
        $html = preg_replace('/<(\/|)blockquote>/i', '', $html);

        return new SirTrevorBlock(
            'quote',
            array(
                'text' => ' ' . $this->htmlToMarkdown($html),
                'cite' => $cite,
            )
        );
    }

    public function matches(\DomElement $node)
    {
        return $node->nodeName === 'blockquote';
    }

    private function getCiteHtml(\DOMElement $node)
    {
        $cite = '';

        foreach ($this->getCiteNodes($node) as $child) {
            $html = $child->ownerDocument->saveXML($child);
            $html = preg_replace('/<(\/|)cite>/i', '', $html);
            $child->parentNode->removeChild($child);
            $cite = ' ' . $this->htmlToMarkdown($html);
        }

        return $cite;
    }

    private function getCiteNodes(\DOMElement $node)
    {
        return array_filter(
            iterator_to_array($node->childNodes),
            function (\DOMElement $childNode) {
                return $childNode->nodeName == 'cite';
            }
        );
    }
}
