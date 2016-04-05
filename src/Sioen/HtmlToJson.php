<?php

namespace Sioen;

use Sioen\Types\BlockquoteConverter;
use Sioen\Types\HeadingConverter;
use Sioen\Types\IframeConverter;
use Sioen\Types\ImageConverter;
use Sioen\Types\ListConverter;
use Sioen\Types\ParagraphConverter;
use Sioen\Types\BaseConverter;

/**
 * Class HtmlToJson
 *
 * Converts html to a json object that can be understood by Sir Trevor
 *
 * @version 1.1.0
 * @author Wouter Sioen <wouter@woutersioen.be>
 * @license http://www.opensource.org/licenses/mit-license.php MIT
 */
class HtmlToJson
{
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
                $data[] = $this->convert($node->nodeName, $node);
            }
        }

        return json_encode(array('data' => $data));
    }

    /**
     * Converts one node to json
     *
     * @param string $nodeName
     * @param \DOMElement $node
     * @return array
     */
    private function convert($nodeName, \DOMElement $node)
    {
        switch ($nodeName) {
            case 'p':
                $converter = new ParagraphConverter();
                break;
            case 'h2':
                $converter = new HeadingConverter();
                break;
            case 'ul':
                $converter = new ListConverter();
                break;
            case 'blockquote':
                $converter = new BlockquoteConverter();
                break;
            case 'iframe':
                $converter = new IframeConverter();
                break;
            case 'img':
                $converter = new ImageConverter();
                break;
            default:
                $converter = new BaseConverter();
                break;
        }

        return $converter->toJson($node);
    }
}
