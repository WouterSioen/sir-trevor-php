<?php

namespace Sioen\Tests;

use Sioen\HtmlToJson;

class HtmlToJsonTest extends \PHPUnit_Framework_TestCase
{
    public function testToJson()
    {
        $htmlToJson = new HtmlToJson();
        $this->assertEquals(
            $htmlToJson->toJson('<h2>Test</h2>'),
            '{"data":[{"type":"heading","data":{"text":" Test"}}]}'
        );

        // a quote
        $this->assertEquals(
            $htmlToJson->toJson('<blockquote><p>Text</p><cite>Cite</cite></blockquote>'),
            '{"data":[{"type":"quote","data":{"text":" Text","cite":" Cite"}}]}'
        );

        $this->assertEquals(
            $htmlToJson->toJson('<blockquote><p>Text</p></blockquote>'),
            '{"data":[{"type":"quote","data":{"text":" Text","cite":""}}]}'
        );

        $this->assertEquals(
            $htmlToJson->toJson('<img src="/path/to/img.jpg" />'),
            '{"data":[{"type":"image","data":{"file":{"url":"\/path\/to\/img.jpg"}}}]}'
        );
    }
}
