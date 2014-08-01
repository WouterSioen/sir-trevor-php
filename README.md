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

    {
        "require": {
            "woutersioen/sir-trevor-php": "dev-master"
        }
    }

Then `composer install` and add `require 'vendor/autoload.php';` to the top of your script.


Usage
-----

### Overal

    // add the composer autoloader to your file
    require_once 'vendor/autoload.php';

    // Add a use statement to be able to use the class
    use Sioen\Converter;

### Conversion to HTML

    // fetch the data from the post
    $sirTrevorInput = $_POST['textarea'];

    // create a converter object and handle the input
    $converter = new Converter();
    $html = $converter->toHtml($sirTrevorInput);

### Conversion to Json

    // fetch html from database or wherever you want to fetch it from
    $html = '<h2>This is my html</h2>';

    // create a converter object and handle the output
    $converter = new Converter();
    $json = $converter->toJson($html);
