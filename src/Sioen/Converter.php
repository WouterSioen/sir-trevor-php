<?php

namespace Sioen;

use Exception;
use \Michelf\Markdown;
use Sioen\Types\BlockquoteConverter;
use Sioen\Types\HeadingConverter;
use Sioen\Types\IframeConverter;
use Sioen\Types\ImageConverter;
use Sioen\Types\ListConverter;
use Sioen\Types\ParagraphConverter;
use Sioen\Types\BaseConverter;

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
            $converter = new BaseConverter($this->options);
            switch ($block['type']) {
                case 'heading':
                    $converter = new HeadingConverter($this->options);
                    break;
                case 'list':
                    $converter = new ListConverter($this->options);
                    break;
                case 'quote':
                    $converter = new BlockquoteConverter($this->options);
                    break;
                case 'video':
                    $converter = new IframeConverter($this->options);
                    break;
                case 'image':
                    $converter = new ImageConverter($this->options);
                    break;
                default:
                    break;
            }

            $html .= $converter->toHtml($block['data']);
        }

        return $html;
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
        $document = new \DOMDocument();

        // Load UTF-8 HTML hack (from http://bit.ly/pVDyCt)
        $document->loadHTML('<?xml encoding="UTF-8">' . $html);
        $document->encoding = 'UTF-8';

        // fetch the body of the document. All html is stored in there
        $body = $document->getElementsByTagName("body")->item(0);

        $data = array();

        // loop trough the child nodes and convert them
        if ($body) {
            foreach ($body->childNodes as $node) {
                $converter = new BaseConverter($this->options);
                switch ($node->nodeName) {
                    case 'p':
                        $converter = new ParagraphConverter($this->options);
                        break;
                    case 'h2':
                        $converter = new HeadingConverter($this->options);
                        break;
                    case 'ul':
                        $converter = new ListConverter($this->options);
                        break;
                    case 'blockquote':
                        $converter = new BlockquoteConverter($this->options);
                        break;
                    case 'iframe':
                        $converter = new IframeConverter($this->options);
                        break;
                    case 'img':
                        $converter = new ImageConverter($this->options);
                        break;
                    default:
                        break;
                }
                $data[] = $converter->toJson($node);
            }
        }

        return json_encode(array('data' => $data));
    }
}
