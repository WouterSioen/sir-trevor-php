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
        // convert the json to an associative array
        $input = json_decode($json, true);
        $html = '';

        // loop trough the data blocks
        foreach($input['data'] as $block)
        {
            // check if we have a converter for this type
            $converter = $block['type'] . 'ToHtml';
            if(is_callable(array(__CLASS__, $converter)))
            {
                // call the function and add the data as parameters
                $html .= call_user_func_array(
                    array(__CLASS__, $converter),
                    $block['data']
                );
            }
            elseif(array_key_exists('text', $block['data']))
            {
                // we have a text block. Let's just try the default converter
                $html .= $this->defaultToHtml($block['data']['text']);
            }
        }

        return $html;
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
     * Converts embedly block to html
     * 
     * @param string $text
     * @return string
     */
    public function embedlyToHtml()
    {
        // we know this is the 8th argument. This is a bit nasty, but should
        // do the trick for now.
        $arguments = func_get_args();
        return $arguments[8] . "\n";
    }

    /**
     * Converts headers to html
     *
     * @param string $text
     * @return string
     */
    public function headingToHtml($text)
    {
        return Markdown::defaultTransform('## ' . $text);
    }

    /**
     * Converts quotes to html
     * 
     * @param string $text
     * @param string[optional] $cite
     * @return string
     */
    public function quoteToHtml($text, $cite = null)
    {
        $html = '<blockquote>';
        $html .= Markdown::defaultTransform($text);

        // Add the cit if necessary
        if(!empty($cite))
        {
            $html .= '<cite>' . Markdown::defaultTransform($cite) . '</cite>';
        }

        $html .= '</blockquote>';
        return $html;
    }
}