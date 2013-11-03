<?php

require_once 'vendor/autoload.php';

use \Michelf\Markdown;

/**
 * Class Converter
 *
 * A Sir Trevor to HTML conversion helper for PHP
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
     * @return string
     */
    public function toHtml($json)
    {

    }

    /**
     * Converts default elements to html
     *
     * @param string $text
     * @return string
     */
    public function defaultToHtml($text)
    {
        return Markdown::defaultTransform($text);
    }

    /**
     * Converts headers to html
     *
     * @param string $text
     * @return string
     */
    public function headerToHtml($text)
    {
        return Markdown::defaultTransform('## ' . $text);
    }

    /**
     * Converts quotes to html
     * 
     * @param string $text
     * @param string $cite
     * @return string
     */
    public function quoteToHtml($text, $cite = null)
    {
        $html = '<blockquote>';
        $html .= Markdown::defaultTransform($text);

        // Add the cit if necessary
        if(!empty($cite))
        {
            $html .= '<cite>';
            $html .= Markdown::defaultTransform($cite);
            $html .= '</cite>';
        }

        $html .= '</blockquote>';
        return $html;
    }
}