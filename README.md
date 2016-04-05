A Sir Trevor JSON to HTML Converter
===================================

[![Build Status](https://travis-ci.org/WouterSioen/sir-trevor-php.svg?branch=master)](https://travis-ci.org/WouterSioen/sir-trevor-php) [![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/WouterSioen/sir-trevor-php/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/WouterSioen/sir-trevor-php/?branch=master) [![SensioLabsInsight](https://insight.sensiolabs.com/projects/f78658cc-06f2-45b0-8704-68aaa0984d38/mini.png)](https://insight.sensiolabs.com/projects/f78658cc-06f2-45b0-8704-68aaa0984d38)


Introduction
------------

This is a Conversion library that handles the input from [Sir Trevor](http://madebymany.github.io/sir-trevor-js/)
and converts it to HTML. In the future, the conversion from HTML to the
Json input Sir Trevor needs will be implemented too.


### Supported Sir Trevor blocks

 - Heading
 - Paragraph
 - List
 - Video
 - Quote
 - Image

It's easy to add a custom block. Just add a new ConversionType in the Sioen\Types namespace and register it in the ToJsonContect and the ToHtmlContect.


Requirement
-----------

This library package requires PHP 5.3 or later.


Installation
------------

Require the library in your composer.json:

run `composer require woutersioen/sir-trevor-php`.

Make sure you have `require 'vendor/autoload.php';` in the top of your script. If you're using a Framework, this should be ok by default.


Usage
-----

### Overal

    // add the composer autoloader to your file
    require_once 'vendor/autoload.php';

    // Add the needed use statements to be able to use this library
    use Sioen\HtmlToJson;
    use Sioen\JsonToHtml;

### Conversion to HTML

    // fetch the data from the post
    $sirTrevorInput = $_POST['textarea'];

    // create a JsonToHtml object
    $jsonToHtml = new JsonToHtml();

    // add the wanted converters (you'll probably want to use your DIC container or a factory)
    $jsonToHtml->addConverter(new Sioen\JsonToHtml\BlockquoteConverter());
    $jsonToHtml->addConverter(new Sioen\JsonToHtml\HeadingConverter());
    $jsonToHtml->addConverter(new Sioen\JsonToHtml\IframeConverter());
    $jsonToHtml->addConverter(new Sioen\JsonToHtml\ImageConverter());
    $jsonToHtml->addConverter(new Sioen\JsonToHtml\BaseConverter());

    // generate your html
    $html = $jsonToHtml->toHtml($sirTrevorInput);

### Conversion to Json

    // fetch html from database or wherever you want to fetch it from
    $html = '<h2>This is my html</h2>';

    // create a HtmlToJson object
    $htmlToJson = new HtmlToJson();

    // add the wanted converters (you'll probably want to use your DIC container or a factory)
    $jsonToHtml->addConverter(new Sioen\HtmlToJson\BlockquoteConverter());
    $jsonToHtml->addConverter(new Sioen\HtmlToJson\HeadingConverter());
    $jsonToHtml->addConverter(new Sioen\HtmlToJson\ImageConverter());
    $jsonToHtml->addConverter(new Sioen\HtmlToJson\IframeConverter());
    $jsonToHtml->addConverter(new Sioen\HtmlToJson\ListConverter());
    $jsonToHtml->addConverter(new Sioen\HtmlToJson\BaseConverter());

    // generate your json
    $json = $htmlToJson->toJson($html);

### Adding your own converters.

Create a class that implements/extends the right abstraction

HtmlToJson converters should extend `Sioen\HtmlToJson\Converter`
JsonToHtml converters should implement `Sioen\JsonToHtml\Converter`

You can add your own converts using the `addConverter` method.
