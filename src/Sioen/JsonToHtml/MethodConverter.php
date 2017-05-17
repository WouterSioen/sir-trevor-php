<?php

namespace Sioen\JsonToHtml;

final class MethodConverter implements Converter
{
    public function toHtml(array $data)
    {
        $html = '<p itemprop="recipeInstructions"><strong>How to Mix</strong></p>';
        $html .= '<ul>';
        foreach ($data['listItems'] as $listItem) {
            $html .= '<li>'.$listItem['content'].'</li>';
        }
        $html .= '</ul>';
        return $html;
    }

    public function matches($type)
    {
        return $type === 'method';
    }
}
