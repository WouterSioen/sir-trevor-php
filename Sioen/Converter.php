<?php

namespace Sioen;

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
     * The options we use for html to markdown
     *
     * @var array
     */
    private $options = array(
        'header_style' => 'atx',
        'bold_style' => '__',
        'italic_style' => '_',
    );

    /**
     * We need our vendors to converter markdown to html and html to markdown
     */
    public function __construct()
    {
        require_once 'vendor/autoload.php';
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
            // check if we have a converter for this type
            $converter = $block['type'] . 'ToHtml';
            if (is_callable(array($this, $converter))) {
                // call the function and add the data as parameters
                $html .= call_user_func_array(
                    array($this, $converter),
                    $block['data']
                );
            } elseif (array_key_exists('text', $block['data'])) {
                // we have a text block. Let's just try the default converter
                $html .= $this->defaultToHtml($block['data']['text']);
            } else {
                throw new Exception('Can\t convert type ' . $block['type'] . '.');
            }
        }

        return $html;
    }

    /**
     * Converts default elements to html
     *
     * @param  string $text
     * @return string
     */
    public function defaultToHtml($text)
    {
        return Markdown::defaultTransform($text);
    }

    /**
     * Converts video block to html
     *
     * @param  string $source
     * @param  string $remoteId
     * @return string
     */
    public function videoToHtml($source, $remoteId)
    {
        // youtube video's
        if ($source == 'youtube') {
            $html = '<iframe src="//www.youtube.com/embed/' . $remoteId . '?rel=0" ';
            $html .= 'frameborder="0" allowfullscreen></iframe>' . "\n";

            return $html;
        }

        // vimeo videos
        if ($source == 'vimeo') {
            $html = '<iframe src="//player.vimeo.com/video/' . $remoteId;
            $html .= '?title=0&amp;byline=0" frameborder="0"></iframe>' . "\n";

            return $html;
        }

        // fallback
        return '';
    }

    /**
     * Converts headers to html
     *
     * @param  string $text
     * @return string
     */
    public function headingToHtml($text)
    {
        return Markdown::defaultTransform('## ' . $text);
    }

    /**
     * Converts quotes to html
     *
     * @param  string           $text
     * @param  string[optional] $cite
     * @return string
     */
    public function quoteToHtml($text, $cite = null)
    {
        $html = '<blockquote>';
        $html .= Markdown::defaultTransform($text);

        // Add the cite if necessary
        if (!empty($cite)) {
            // remove the indent thats added by Sir Trevor
            $cite = ltrim($cite, '>');
            $html .= '<cite>' . Markdown::defaultTransform($cite) . '</cite>';
        }

        $html .= '</blockquote>';

        return $html;
    }

    /**
     * Converts the image to html
     *
     * @param  array  $file
     * @return string
     */
    public function imageToHtml($file)
    {
        return '<img src="' . $file['url'] . '" />' . "\n";
    }

    /**
     * Converts html to the json Sir Trevor requires
     *
     * @param  string $html
     * @return string The json string
     */
    public function toJson($html)
    {
        // Strip white space between tags to prevent creation of empty #text nodes
        $html = preg_replace('~>\s+<~', '><', $html);
        $this->document = new \DOMDocument();

        // Load UTF-8 HTML hack (from http://bit.ly/pVDyCt)
        $this->document->loadHTML('<?xml encoding="UTF-8">' . $html);
        $this->document->encoding = 'UTF-8';

        // fetch the body of the document. All html is stored in there
        $body = $this->document->getElementsByTagName("body")->item(0);

        $data = array();

        // loop trough the child nodes and convert them
        if ($body) {
            foreach ($body->childNodes as $node) {
                $html = $node->ownerDocument->saveXML($node);
                switch ($node->nodeName) {
                    case 'p':
                        $data[] = $this->paragraphToJson($html);
                        break;
                    case 'h2':
                        $data[] = $this->headingToJson($html);
                        break;
                    case 'ul':
                        $data[] = $this->listToJson($html);
                        break;
                    case 'blockquote':
                        $data[] = $this->quoteToJson($node);
                        break;
                    case 'iframe':
                        $data[] = $this->iframeToJson($html);
                        break;
                    case 'img':
                        $src = $node->getAttribute('src');
                        $data[] = $this->imageToJson($src);
                        break;
                    default:
                        break;
                }
            }
        }

        return json_encode(array('data' => $data));
    }

    /**
     * Converts headings to the json format
     *
     * @param $html
     * @return array
     */
    public function headingToJson($html)
    {
        // remove the h2 tags from the text. We just need the inner text.
        $html = preg_replace('/<(\/|)h2>/i', '', $html);
        $markdown = new \HTML_To_Markdown($html, $this->options);
        $markdown = ' ' . $markdown->output();

        return array(
            'type' => 'heading',
            'data' => array(
                'text' => $markdown
            )
        );
    }

    /**
     * Converts an iframe to the array Embedly needs
     *
     * @param $html
     * @return array
     */
    public function iframeToJson($html)
    {
        // youtube or vimeo
        if (preg_match('~//www.youtube.com/embed/([^/\?]+).*\"~si', $html, $matches)) {
            return array(
                'type' => 'video',
                'data' => array(
                    'source' => 'youtube',
                    'remote_id' => $matches[1]
                )
            );
        } elseif (preg_match('~//player.vimeo.com/video/([^/\?]+).*\?~si', $html, $matches)) {
            return array(
                'type' => 'video',
                'data' => array(
                    'source' => 'vimeo',
                    'remote_id' => $matches[1]
                )
            );
        }
    }

    /**
     * Converts lists to the json format
     *
     * @param $html
     * @return array
     */
    public function listToJson($html)
    {
        $markdown = new \HTML_To_Markdown($html, $this->options);
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
     * @param $html
     * @return array
     */
    public function paragraphToJson($html)
    {
        // convert the html to markdown. That's all we need
        $markdown = new \HTML_To_Markdown($html, $this->options);
        $markdown = ' ' . $markdown->output();

        return array(
            'type' => 'text',
            'data' => array(
                'text' => $markdown
            )
        );
    }

    /**
     * Converts quotes to the json format
     *
     * @param $node The node is send to check if it contains a cite
     * @return array
     */
    public function quoteToJson($node)
    {
        // check if the quote contains a cite
        $cite = '';

        foreach ($node->childNodes as $child) {
            // if it contains a 'cite' node, we should add it in the cite property
            if ($child->nodeName == 'cite') {
                $html = $child->ownerDocument->saveXML($child);
                $html = preg_replace('/<(\/|)cite>/i', '', $html);
                $child->parentNode->removeChild($child);
                $cite = new \HTML_To_Markdown($html, $this->options);
                $cite = ' ' . $cite->output();
            }
        }

        // we use the remaining html to create the remaining text
        $html = $node->ownerDocument->saveXML($node);
        $html = preg_replace('/<(\/|)blockquote>/i', '', $html);
        $markdown = new \HTML_To_Markdown($html, $this->options);
        $markdown = ' ' . $markdown->output();

        return array(
            'type' => 'quote',
            'data' => array(
                'text' => $markdown,
                'cite' => $cite
            )
        );
    }

    /**
     * Converts images to html
     *
     * @param  string $url
     * @return array
     */
    public static function imageToJson($url)
    {
        return array(
            'type' => 'image',
            'data' => array(
                'file' => array(
                    'url' => $url
                )
            )
        );
    }
}
