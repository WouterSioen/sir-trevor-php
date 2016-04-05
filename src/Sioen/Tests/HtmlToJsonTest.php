<?php

namespace Sioen\Tests;

use Sioen\HtmlToJson;
use Sioen\HtmlToJson\HeadingConverter;
use Sioen\HtmlToJson\BlockquoteConverter;
use Sioen\HtmlToJson\ImageConverter;

class HtmlToJsonTest extends \PHPUnit_Framework_TestCase
{
    public function testToJson()
    {
        $htmlToJson = new HtmlToJson();
        $htmlToJson->addConverter(new HeadingConverter());
        $this->assertEquals(
            $htmlToJson->toJson('<h2>Test</h2>'),
            '{"data":[{"type":"heading","data":{"text":" Test"}}]}'
        );

        // a quote
        $htmlToJson->addConverter(new BlockquoteConverter());
        $this->assertEquals(
            $htmlToJson->toJson('<blockquote><p>Text</p><cite>Cite</cite></blockquote>'),
            '{"data":[{"type":"quote","data":{"text":" Text","cite":" Cite"}}]}'
        );

        $this->assertEquals(
            $htmlToJson->toJson('<blockquote><p>Text</p></blockquote>'),
            '{"data":[{"type":"quote","data":{"text":" Text","cite":""}}]}'
        );

        $htmlToJson->addConverter(new ImageConverter());
        $this->assertEquals(
            $htmlToJson->toJson('<img src="/path/to/img.jpg" />'),
            '{"data":[{"type":"image","data":{"file":{"url":"\/path\/to\/img.jpg"}}}]}'
        );
    }
}
