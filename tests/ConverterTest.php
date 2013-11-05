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

        // test with italic
        $html = $converter->defaultToHtml('test _italic_ test');
        $this->assertEquals($html, "<p>test <em>italic</em> test</p>\n");

        // test with bold
        $html = $converter->defaultToHtml('test __bold__ test');
        $this->assertEquals($html, "<p>test <strong>bold</strong> test</p>\n");
    }

    public function testHeadingToHtml()
    {
        $converter = new Converter();

        $html = $converter->headingToHtml('Heading');
        $this->assertEquals($html, "<h2>Heading</h2>\n");
    }

    public function testEmbedlyToHtml()
    {
        $converter = new Converter();

        $html = $converter->embedlyToHtml(
            'http://www.youtube.com/',
            'In this video you\'ll learn how to get started with the AngularJS SPA framework.',
            'AngularJS Fundamentals In 60-ish Minutes',
            'http://www.youtube.com/watch?v=i9MHigUZKEM',
            'Dan Wahlin', 480, 480, 640,
            '<iframe width="640" height="480" src="http://www.youtube.com/embed/i9MHigUZKEM?feature=oembed" frameborder="0" allowfullscreen></iframe>',
            'http://www.youtube.com/user/dwahlin',
            '1.0', 'YouTube', 'http://i1.ytimg.com/vi/i9MHigUZKEM/hqdefault.jpg',
            'video', 360
        );
        $this->assertEquals(
            $html,
            "<iframe width=\"640\" height=\"480\" src=\"http://www.youtube.com/embed/i9MHigUZKEM?feature=oembed\" frameborder=\"0\" allowfullscreen></iframe>\n"
        );
    }

    public function testQuoteToHtml()
    {
        $converter = new Converter();

        // with cite
        $html = $converter->quoteToHtml('Text', '> Cite');
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

        // the embedly conversion is a little bit nastier
        $json = 
'{"data": [{
  "type":"embedly",
  "data": {
    "provider_url":"http://www.youtube.com/",
    "description":"In this video you\'ll learn how to get started with the AngularJS SPA framework.",
    "title":"AngularJS Fundamentals In 60-ish Minutes",
    "url":"http://www.youtube.com/watch?v=i9MHigUZKEM",
    "author_name":"Dan Wahlin",
    "height":480,
    "thumbnail_width":480,
    "width":640,
    "html":"<iframe width=\"640\" height=\"480\" src=\"http://www.youtube.com/embed/i9MHigUZKEM?feature=oembed\" frameborder=\"0\" allowfullscreen></iframe>",
    "author_url":"http://www.youtube.com/user/dwahlin",
    "version":"1.0",
    "provider_name":"YouTube",
    "thumbnail_url":"http://i1.ytimg.com/vi/i9MHigUZKEM/hqdefault.jpg",
    "type":"video",
    "thumbnail_height":360
  }
}]}';
        $html = $converter->toHtml($json);
        $this->assertEquals(
            $html,
            "<iframe width=\"640\" height=\"480\" src=\"http://www.youtube.com/embed/i9MHigUZKEM?feature=oembed\" frameborder=\"0\" allowfullscreen></iframe>\n"
        );

        // The heading
        $json = 
'{"data": [{
  "type": "heading",
  "data": { "text": "test" }
}]}';
        $html = $converter->toHtml($json);
        $this->assertEquals($html, "<h2>test</h2>\n");
    }

    public function testToJson()
    {
        $converter = new Converter();
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
    }

    public function testHeadingToJson()
    {
        $converter = new Converter();
        $this->assertEquals(
            $converter->headingToJson('<h2>Test</h2>'),
            array(
                'type' => 'heading',
                'data' => array(
                    'text' => ' Test'
                )
            )
        );
    }

    public function testListToJson()
    {
        $converter = new Converter();
        $this->assertEquals(
            $converter->listToJson('<ul><li>1</li><li>2</li></ul>'),
            array(
                'type' => 'list',
                'data' => array(
                    'text' => " - 1\n - 2"
                )
            )
        );
    }

    public function testParagraphToJson()
    {
        $converter = new Converter();
        $this->assertEquals(
            $converter->paragraphToJson('<p>Test</p>'),
            array(
                'type' => 'text',
                'data' => array(
                    'text' => ' Test'
                )
            )
        );

        // with em tags
        $this->assertEquals(
            $converter->paragraphToJson('<p>test <em>italic</em> test</p>'),
            array(
                'type' => 'text',
                'data' => array(
                    'text' => ' test _italic_ test'
                )
            )
        );

        // with strong tags
        $this->assertEquals(
            $converter->paragraphToJson('<p>test <strong>bold</strong> test</p>'),
            array(
                'type' => 'text',
                'data' => array(
                    'text' => ' test __bold__ test'
                )
            )
        );
    }

    public function testIframeToJson()
    {
        $converter = new Converter();
        $this->assertEquals(
            $converter->iframeToJson('<iframe src="http://google.be"></iframe>'),
            array(
                'type' => 'embedly',
                'data' => array(
                    'html' => '<iframe src="http://google.be"></iframe>'
                )
            )
        );
    }
}