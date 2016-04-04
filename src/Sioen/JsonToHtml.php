<?php

namespace Sioen;

/**
 * Class JsonToHtml
 *
 * Converts a json object received from Sir Trevor to an html representation
 *
 * @version 1.1.0
 * @author Wouter Sioen <wouter@woutersioen.be>
 * @license http://www.opensource.org/licenses/mit-license.php MIT
 */
class JsonToHtml
{
    /**
     * Converts the outputted json from Sir Trevor to html
     *
     * @param  string $json
     * @return string
     */
    public function toHtml($json)
    {
        // convert the json to an associative array
        $input = json_decode($json, true);
        $html = '';

        // loop trough the data blocks
        foreach ($input['data'] as $block) {
            $toHtmlContext = new ToHtmlContext($block['type']);
            $html .= $toHtmlContext->getHtml($block['data']);
        }

        return $html;
    }
}
