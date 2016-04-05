<?php

namespace Sioen\HtmlToJson;

trait HtmlToMarkdown
{
    /**
     * @param string $html
     * @return string
     */
    protected function htmlToMarkdown($html)
    {
        $markdown = new \HTML_To_Markdown(
            $html,
            array(
                'header_style' => 'atx',
                'bold_style' => '__',
                'italic_style' => '_',
            )
        );

        return $markdown->output();
    }
}
