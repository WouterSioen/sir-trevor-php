<?php

namespace Sioen;

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
final class JsonToHtml
{
    /** @var array */
    private $converters = array();

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
            $html .= $this->convert(new SirTrevorBlock($block['type'], $block['data']));
        }

        return $html;
    }


    /**
     * Converts on array to an html string
     *
     * @param SirTrevorBlock $block
     * @return string
     */
    private function convert(SirTrevorBlock $block)
    {
        foreach ($this->converters as $converter) {
            if ($converter->matches($block->getType())) {
                return $converter->toHtml($block->getData());
            }
        }
    }
}
