<?php

namespace Sioen\Tests;

use Sioen\Converter;

class ConverterTest extends \PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        // require our converter class
        require_once dirname(__FILE__) . '/../Converter.php';
    }

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

    public function testVideoToHtml()
    {
        $converter = new Converter();

        $html = $converter->videoToHtml(
            'youtube',
            '4BXpi7056RM'
        );
        $this->assertEquals(
            $html,
            "<iframe src=\"//www.youtube.com/embed/4BXpi7056RM?rel=0\" frameborder=\"0\" allowfullscreen></iframe>\n"
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

    public function testImageToHtml()
    {
        $converter = new Converter();
        $html = $converter->imageToHtml(array('url' => '/path/to/img.jpg'));
        $this->assertEquals($html, '<img src="/path/to/img.jpg" />' . "\n");
    }

    public function testToHtml()
    {
        $converter = new Converter();

        // let's try a basic json
        $json = json_encode(array(
            'data' => array(array(
                'type' => 'quote',
                'data' => array(
                    'text' => 'Text',
                    'cite' => 'Cite'
                )
            ))
        ));
        $html = $converter->toHtml($json);
        $this->assertEquals(
            $html,
            "<blockquote><p>Text</p>\n<cite><p>Cite</p>\n</cite></blockquote>"
        );

        // Lets convert a normal text type that uses the default converter
        $json = json_encode(array(
            'data' => array(array(
                'type' => 'text',
                'data' => array(
                    'text' => 'test'
                )
            ))
        ));
        $html = $converter->toHtml($json);
        $this->assertEquals($html, "<p>test</p>\n");

        // the video conversion
        $json = json_encode(array(
            'data' => array(array(
                'type' => 'video',
                'data' => array(
                    'source' => 'youtube',
                    'remote_id' => '4BXpi7056RM'
                )
            ))
        ));
        $html = $converter->toHtml($json);
        $this->assertEquals(
            $html,
            "<iframe src=\"//www.youtube.com/embed/4BXpi7056RM?rel=0\" frameborder=\"0\" allowfullscreen></iframe>\n"
        );

        // The heading
        $json = json_encode(array(
            'data' => array(array(
                'type' => 'heading',
                'data' => array(
                    'text' => 'test'
                )
            ))
        ));
        $html = $converter->toHtml($json);
        $this->assertEquals($html, "<h2>test</h2>\n");

        // images
        $json = json_encode(array(
            'data' => array(array(
                'type' => 'image',
                'data' => array(
                    'file' => array(
                        'url' => '/frontend/files/sir-trevor/images/IMG_3867.JPG'
                    )
                )
            ))
        ));
        $html = $converter->toHtml($json);
        $this->assertEquals($html, "<img src=\"/frontend/files/sir-trevor/images/IMG_3867.JPG\" />\n");
    }

    /*
        Down here is the html to json conversion
    */

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
            $converter->iframeToJson(
                '<iframe src="//www.youtube.com/embed/4BXpi7056RM?rel=0" frameborder="0" allowfullscreen></iframe>'
            ),
            array(
                'type' => 'video',
                'data' => array(
                    'source' => 'youtube',
                    'remote_id' => '4BXpi7056RM'
                )
            )
        );

        $converter = new Converter();
        $this->assertEquals(
            $converter->iframeToJson(
                '<iframe src="//player.vimeo.com/video/63492364?title=0&amp;byline=0" frameborder="0"></iframe>'
            ),
            array(
                'type' => 'video',
                'data' => array(
                    'source' => 'vimeo',
                    'remote_id' => '63492364'
                )
            )
        );
    }

    public function testImageToJson()
    {
        $converter = new Converter();
        $this->assertEquals(
            $converter->imageToJson('/path/to/img.jpg'),
            array(
                'type' => 'image',
                'data' => array(
                    'file' => array(
                        'url' => '/path/to/img.jpg'
                    )
                )
            )
        );
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

        $this->assertEquals(
            $converter->toJson('<img src="/path/to/img.jpg" />'),
            '{"data":[{"type":"image","data":{"file":{"url":"\/path\/to\/img.jpg"}}}]}'
        );
    }
}
