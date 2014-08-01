<?php

namespace Sioen;

use Sioen\Types\BlockquoteConverter;
use Sioen\Types\HeadingConverter;
use Sioen\Types\IframeConverter;
use Sioen\Types\ImageConverter;
use Sioen\Types\ListConverter;
use Sioen\Types\BaseConverter;

class ToHtmlContext
{
    protected $converter = null;

    public function __construct($type)
    {
        switch ($type) {
            case 'heading':
                $this->converter = new HeadingConverter();
                break;
            case 'list':
                $this->converter = new ListConverter();
                break;
            case 'quote':
                $this->converter = new BlockquoteConverter();
                break;
            case 'video':
                $this->converter = new IframeConverter();
                break;
            case 'image':
                $this->converter = new ImageConverter();
                break;
            default:
                $this->converter = new BaseConverter();
                break;
        }
    }

    public function getHtml(array $data)
    {
        return $this->converter->toHtml($data);
    }
}
