<?php

namespace Sioen;

use Sioen\JsonToHtml\BlockquoteConverter;
use Sioen\JsonToHtml\HeadingConverter;
use Sioen\JsonToHtml\IframeConverter;
use Sioen\JsonToHtml\ImageConverter;
use Sioen\JsonToHtml\BaseConverter;
use Sioen\JsonToHtml\Converter;

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
    /** @var array */
    private $converters;

    public function __construct()
    {
        $this->addConverter(new HeadingConverter());
        $this->addConverter(new BlockquoteConverter());
        $this->addConverter(new IframeConverter());
        $this->addConverter(new ImageConverter());
        $this->addConverter(new BaseConverter());
    }

    public function addConverter(Converter $converter)
    {
        $this->converters[] = $converter;
    }

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
        foreach ($this->converters as $converter) {
            if ($converter->matches($type)) {
                return $converter->toHtml($data);
            }
        }
    }
}
