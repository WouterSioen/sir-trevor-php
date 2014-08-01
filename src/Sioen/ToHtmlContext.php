<?php

namespace Sioen;

use Sioen\Types\BlockquoteConverter;
use Sioen\Types\HeadingConverter;
use Sioen\Types\IframeConverter;
use Sioen\Types\ImageConverter;
use Sioen\Types\ListConverter;
use Sioen\Types\ParagraphConverter;
use Sioen\Types\BaseConverter;

class ToHtmlContext
{
    protected $converter = null;

    public function __construct($type, $options)
    {
        switch ($type) {
            case 'heading':
                $this->converter = new HeadingConverter($options);
                break;
            case 'list':
                $this->converter = new ListConverter($options);
                break;
            case 'quote':
                $this->converter = new BlockquoteConverter($options);
                break;
            case 'video':
                $this->converter = new IframeConverter($options);
                break;
            case 'image':
                $this->converter = new ImageConverter($options);
                break;
            default:
                $this->converter = new BaseConverter($options);
                break;
        }
    }

    public function getHtml(array $data)
    {
        return $this->converter->toHtml($data);
    }
}
