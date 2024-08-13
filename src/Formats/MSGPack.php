<?php

namespace Nathanmac\Utilities\Parser\Formats;

use Nathanmac\Utilities\Parser\Exceptions\ParserException;

/**
 * MSGPack Formatter
 *
 * @package    Nathanmac\Utilities\Parser\Formats
 * @author     Nathan Macnamara <hola@nathanmac.com>
 * @license    https://github.com/nathanmac/Parser/blob/master/LICENSE.md  MIT
 */
class MSGPack implements FormatInterface
{
    /**
     * Parse Payload Data
     *
     * @param string $payload
     *
     * @throws ParserException
     *
     * @return array
     */
    public function parse($payload)
    {
        if (function_exists('msgpack_unpack')) {
            if ($payload) {
                set_error_handler(function (int $errno, string $errstr) {
                    throw new \Exception($errstr);  // @codeCoverageIgnore
                });

                try {
                    $msg = msgpack_unpack(trim($payload));
                    if ( ! $msg) {
                        throw new \Exception('Unknown error');  // @codeCoverageIgnore
                    }
                } catch (\Exception $e) {
                    restore_error_handler();
                    throw new ParserException('Failed To Parse MSGPack - ' . $e->getMessage());
                }

                restore_error_handler();

                return $msg;
            }
            return [];
        }

        throw new ParserException('Failed To Parse MSGPack - Supporting Library Not Available');  // @codeCoverageIgnore
    }
}
