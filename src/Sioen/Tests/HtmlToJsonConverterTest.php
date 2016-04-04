<?php

namespace Sioen\Tests;

use Sioen\HtmlToJsonConverter;

class HtmlToJsonConverterTest extends \PHPUnit_Framework_TestCase
{
    public function testToJson()
    {
        $converter = new HtmlToJsonConverter();
        $this->assertEquals(
            $converter->toJson('<h2>Test</h2>'),
            '{"data":[{"type":"heading","data":{"text":" Test"}}]}'
        );

        // a quote
        $this->assertEquals(
            $converter->toJson('<blockquote><p>Text</p><cite>Cite</cite></blockquote>'),
            '{"data":[{"type":"quote","data":{"text":" Text","cite":" Cite"}}]}'
        );

        $this->assertEquals(
            $converter->toJson('<blockquote><p>Text</p></blockquote>'),
            '{"data":[{"type":"quote","data":{"text":" Text","cite":""}}]}'
        );

        $this->assertEquals(
            $converter->toJson('<img src="/path/to/img.jpg" />'),
            '{"data":[{"type":"image","data":{"file":{"url":"\/path\/to\/img.jpg"}}}]}'
        );
    }
}
