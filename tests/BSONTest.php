<?php

namespace Nathanmac\Utilities\Parser\Tests;

use Nathanmac\Utilities\Parser\Parser;
use PHPUnit\Framework\TestCase;

class BSONTest extends TestCase
{
    public function test_parser_validates_bson_data()
    {
        $expected  = ['status' => 123, 'message' => 'hello world'];

        if (function_exists('bson_encode')) {
            $payload = bson_encode($expected);
        } elseif (function_exists('MongoDB\BSON\fromPHP')) {
            $payload = \MongoDB\BSON\fromPHP($expected);
        }

        if (function_exists('bson_decode') || function_exists('MongoDB\BSON\toPHP')) {
            $parser = new Parser();
            $this->assertEquals($expected, $parser->bson($payload));
        }
    }

    public function test_parser_empty_bson_data()
    {
        if (function_exists('bson_decode') || function_exists('MongoDB\BSON\toPHP')) {
            $parser = new Parser();
            $this->assertEquals([], $parser->bson(""));
        }
    }

    public function test_throw_an_exception_when_bson_library_not_loaded()
    {
        if ( ! (function_exists('bson_decode') || function_exists('MongoDB\BSON\toPHP'))) {
            $this->expectException('Exception');
            $this->expectExceptionMessage('Failed To Parse BSON - Supporting Library Not Available');

            $parser = new Parser();
            $this->assertEquals([], $parser->bson(""));
        }
    }

    public function test_throws_an_exception_when_parsed_bson_bad_data()
    {
        $parser = new Parser();
        if (function_exists('bson_decode') || function_exists('MongoDB\BSON\toPHP')) {
            $this->expectException('Exception');
            $this->expectExceptionMessage('Failed To Parse BSON');
        } else {
            $this->expectException('Exception');
            $this->expectExceptionMessage('Failed To Parse BSON - Supporting Library Not Available');
        }
        $parser->bson('as|df>ASFBw924hg2=');
    }

    public function test_format_detection_bson()
    {
        $parser = new Parser();

        $_SERVER['HTTP_CONTENT_TYPE'] = "application/bson";
        $this->assertEquals('Nathanmac\Utilities\Parser\Formats\BSON', $parser->getFormatClass());

        unset($_SERVER['HTTP_CONTENT_TYPE']);
    }
}
