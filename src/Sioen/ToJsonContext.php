<?php

namespace Sioen;

use Sioen\Types\BlockquoteConverter;
use Sioen\Types\HeadingConverter;
use Sioen\Types\IframeConverter;
use Sioen\Types\ImageConverter;
use Sioen\Types\ListConverter;
use Sioen\Types\ParagraphConverter;
use Sioen\Types\BaseConverter;

class ToJsonContext
{
    protected $converter = null;

    public function __construct($nodeName, $options)
    {
        switch ($nodeName) {
            case 'p':
                $this->converter = new ParagraphConverter($options);
                break;
            case 'h2':
                $this->converter = new HeadingConverter($options);
                break;
            case 'ul':
                $this->converter = new ListConverter($options);
                break;
            case 'blockquote':
                $this->converter = new BlockquoteConverter($options);
                break;
            case 'iframe':
                $this->converter = new IframeConverter($options);
                break;
            case 'img':
                $this->converter = new ImageConverter($options);
                break;
            default:
                $this->converter = new BaseConverter($options);
                break;
        }
    }

    public function getData(\DOMElement $node)
    {
        return $this->converter->toJson($node);
    }
}
