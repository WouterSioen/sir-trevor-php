<?php

require_once 'vendor/autoload.php';

use \Michelf\Markdown;

/**
 * Class Converter
 *
 * An Sir Trevor to HTML conversion helper for PHP
 *
 * @version 1.0.0
 * @author Wouter Sioen <info@woutersioen.be>
 * @license http://www.opensource.org/licenses/mit-license.php MIT
 */
class Converter
{
    /**
     * Converts the outputted json from Sir Trevor to html
     * 
     * @param string $json
     * @return string html
     */
    public function toHtml($json)
    {

    }

    /**
     * Converts default elements to html
     *
     * @param string $text
     * @return string $html
     */
    public function defaultToHtml($text)
    {
        return Markdown::defaultTransform($text);
    }

    /**
     * Converts headers to html
     *
     * @param string $text
     * @return string $html
     */
    public function headerToHtml($text)
    {
        return Markdown::defaultTransform('## ' . $text);
    }
}