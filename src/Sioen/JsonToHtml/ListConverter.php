<?php

namespace Sioen\JsonToHtml;

final class ListConverter implements Converter
{
    public function toHtml(array $data)
    {
        $html = '<ul>';
        foreach ($data['listItems'] as $listItem) {
            $html .= '<li>'.$listItem['content'].'</li>';
        }
        $html .= '</ul>';
        return $html;
    }

    public function matches($type)
    {
        return $type === 'list';
    }
}
