A Sir Trevor JSON to HTML Converter
===================================

[![Build Status](https://travis-ci.org/WouterSioen/sir-trevor-php.png?branch=master)](https://travis-ci.org/WouterSioen/sir-trevor-php)


Introduction
------------

This is a Conversion library that handles the input from [Sir Trevor](http://madebymany.github.io/sir-trevor-js/)
and converts it to HTML. In the future, the conversion from HTML to the 
Json input Sir Trevor needs will be implemented too.


### Supported Sir Trevor blocks

 - Heading
 - Paragraph
 - List
 - Embedly
 - Quote

More coming soon


Requirement
-----------

This library package requires PHP 5.3 or later.


Installation
------------

Either include HTML_To_Markdown.php directly:

    require_once(dirname( __FILE__) . '/Composer.php');

Or, require the library in your composer.json:

    {
        "require": {
            "woutersioen/sir-trevor-php": "dev-master"
        }
    }

Then `composer install` and add `require 'vendor/autoload.php';` to the top of your script.


Usage
-----

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
    $json = $converter->toJson($html);
