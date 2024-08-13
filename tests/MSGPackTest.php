<?php

namespace Nathanmac\Utilities\Parser\Tests;

use Nathanmac\Utilities\Parser\Parser;
use PHPUnit\Framework\TestCase;

class MSGPackTest extends TestCase
{
    public function test_parser_validates_msgpack_data()
    {
        if (function_exists('msgpack_unpack')) {
            $expected = ['status' => 123, 'message' => 'hello world'];
            $payload  = msgpack_pack($expected);

            $parser = new Parser();
            $this->assertEquals($expected, $parser->msgpack($payload));
        }
    }

    public function test_parser_empty_msgpack_data()
    {
        if (function_exists('msgpack_unpack')) {
            $parser = new Parser();
            $this->assertEquals([], $parser->msgpack(""));
        }
    }

    public function test_throw_an_exception_when_msgpack_library_not_loaded()
    {
        if ( ! function_exists('msgpack_unpack')) {
            $this->expectException('Exception');
            $this->expectExceptionMessage('Failed To Parse MSGPack - Supporting Library Not Available');
            $parser = new Parser();
            $this->assertEquals([], $parser->msgpack(""));
        }
    }

    public function test_throws_an_exception_when_parsed_msgpack_bad_data()
    {
        if (function_exists('msgpack_unpack')) {
            $parser = new Parser();
            $this->expectException('Exception');
            $this->expectExceptionMessage('Failed To Parse MSGPack');
            $parser->msgpack('as|df>ASFBw924hg2=');
        }
    }

    public function test_format_detection_msgpack()
    {
        $parser = new Parser();

        $_SERVER['HTTP_CONTENT_TYPE'] = "application/msgpack";
        $this->assertEquals('Nathanmac\Utilities\Parser\Formats\MSGPack', $parser->getFormatClass());

        $_SERVER['HTTP_CONTENT_TYPE'] = "application/x-msgpack";
        $this->assertEquals('Nathanmac\Utilities\Parser\Formats\MSGPack', $parser->getFormatClass());

        unset($_SERVER['HTTP_CONTENT_TYPE']);
    }
}
