<?php

namespace Sioen;

use Sioen\Types\BlockquoteConverter;
use Sioen\Types\HeadingConverter;
use Sioen\Types\IframeConverter;
use Sioen\Types\ImageConverter;
use Sioen\Types\ListConverter;
use Sioen\Types\BaseConverter;

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
            $html .= $this->convert($block['type'], $block['data']);
        }

        return $html;
    }


    /**
     * Converts on array to an html string
     *
     * @param string $type
     * @param array $data
     * @return string
     */
    private function convert($type, array $data)
    {
        switch ($type) {
            case 'heading':
                $converter = new HeadingConverter();
                break;
            case 'list':
                $converter = new ListConverter();
                break;
            case 'quote':
                $converter = new BlockquoteConverter();
                break;
            case 'video':
                $converter = new IframeConverter();
                break;
            case 'image':
                $converter = new ImageConverter();
                break;
            default:
                $converter = new BaseConverter();
                break;
        }

        return $converter->toHtml($data);
    }
}
