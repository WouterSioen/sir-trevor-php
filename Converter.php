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
            else
            {
                throw new Exception('Can\t convert type ' . $block['type'] . '.');
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
     * Parameters are send as an array fetched from the json data
     * 
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

    /**
     * Converts html to the json Sir Trevor requires
     * 
     * @param string $html
     * @return string The json string
     */
    public function toJson($html)
    {
        // Strip white space between tags to prevent creation of empty #text nodes
        $html = preg_replace('~>\s+<~', '><', $html);
        $this->document = new DOMDocument();

        // Load UTF-8 HTML hack (from http://bit.ly/pVDyCt)
        $this->document->loadHTML('<?xml encoding="UTF-8">' . $html);
        $this->document->encoding = 'UTF-8';

        // fetch the body of the document. All html is stored in there
        $body = $this->document->getElementsByTagName("body")->item(0);

        $data = array();

        // loop trough the child nodes and convert them
        foreach($body->childNodes as $node)
        {
            $html = $node->ownerDocument->saveXML($node);
            switch($node->nodeName)
            {
                case 'p':
                    $data[] = $this->paragraphToJson($html);
                    break;
                case 'h2':
                    $data[] = $this->headingToJson($html);
                    break;
                case 'ul':
                    $data[] = $this->listToJson($html);
                    break;
                default:
                    break;
            }
        }

        return json_encode(array('data' => $data));
    }

    /**
     * Converts headings to the json format
     * 
     * @param $text
     * @return array
     */
    public function headingToJson($html)
    {
        // remove the h2 tags from the text. We just need the inner text.
        $html = preg_replace('/<(\/|)h2>/i', '', $html);
        $markdown = new HTML_To_Markdown($html);
        $markdown = ' ' . $markdown->output();

        return array(
            'type' => 'heading',
            'data' => array(
                'text' => $markdown
            )
        );
    }

    /**
     * Converts lists to the json format
     * 
     * @param $text
     * @return array
     */
    public function listToJson($html)
    {
        $markdown = new HTML_To_Markdown($html);
        $markdown = $markdown->output();

        // we need a space in the beginnen of each line
        $markdown = ' ' . str_replace("\n", "\n ", $markdown);

        return array(
            'type' => 'list',
            'data' => array(
                'text' => $markdown
            )
        );
    }

    /**
     * Converts paragraphs to the json format
     * 
     * @param $text
     * @return array
     */
    public function paragraphToJson($html)
    {
        // convert the html to markdown. That's all we need
        $markdown = new HTML_To_Markdown($html);
        $markdown = ' ' . $markdown->output();

        return array(
            'type' => 'text',
            'data' => array(
                'text' => $markdown
            )
        );
    }
}