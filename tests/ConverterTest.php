<?php
require_once dirname(__FILE__) . '/../Converter.php';

class ConverterTest extends PHPUnit_Framework_TestCase
{
    public function testDefaultToHtml()
    {
        $converter = new Converter();

        // lists
        $html = $converter->defaultToHtml(" - 1\n - 2");
        $this->assertEquals($html, "<ul>\n<li>1</li>\n<li>2</li>\n</ul>\n");

        // one paragraph
        $html = $converter->defaultToHtml('test');
        $this->assertEquals($html, "<p>test</p>\n");

        // two paragraphs
        $html = $converter->defaultToHtml("test\n\nline2");
        $this->assertEquals($html, "<p>test</p>\n\n<p>line2</p>\n");
    }

    public function testHeaderToHtml()
    {
        $converter = new Converter();

        $html = $converter->headerToHtml('Heading');
        $this->assertEquals($html, "<h2>Heading</h2>\n");
    }
}