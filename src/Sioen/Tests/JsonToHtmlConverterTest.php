<?php

namespace Sioen\Tests;

use Sioen\JsonToHtmlConverter;

class JsonToHtmlConverterTest extends \PHPUnit_Framework_TestCase
{
    public function testToHtml()
    {
        $converter = new JsonToHtmlConverter();

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
}
