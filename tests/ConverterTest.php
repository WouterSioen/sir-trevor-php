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

    public function testQuoteToHtml()
    {
        $converter = new Converter();

        // with cite
        $html = $converter->quoteToHtml('Text', 'Cite');
        $this->assertEquals(
            $html,
            "<blockquote><p>Text</p>\n<cite><p>Cite</p>\n</cite></blockquote>"
        );

        // without cite
        $html = $converter->quoteToHtml('Text');
        $this->assertEquals($html, "<blockquote><p>Text</p>\n</blockquote>");

        // with empty cite
        $html = $converter->quoteToHtml('Text', '');
        $this->assertEquals($html, "<blockquote><p>Text</p>\n</blockquote>");
    }

    public function testToHtml()
    {
        $converter = new Converter();

        // let's try a basic json
        $json = 
'{"data": [{
  "type": "quote",
  "data": { "text": "Text", "cite": "Cite" }
}]}';
        $html = $converter->toHtml($json);
        $this->assertEquals(
            $html,
            "<blockquote><p>Text</p>\n<cite><p>Cite</p>\n</cite></blockquote>"
        );

        // Lets convert a normal text type that uses the default converter
        $json = 
'{"data": [{
  "type": "text",
  "data": { "text": "test" }
}]}';
        $html = $converter->toHtml($json);
        $this->assertEquals($html, "<p>test</p>\n");

        $json = '{"data":[{"type":"embedly","data":{"provider_url":"http://www.youtube.com/","description":"For more articles and videos subscribe to my YouTube channel or visit http://weblogs.asp.net/dwahlin. In this video you\'ll learn how to get started with the AngularJS SPA framework. First you\'ll be introduced to what a SPA is and AngularJS features that simplify building SPAs. From there you\'ll see how to use directives, filters and data binding techniques.","title":"AngularJS Fundamentals In 60-ish Minutes","url":"http://www.youtube.com/watch?v=i9MHigUZKEM","author_name":"Dan Wahlin","height":480,"thumbnail_width":480,"width":640,"html":"<iframe width=\"640\" height=\"480\" src=\"http://www.youtube.com/embed/i9MHigUZKEM?feature=oembed\" frameborder=\"0\" allowfullscreen></iframe>","author_url":"http://www.youtube.com/user/dwahlin","version":"1.0","provider_name":"YouTube","thumbnail_url":"http://i1.ytimg.com/vi/i9MHigUZKEM/hqdefault.jpg","type":"video","thumbnail_height":360}}]}';
        $html = $converter->toHtml($json);
        $this->assertEquals($html, '<iframe width="640" height="480" src="http://www.youtube.com/embed/i9MHigUZKEM?feature=oembed" frameborder="0" allowfullscreen></iframe>');
    }
}