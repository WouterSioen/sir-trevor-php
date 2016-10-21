<?php

namespace Sioen\HtmlToJson;

use Sioen\SirTrevorBlock;

final class ListConverter implements Converter
{
    use HtmlToMarkdown;

    public function toJson(\DOMElement $node)
    {
        $list = $node->ownerDocument->saveXML($node);
        $list = str_replace('<ul>', '', $list);
        $list = str_replace('</ul>', '', $list);
        $list = str_replace('</li>', '', $list);
        $array = explode("<li>", $list);

        $listItems = [];
        foreach($array as $key => $item){
            if(!empty($item)){
                $object = (object) [
                    'content' => $item
                ];
                array_push($listItems,$object);
            }
        }

        return new SirTrevorBlock(
            'list',
            array("format"=> "html", 'listItems' => $listItems)
        );
    }

    public function matches(\DomElement $node)
    {
        return $node->nodeName === 'ul';
    }
}
